<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the addPoints method in class Game.
 */
class GameAddPointsTest extends TestCase
{

    /**
     * Construct a game object with two dices per hand and verify that the
     * addPoints method works as expected
     */
    public function testAddPoints()
    {
        $game = new Game(["Computer", "Me"], 100, 2);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Roll the dices
        $game->roll();
        $res = $game->getSumCurrent();

        // First check that points are null for both players
        $players = $game->getPlayers();
        $this->assertNull($players["Computer"]);
        $this->assertNull($players["Me"]);

        // Add the $points
        $game->addPoints("Computer");
        $game->addPoints("Me");

        // Check that points are correct after adding
        $players = $game->getPlayers();
        $this->assertEquals($res, $players["Computer"]);
        $this->assertEquals($res, $players["Me"]);
    }
}
