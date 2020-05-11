<?php
/**
 * Load the movie database as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Movie database",
            "mount" => "movie",
            "handler" => "\Peo\Movie\MovieController",
        ],
    ]
];
