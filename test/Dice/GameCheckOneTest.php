<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for for the checkOne method in class Game.
 */
class GameCheckOneTest extends TestCase
{

    /**
     * Construct a game object with two dices per hand and verify that the
     * checkOne method works as expected when a '1' is thrown
     */
    public function testCheckOneTrue()
    {
        $game = new Game(["Computer", "Me"], 2);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Roll the dices until a '1' is thrown
        $dice1 = false;
        for (;;) {
            $game->roll();
            $dices = $game->getGraphicHand();

            foreach ($dices as $dice) {
                if (in_array($dice, ["dice-1"])) {
                    $dice1 = true;
                }
            }
            if ($dice1) {
                break;
            }
        }

        $this->assertTrue($game->checkOne());
    }

    /**
     * Construct a game object with two dices per hand and verify that the
     * checkOne method works as expected when no '1' is thrown
     */
    public function testCheckOneFalse()
    {
        $game = new Game(["Computer", "Me"], 2);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Roll the dices until a hand with no '1' is thrown
        for (;;) {
            $dice1 = false;
            $game->roll();
            $dices = $game->getGraphicHand();

            foreach ($dices as $dice) {
                if (in_array($dice, ["dice-1"])) {
                    $dice1 = true;
                }
            }
            if (!$dice1) {
                break;
            }
        }

        $this->assertTrue(!$game->checkOne());
    }
}
