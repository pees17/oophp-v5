<?php
/**
 * Load the movie database as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Content database, with pages and blog posts",
            "mount" => "content",
            "handler" => "\Peo\Content\ContentController",
        ],
    ]
];
