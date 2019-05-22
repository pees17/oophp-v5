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
 */
class ContentController implements AppInjectableInterface
{
    use AppInjectableTrait;


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
        $this->app->db->connect();
        $sql = "SELECT * FROM content;";
        $res = $this->app->db->executeFetchAll($sql);

        // Add view
        $data = [
            "edit" => false,
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/index", $data);
        $this->app->page->add("content/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the add method action, it handles:
     * GET mountpoint/add
     * It will add a new movie to the database an then redirct to the
     * edit movie view.
     *
     * @return object redirect to mountpoint/edit
     */
    public function addActionGet() : object
    {
        // Update the database
        $this->app->db->connect();
        $sql = "INSERT INTO movie (title, year, image) VALUES (?, ?, ?);";
        $this->app->db->execute($sql, ["A title", 2019, "img/noimage.png"]);

        // Get the id of the created movie
        $movieId = $this->app->db->lastInsertId();

        // Redirect to edit
        return $this->app->response->redirect("movie/edit/$movieId");
    }


    /**
     * This is the edit method action, it handles:
     * GET mountpoint/edit
     * It will render the view to edit a movie
     *
     * @param string $movieId the id of the movie to edit
     *
     * @return object rendering the edit movie view
     */
    public function editActionGet($movieId) : object
    {
        $title = "Edit movie";

        // Get data from database
        $this->app->db->connect();
        $sql = "SELECT * FROM movie WHERE id = ?;";
        $res = $this->app->db->executeFetchAll($sql, [$movieId]);

        $data = [
            "res" => $res,
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/edit", $data);
        $this->app->page->add("movie/footer");

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the edit method action, it handles:
     * POST mountpoint/edit
     * It will update the movie an then redirect back to to the edit view.
     *
     * @param string $movieId the id of the movie to edit
     *
     * @return object redirect to mountpoint/edit
     */
    public function editActionPost($movieId) : object
    {
        // Get POST data
        $movieTitle = $this->app->request->getPost("movieTitle");
        $movieYear  = $this->app->request->getPost("movieYear");
        $movieImage = $this->app->request->getPost("movieImage");

        // Update the database
        $this->app->db->connect();
        $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
        $this->app->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);

        // Redirect to edit
        return $this->app->response->redirect("movie/edit/$movieId");
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
}
