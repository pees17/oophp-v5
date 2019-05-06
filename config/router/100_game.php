<?php
/**
 * Load the dice 100 game as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Dice 100 game.",
            "mount" => "dice100",
            "handler" => "\Peo\Dice\DiceController",
        ],
    ]
];
