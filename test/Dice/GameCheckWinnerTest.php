<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the checkWinner method in class Game.
 */
class GameCheckWinnerTest extends TestCase
{
    /**
     * Construct a game object with one dice per hand and verify that the checkWinner
     * method works as expected when none of the players have reached the 100
     */
    public function testCheckWinnerNotAt100()
    {
        $game = new Game(["Computer", "Me"], 100, 1);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Loop while both players are below win level
        while ($game->getPlayers()["Computer"] < 100 &&
               $game->getPlayers()["Me"] < 100) {
            // No winner
            $this->assertNull($game->checkWinner());

            // Get points for first player
            $game->roll();
            $game->addPoints("Computer");

            // Get points for second player
            $game->roll();
            $game->addPoints("Me");
        }
    }

    /**
     * Construct a game object with one dice per hand and verify that the checkWinner
     * method works as expected when Computer has won
     */
    public function testCheckWinnerComputer()
    {
        $game = new Game(["Computer", "Me"], 100, 1);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        $loop = true;
        while ($loop) {
            // The while loop will continue until Computer has won
            $game = new Game(["Computer", "Me"], 100, 1);

            // Loop until a player is at or above win level
            while ($game->getPlayers()["Computer"] < 100 &&
                   $game->getPlayers()["Me"] < 100) {
                // Get points for first player
                $game->roll();
                $game->addPoints("Computer");
                $game->resetSumCurrent();

                // Get points for second player
                $game->roll();
                $game->addPoints("Me");
                $game->resetSumCurrent();
            }
            if ($game->getPlayers()["Computer"] > $game->getPlayers()["Me"]) {
                // Computer is winner
                $loop = false;
            }
        }
        $this->assertEquals("Computer", $game->checkWinner());
    }

    /**
     * Construct a game object with one dice per hand and verify that the checkWinner
     * method works as expected when player "Me" has won
     */
    public function testCheckWinnerMe()
    {
        $game = new Game(["Computer", "Me"], 100, 1);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        $loop = true;
        while ($loop) {
            // The while loop will continue until Me has won
            $game = new Game(["Computer", "Me"], 100, 1);

            // Loop until a player is at or above win level
            while ($game->getPlayers()["Computer"] < 100 &&
                   $game->getPlayers()["Me"] < 100) {
                // Get points for first player
                $game->roll();
                $game->addPoints("Computer");
                $game->resetSumCurrent();

                // Get points for second player
                $game->roll();
                $game->addPoints("Me");
                $game->resetSumCurrent();
            }
            if ($game->getPlayers()["Me"] > $game->getPlayers()["Computer"]) {
                // Computer is winner
                $loop = false;
            }
        }
        $this->assertEquals("Me", $game->checkWinner());
    }

    /**
     * Construct a game object with one dice per hand and verify that the checkWinner
     * method works as expected when both players are at 100 or above with the same points
     */
    public function testCheckWinnerTie()
    {
        $game = new Game(["Computer", "Me"], 100, 1);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        $loop = true;
        while ($loop) {
            // The while loop will continue until both players points are >= 100 and the same
            $game = new Game(["Computer", "Me"], 100, 1);

            // Loop until both players are at or above win level
            while ($game->getPlayers()["Computer"] < 100 ||
                   $game->getPlayers()["Me"] < 100) {
                // Get points for first player
                $game->roll();
                $game->addPoints("Computer");
                $game->resetSumCurrent();

                // Get points for second player
                $game->roll();
                $game->addPoints("Me");
                $game->resetSumCurrent();
            }
            if ($game->getPlayers()["Me"] == $game->getPlayers()["Computer"]) {
                // There is a tie
                $loop = false;
            }
        }
        $this->assertNull($game->checkWinner());
    }
}
