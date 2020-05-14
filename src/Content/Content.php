<?php
namespace Peo\Content;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;
use Anax\Route\Exception\NotFoundException;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\InternalErrorException;
/**
 * A controller that handles the test/demo of the content database.
 *
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 */
class Content implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
    * @var PageHandler  $pageHandler database handler
    * @var BlogHandler  $blogHandler database handler
     */
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
        $this->pageHandler = new PageHandler();
        $this->blogHandler = new BlogHandler();
    }


    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     * It will render the view that shows the home page
     *
     * @return object redirect to the home page route
     */
    public function indexAction() : object
    {
        // Render the header
        $this->app->page->add("content/header");
        return $this->app->page->render([
            "title" => "Start"
        ]);
    }


    /**
     * This is the page method action, it handles:
     * GET mountpoint/page
     * It will render the view of a specific page.
     *
     * @throws NotFoundException when no data received
     *
     * @param string $path to the page
     *
     * @return object rendering the page
     */
    public function pageActionGet($path = null) : object
    {
        if (!$path) {
            // Page is not set
            $this->app->page->add("content/header");
            return $this->app->page->render([
                "title" => "Error"
            ]);
        }
        // Get data from database
        $res = $this->pageHandler->fetchPage($this->app->db, $path);

        // If no data the path was invalid
        if (!$res) {
            throw new NotFoundException;
        }
        // Add view
        $title = $res->title;
        $data = [
            "res" => $res,
            "filters" => explode(",", $res->filter),
            "viewModified" => false
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
     * @throws NotFoundException when no data received
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
                throw new NotFoundException;
            }
            $title = $res->title;
        }
        // Add view
        $data = [
            "res" => $res,
            "url" => "content/blog"
        ];
        $this->app->page->add("content/header");
        $this->app->page->add($view, $data);

        // Render view
        return $this->app->page->render([
            "title" => $title,
        ]);
    }
}
