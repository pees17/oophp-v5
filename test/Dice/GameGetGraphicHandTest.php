<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the getGraphicHand method in class Game.
 */
class GameGetGraphicHandTest extends TestCase
{

    /**
     * Construct a game object with five dices per hand and verify that the
     * getGraphicHand method works as expected.
     */
    public function testRollFiveDices()
    {
        $game = new Game(["Computer", "Me"], 100, 5);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Check that every roll produces correct output
        $valid = ["dice-1", "dice-2", "dice-3", "dice-4", "dice-5", "dice-6"];
        $res = true;
        for ($i = 0; $i < 100; $i++) {
            $game->roll();
            $dices = $game->getGraphicHand();

            foreach ($dices as $dice) {
                if (!in_array($dice, $valid)) {
                    $res = false;
                }
            }
        }
        $this->assertTrue($res);
    }
}
