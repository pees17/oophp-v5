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
    * @var DbHandler    $dbHandler database handler
    * @var PageHandler  $pageHandler database handler
    * @var BlogHandler  $blogHandler database handler
     */
    private $dbHandler;
    private $pageHandler;
    private $blogHandler;

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
        $this->pageHandler = new PageHandler();
        $this->blogHandler = new BlogHandler();
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

        // Not to get a database "uniqe" error if path field is empty
        if (!$params["contentPath"]) {
            $params["contentPath"] = null;
        }

        // Generate slug if slug field is empty
        if (!$params["contentSlug"]) {
            $params["contentSlug"] = slugify($params["contentTitle"]);
        }
        // Make sure slug is unique
        $slugs = $this->dbHandler->fetchSlugs($this->app->db);
        $params["contentSlug"] = makeSlugUnique($params["contentSlug"], $slugs);

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
     * It will render the view to delete a content
     *
     * @param string $id the id of the content to delete
     *
     * @return object rendering the delete content view
     */
    public function deleteActionGet($id) : object
    {
        $title = "Delete content";
        if (!is_numeric($id)) {
            throw new \Exception("Not valid for content id.");
        }

        // Get data from database
        $res = $this->dbHandler->fetchId($this->app->db, $id);

        // Add view
        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/delete", $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the delete method action, it handles:
     * POST mountpoint/delete
     * It will delete a content and then redirect to the admin route
     *
     * @param string $id the id of the content to delete
     *
     * @return object redirect to mountpoint/admin
     */
    public function deleteActionPost($id) : object
    {
        // Soft delete content in database
        $this->dbHandler->delete($this->app->db, $id);

        // Redirect to index
        return $this->app->response->redirect("content/admin");
    }


    /**
     * This is the reset method action, it handles:
     * GET METHOD mountpoint/reset
     * It will render the reset view to reset the database to its
     * initial state
     *
     * @return object rendering the reset view
     */
    public function resetActionGet() : object
    {
        $title = "Resetting the database";

        // Add view
        $this->app->page->add("content/header");
        $this->app->page->add("content/reset");

        // Render view
        return $this->app->page->render([
            "title" => $title
        ]);
    }


    /**
     * This is the reset method action, it handles:
     * POST METHOD mountpoint/reset
     * It will reset the database to its initial state and render
     * a view to show the result
     *
     * @return object rendering the reset view
     */
    public function resetActionPost() : object
    {
        $title = "Resetting the database";

        // Reset
        $output = $this->resetDb();

        // Add view
        $data = [
            "output" => $output
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/reset-result", $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the pages method action, it handles:
     * GET mountpoint/pages
     * It will render the view that lists all pages in the database.
     *
     * @return object rendering the pages view
     */
    public function pagesActionGet() : object
    {
        $title = "View pages";

        // Get data from database
        $res = $this->pageHandler->fetchAll($this->app->db);

        // Add view
        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/pages", $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the page method action, it handles:
     * GET mountpoint/page
     * It will render the view of a specific page.
     *
     * @param string $path to the page
     *
     * @return object rendering the page view
     */
    public function pageActionGet($path) : object
    {
        // Get data from database
        $res = $this->pageHandler->fetchPage($this->app->db, $path);

        // If no data the path was invalid
        if (!$res) {
            throw new \Anax\Route\Exception\NotFoundException;
        }

        // Add view
        $title = $res->title;
        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add("content/page", $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the blog method action, it handles:
     * GET mountpoint/blog
     * It will render the view of a specific blog post or if $path
     * is not set it will render a view of all published blog posts.
     *
     * @param string $slug path to the blog post
     *
     * @return object rendering the blog view
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function blogActionGet($slug = null) : object
    {
        if (!$slug) {
            // View all blog posts
            $title = "View blog";
            $view = "content/blog";
            $res = $this->blogHandler->fetchAll($this->app->db);
        } else {
            // View specific blog post
            $view = "content/blogpost";
            $res = $this->blogHandler->fetchPost($this->app->db, $slug);

            // If no data the path was invalid
            if (!$res) {
                throw new \Anax\Route\Exception\NotFoundException;
            }
            $title = $res->title;
        }
        // Add view
        $data = [
            "res" => $res,
        ];
        $this->app->page->add("content/header");
        $this->app->page->add($view, $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is a private method to do the resetting of the database.
     *
     * @return string the result of the reset
     */
    private function resetDb() : string
    {
        // Get configuration
        $config = $this->app->configuration->load("database")["config"];

        // Set path to sql file and mysql command
        $file   = "../sql/content/setup.sql";
        $mysql  = $config["mysql"];

        // Extract hostname and databasename from dsn
        $dsnDetail = [];
        preg_match("/mysql:host=(.+);dbname=([^;.]+)/", $config["dsn"], $dsnDetail);
        $host = $dsnDetail[1];
        $database = $dsnDetail[2];
        $login = $config["username"];
        $password = $config["password"];

        // Execute command and return result
        $command = "$mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
        $output = [];
        $status = null;
        exec($command, $output, $status);
        $output = "<p>The command was: <code>$command</code>.<br>The command exit status was $status."
            . "<br>The output from the command was:</p><pre>"
            . print_r($output, 1)
            . "</pre>";

        return $output;
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
