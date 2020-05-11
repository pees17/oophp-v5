<?php
/**
 * Load the data driven website as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Data driven website, with pages and blog posts",
            "mount" => "content",
            "handler" => "\Peo\Content\Content",
        ],
    ]
];
