<?php
namespace Peo\Content;

/**
 * A class with methods to handle content of type post in a database.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class BlogHandler
{
    /**
     * Return all rows from the content table where type = 'post'
     *
     * @param \Anax\Database\Database $db the framework database handler
     *
     * @return array all 'post' rows in the content table
     */
    public function fetchAll($db)
    {
        $sql = <<<EOD
SELECT
    *,
    GREATEST(COALESCE(updated, published), published) AS published_iso8601,
    GREATEST(COALESCE(updated, published), published) AS published
FROM content
WHERE
    type=?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
ORDER BY published DESC
;
EOD;
        return $db->executeFetchAll($sql, ["post"]);
    }

    /**
     * Return the row from the content table where type = 'post and
     * slug = $slug
     *
     * @param \Anax\Database\Database $db the framework database handler
     * @param string $slug path to the post
     *
     * @return array the matching row in the content table
     */
    public function fetchPost($db, $slug)
    {
        $sql = <<<EOD
SELECT
    *,
    GREATEST(COALESCE(updated, published), published) AS published_iso8601,
    GREATEST(COALESCE(updated, published), published) AS published
FROM content
WHERE
    slug = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
;
EOD;
        return $db->executeFetch($sql, [$slug, "post"]);
    }
}
