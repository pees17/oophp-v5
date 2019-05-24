<?php
namespace Peo\Content;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;
/**
 * A controller that handles the content database.
 *
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class ContentController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * @var DbHandler $dbHandler database handler
     */
    private $dbHandler;

    /**
     * The initialize method is optional and will always be called before
     * the target method/action.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Connect to database and create handler
        $this->app->db->connect();
        $this->dbHandler = new DbHandler();
    }


    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     * It will render the view that lists all content in the database
     *
     * @return object rendering the all content view
     */
    public function indexAction() : object
    {
        $title = "Show all content";

        // Get data from database
        $res = $this->dbHandler->fetchAll($this->app->db);

        // Add view
        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/index", $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the admin method action, it handles:
     * GET mountpoint/admin
     * It will render the view that lists all content in the database, and
     * administrate it (CRUD).
     *
     * @return object rendering the admin view
     */
    public function adminActionGet() : object
    {
        $title = "Admin content";

        // Get data from database
        $res = $this->dbHandler->fetchAll($this->app->db);

        // Add view
        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/admin", $data);
        $this->app->page->add("content/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the create method action, it handles:
     * GET mountpoint/create
     * It will render a view to create new content in the database
     *
     * @return object rendering the create view
     */
    public function createActionGet() : object
    {
        $title = "Create content";

        // Add view
        $this->app->page->add("content/header");
        $this->app->page->add("content/create");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the create method action, it handles:
     * POST mountpoint/create
     * It will create the content an then redirect to the edit route.
     *
     * @return object redirect to mountpoint/edit
     */
    public function createActionPost() : object
    {
        // Get POST data
        $contentTitle = [$this->app->request->getPost("contentTitle")];

        // Insert into database
        $id = $this->dbHandler->insert($this->app->db, ["title"], $contentTitle);

        // Redirect to edit
        return $this->app->response->redirect("content/edit/$id");
    }
    /**
     * This is the edit method action, it handles:
     * GET mountpoint/edit
     * It will render the view to edit a content in the database
     *
     * @param string $id the id of the content to edit
     *
     * @return object rendering the edit content view
     */
    public function editActionGet($id) : object
    {
        $title = "Edit content";
        if (!is_numeric($id)) {
            throw new \Exception("Not valid for content id.");
        }

        // Get data from database
        $res = $this->dbHandler->fetchId($this->app->db, $id);

        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/edit", $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the edit method action, it handles:
     * POST mountpoint/edit
     * It will update the content an then redirect back to to the edit route.
     *
     * @param string $id the id of the content to edit
     *
     * @return object redirect to mountpoint/edit
     */
    public function editActionPost($id) : object
    {
        // Get POST data
        $params = $this->getEditPost();

        // Make sure 'unique' fields are unique
        if (!$params["contentSlug"]) {
            $params["contentSlug"] = slugify($params["contentTitle"]);
        }
        if (!$params["contentPath"]) {
            $params["contentPath"] = null;
        }

        // Update the database
        $params["id"] = $id;
        $fields = ["title", "path", "slug", "data", "type", "filter", "published"];
        $this->dbHandler->update($this->app->db, $fields, array_values($params));

        // Redirect to edit
        return $this->app->response->redirect("content/edit/$id");
    }


    /**
     * This is the delete method action, it handles:
     * GET mountpoint/delete
     * It will render the view to delete a movie
     *
     * @param string $movieId the id of the movie to delete
     *
     * @return object rendering the delete movie view
     */
    public function deleteActionGet($movieId) : object
    {
        $title = "Delete movie";

        // Get data from database
        $this->app->db->connect();
        $sql = "SELECT * FROM movie WHERE id = ?;";
        $res = $this->app->db->executeFetchAll($sql, [$movieId]);

        $data = [
            "res" => $res,
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/delete", $data);
        $this->app->page->add("movie/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the delete method action, it handles:
     * POST mountpoint/delete
     * It will delete a movie and then render the view to view all movies
     *
     * @param string $movieId the id of the movie to delete
     *
     * @return object redirect to mountpoint/index
     */
    public function deleteActionPost($movieId) : object
    {
        // Get data from database
        $this->app->db->connect();
        $sql = "DELETE FROM movie WHERE id = ?;";
        $this->app->db->execute($sql, [$movieId]);

        // Redirect to index
        return $this->app->response->redirect("movie/index");
    }


    /**
     * This is the searchtitle method action, it handles:
     * GET mountpoint/searchtitle
     * It will render the view to search for movies based on their title
     * and view the movies that match
     *
     * @return object rendering the searchtitle view and result
     */
    public function searchTitleActionGet() : object
    {
        $title = "Search on title";

        // Get GET data
        $searchTitle = $this->app->request->getGet("searchTitle");

        // Get data from database
        $this->app->db->connect();
        $sql = "SELECT * FROM movie WHERE title LIKE ?;";
        $res = $this->app->db->executeFetchAll($sql, [$searchTitle]);

        // Add view
        $data = [
            "res" => $res,
            "searchTitle" => $searchTitle,
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/searchtitle", $data);
        $this->app->page->add("movie/index", $data);
        $this->app->page->add("movie/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the searchyear method action, it handles:
     * GET mountpoint/searchyear
     * It will render the view to search for movies based on their year
     * and view the movies that match
     *
     * @return object rendering the searchyear view and result
     */
    public function searchYearActionGet() : object
    {
        $title = "Search on year";

        // Get GET data
        $year1 = $this->app->request->getGet("year1");
        $year2 = $this->app->request->getGet("year2");

        // Get data from database
        $this->app->db->connect();
        $res = null;
        if ($year1 && $year2) {
            $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
            $res = $this->app->db->executeFetchAll($sql, [$year1, $year2]);
        } elseif ($year1) {
            $sql = "SELECT * FROM movie WHERE year >= ?;";
            $res = $this->app->db->executeFetchAll($sql, [$year1]);
        } elseif ($year2) {
            $sql = "SELECT * FROM movie WHERE year <= ?;";
            $res = $this->app->db->executeFetchAll($sql, [$year2]);
        }

        // Add view
        $data = [
            "res" => $res,
            "year1" => $year1,
            "year2" => $year2,
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/searchyear", $data);
        $this->app->page->add("movie/index", $data);
        $this->app->page->add("movie/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the reset method action, it handles:
     * ANY METHOD mountpoint/reset
     * It will render the reset view to reset the database to its
     * initial state
     *
     * @return object rendering the reset view
     */
    public function resetAction() : object
    {
        $title = "Resetting the database";

        // Get configuration
        $dbConfig = $this->app->configuration->load("database");

        // Add view
        $data = [
            "config" => $dbConfig["config"],
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/reset", $data);
        $this->app->page->add("movie/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is a private help method to get all post parameters
     * from the edit form into an array.
     *
     * @return array the post parameters
     */
    private function getEditPost() : array
    {
        // Get POST data
        $params = [];
        $params["contentTitle"] = $this->app->request->getPost("contentTitle");
        $params["contentPath"] = $this->app->request->getPost("contentPath");
        $params["contentSlug"] = $this->app->request->getPost("contentSlug");
        $params["contentData"] = $this->app->request->getPost("contentData");
        $params["contentType"] = $this->app->request->getPost("contentType");
        $params["contentFilter"] = $this->app->request->getPost("contentFilter");
        $params["contentPublish"] = $this->app->request->getPost("contentPublish");

        return $params;
    }
}
