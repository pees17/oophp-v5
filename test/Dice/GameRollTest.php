<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the roll method in class Game.
 */
class GameRollTest extends TestCase
{
    /**
     * Construct a game object with one dice per hand and verify that the roll
     * method works as expected.
     */
    public function testRollOneDice()
    {
        $game = new Game(["Computer", "Me"], 100, 1);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Check that every roll is between 1 and 6
        $min = 100;
        $max = 0;
        for ($i = 0; $i < 100; $i++) {
            $game->roll();
            $current = $game->getSumCurrent();
            if ($current > $max) {
                $max = $current;
            }
            if ($current < $min) {
                $min = $current;
            }
            $game->resetSumCurrent();
        }
        $this->assertLessThanOrEqual(6, $max);
        $this->assertGreaterThanOrEqual(1, $min);
    }

    /**
     * Construct a game object with five dices per hand and verify that the roll
     * method works as expected.
     */
    public function testRollFiveDices()
    {
        $game = new Game(["Computer", "Me"], 100, 5);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Check that every roll is between 1 and 6
        $min = 100;
        $max = 0;
        for ($i = 0; $i < 1000; $i++) {
            $game->roll();
            $current = $game->getSumCurrent();
            if ($current > $max) {
                $max = $current;
            }
            if ($current < $min) {
                $min = $current;
            }
            $game->resetSumCurrent();
        }
        $this->assertLessThanOrEqual(30, $max);
        $this->assertGreaterThanOrEqual(5, $min);
    }
}
