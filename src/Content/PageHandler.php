<?php
namespace Peo\Content;

/**
 * A class with methods to handle content of type page in a database.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PageHandler
{
    /**
     * Return all rows from the content table where type = 'page'
     *
     * @param \Anax\Database\Database $db the framework database handler
     *
     * @return array all 'page' rows in the content table
     */
    public function fetchAll($db)
    {
        $sql = <<<EOD
SELECT
    *,
    CASE
        WHEN (deleted <= NOW()) THEN "isDeleted"
        WHEN (published <= NOW()) THEN "isPublished"
        ELSE "notPublished"
    END AS status
FROM content
WHERE type=?
;
EOD;
        return $db->executeFetchAll($sql, ["page"]);
    }

    /**
     * Return the row from the content table where type = 'page' and
     * path = $path
     *
     * @param \Anax\Database\Database $db the framework database handler
     * @param string $path path to the page
     *
     * @return array the matching row in the content table
     */
    public function fetchPage($db, $path)
    {
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(GREATEST(COALESCE(updated, published), published), '%Y-%m-%dT%TZ') AS modified_iso8601,
    DATE_FORMAT(GREATEST(COALESCE(updated, published), published), '%Y-%m-%d') AS modified
FROM content
WHERE
    path = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
;
EOD;
        return $db->executeFetch($sql, [$path, "page"]);
    }
}
