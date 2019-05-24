<?php
namespace Peo\Content;

/**
 * A class with methods to handle a database.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class DbHandler
{
    /**
     * Return all rows from the content database
     *
     * @param \Anax\Database\Database $db the framework database handler
     *
     * @return array all rows in the content table
     */
    public function fetchAll($db)
    {
        $sql = "SELECT * FROM content;";
        return $db->executeFetchAll($sql);
    }


    /**
     * Return slug from all rows from the content database
     *
     * @param \Anax\Database\Database $db the framework database handler
     *
     * @return array all slugs in the content table
     */
    public function fetchSlugs($db)
    {
        $sql = "SELECT * FROM content;";
        return array_map(function ($row) {
            return $row->slug;
        }, $db->executeFetchAll($sql));
    }


    /**
     * Return the row with a specific id from the content database
     *
     * @param \Anax\Database\Database $db the framework database handler
     * @param int $id the id in the database
     *
     * @return array the row in the content table
     */
    public function fetchId($db, $id)
    {
        $sql = "SELECT * FROM content WHERE id = ?;";
        return $db->executeFetch($sql, [$id]);
    }


    /**
     * Insert a row into the content table
     *
     * @param \Anax\Database\Database $db the framework database handler
     * @param array $fields the fields to set values for
     * @param array $values the values to be inserted
     *
     * @return int the id of the inserted row
     */
    public function insert($db, $fields, $values)
    {
        // Prepare arguments
        $fields = implode(", ", $fields);
        $qMarks = rtrim(str_repeat("?,", count($values)), ",");

        // Insert
        $sql = "INSERT INTO content ($fields) VALUES ($qMarks);";
        $db->execute($sql, $values);

        // Return the id of the created content
        return $db->lastInsertId();
    }


    /**
     * Update a row in the content table
     *
     * @param \Anax\Database\Database $db the framework database handler
     * @param array $fields the fields to update
     * @param array $values the values to use in update
     *
     * @return void
     */
    public function update($db, $fields, $values)
    {
        // Prepare arguments
        $fields = implode(" = ?, ", $fields) . " = ?";

        // Update the database
        $sql = "UPDATE content SET $fields WHERE id = ?;";
        $db->execute($sql, $values);
    }


    //
    //
    // /**
    //  * This is the edit method action, it handles:
    //  * GET mountpoint/edit
    //  * It will render the view to edit a movie
    //  *
    //  * @param string $movieId the id of the movie to edit
    //  *
    //  * @return object rendering the edit movie view
    //  */
    // public function editActionGet($movieId) : object
    // {
    //     $title = "Edit movie";
    //
    //     // Get data from database
    //     $this->app->db->connect();
    //     $sql = "SELECT * FROM movie WHERE id = ?;";
    //     $res = $this->app->db->executeFetchAll($sql, [$movieId]);
    //
    //     $data = [
    //         "res" => $res,
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/edit", $data);
    //     $this->app->page->add("movie/footer");
    //
    //     // Render view
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
    //
    //
    // /**
    //  * This is the edit method action, it handles:
    //  * POST mountpoint/edit
    //  * It will update the movie an then redirect back to to the edit view.
    //  *
    //  * @param string $movieId the id of the movie to edit
    //  *
    //  * @return object redirect to mountpoint/edit
    //  */
    // public function editActionPost($movieId) : object
    // {
    //     // Get POST data
    //     $movieTitle = $this->app->request->getPost("movieTitle");
    //     $movieYear  = $this->app->request->getPost("movieYear");
    //     $movieImage = $this->app->request->getPost("movieImage");
    //
    //     // Update the database
    //     $this->app->db->connect();
    //     $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
    //     $this->app->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
    //
    //     // Redirect to edit
    //     return $this->app->response->redirect("movie/edit/$movieId");
    // }
    //
    //
    // /**
    //  * This is the delete method action, it handles:
    //  * GET mountpoint/delete
    //  * It will render the view to delete a movie
    //  *
    //  * @param string $movieId the id of the movie to delete
    //  *
    //  * @return object rendering the delete movie view
    //  */
    // public function deleteActionGet($movieId) : object
    // {
    //     $title = "Delete movie";
    //
    //     // Get data from database
    //     $this->app->db->connect();
    //     $sql = "SELECT * FROM movie WHERE id = ?;";
    //     $res = $this->app->db->executeFetchAll($sql, [$movieId]);
    //
    //     $data = [
    //         "res" => $res,
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/delete", $data);
    //     $this->app->page->add("movie/footer");
    //
    //     // Render view
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
    //
    //
    // /**
    //  * This is the delete method action, it handles:
    //  * POST mountpoint/delete
    //  * It will delete a movie and then render the view to view all movies
    //  *
    //  * @param string $movieId the id of the movie to delete
    //  *
    //  * @return object redirect to mountpoint/index
    //  */
    // public function deleteActionPost($movieId) : object
    // {
    //     // Get data from database
    //     $this->app->db->connect();
    //     $sql = "DELETE FROM movie WHERE id = ?;";
    //     $this->app->db->execute($sql, [$movieId]);
    //
    //     // Redirect to index
    //     return $this->app->response->redirect("movie/index");
    // }
    //
    //
    // /**
    //  * This is the searchtitle method action, it handles:
    //  * GET mountpoint/searchtitle
    //  * It will render the view to search for movies based on their title
    //  * and view the movies that match
    //  *
    //  * @return object rendering the searchtitle view and result
    //  */
    // public function searchTitleActionGet() : object
    // {
    //     $title = "Search on title";
    //
    //     // Get GET data
    //     $searchTitle = $this->app->request->getGet("searchTitle");
    //
    //     // Get data from database
    //     $this->app->db->connect();
    //     $sql = "SELECT * FROM movie WHERE title LIKE ?;";
    //     $res = $this->app->db->executeFetchAll($sql, [$searchTitle]);
    //
    //     // Add view
    //     $data = [
    //         "res" => $res,
    //         "searchTitle" => $searchTitle,
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/searchtitle", $data);
    //     $this->app->page->add("movie/index", $data);
    //     $this->app->page->add("movie/footer");
    //
    //     // Render view
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
    //
    //
    // /**
    //  * This is the searchyear method action, it handles:
    //  * GET mountpoint/searchyear
    //  * It will render the view to search for movies based on their year
    //  * and view the movies that match
    //  *
    //  * @return object rendering the searchyear view and result
    //  */
    // public function searchYearActionGet() : object
    // {
    //     $title = "Search on year";
    //
    //     // Get GET data
    //     $year1 = $this->app->request->getGet("year1");
    //     $year2 = $this->app->request->getGet("year2");
    //
    //     // Get data from database
    //     $this->app->db->connect();
    //     $res = null;
    //     if ($year1 && $year2) {
    //         $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
    //         $res = $this->app->db->executeFetchAll($sql, [$year1, $year2]);
    //     } elseif ($year1) {
    //         $sql = "SELECT * FROM movie WHERE year >= ?;";
    //         $res = $this->app->db->executeFetchAll($sql, [$year1]);
    //     } elseif ($year2) {
    //         $sql = "SELECT * FROM movie WHERE year <= ?;";
    //         $res = $this->app->db->executeFetchAll($sql, [$year2]);
    //     }
    //
    //     // Add view
    //     $data = [
    //         "res" => $res,
    //         "year1" => $year1,
    //         "year2" => $year2,
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/searchyear", $data);
    //     $this->app->page->add("movie/index", $data);
    //     $this->app->page->add("movie/footer");
    //
    //     // Render view
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
    //
    //
    // /**
    //  * This is the reset method action, it handles:
    //  * ANY METHOD mountpoint/reset
    //  * It will render the reset view to reset the database to its
    //  * initial state
    //  *
    //  * @return object rendering the reset view
    //  */
    // public function resetAction() : object
    // {
    //     $title = "Resetting the database";
    //
    //     // Get configuration
    //     $dbConfig = $this->app->configuration->load("database");
    //
    //     // Add view
    //     $data = [
    //         "config" => $dbConfig["config"],
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/reset", $data);
    //     $this->app->page->add("movie/footer");
    //
    //     // Render view
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
}
