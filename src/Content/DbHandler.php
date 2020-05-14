<?php
namespace Peo\Content;

/**
 * A class with methods to handle CRUD of a database.
 *
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


    /**
     * Delete a row in the content table. It is a soft delete, meaning
     * that the row is still in the database but the deletefield is set
     * current time.
     *
     * @param \Anax\Database\Database $db the framework database handler
     * @param int $id the id in the database
     *
     * @return void
     */
    public function delete($db, $id)
    {
        // Update the database
        $sql = "UPDATE content SET deleted=NOW() WHERE id = ?;";
        $db->execute($sql, [$id]);
    }
}
