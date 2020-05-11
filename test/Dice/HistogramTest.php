<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for for the methods in class Histogram.
 */
class HistogramTest extends TestCase
{

    /**
     * Construct a game object with five dices per hand. Play 100
     * rounds, inject the data into the Histogram class and then
     * verify that the getSerie method works as expected
     */
    public function testGetSerie()
    {
        $game = new Game(["Computer", "Me"], 100, 5);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Play 10 hands
        for ($i = 0; $i < 100; $i++) {
            $game->roll();
        }

        $histogram = new Histogram();
        $this->assertInstanceOf("\Peo\Dice\Histogram", $histogram);

        $diceHand = $game->getDiceHand();
        $histogram->injectData($diceHand);

        // Get and verify the data
        $data = $histogram->getSerie();

        $this->assertEquals(500, count($data));  // Length should be 500

        // Check that every value is between 1 and 6
        $min = 100;
        $max = 0;
        foreach ($data as $dice) {
            if ($dice > $max) {
                $max = $dice;
            }
            if ($dice < $min) {
                $min = $dice;
            }
        }
        $this->assertLessThanOrEqual(6, $max);
        $this->assertGreaterThanOrEqual(1, $min);
    }

    /**
     * Construct a game object with five dices per hand. Play 10
     * rounds, inject the data into the Histogram class and then
     * verify that the testGetAsText method works as expected
     */
    public function testGetAsText()
    {
        $game = new Game(["Computer", "Me"], 10, 5);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        // Play 10 hands
        for ($i = 0; $i < 10; $i++) {
            $game->roll();
        }

        $histogram = new Histogram();
        $this->assertInstanceOf("\Peo\Dice\Histogram", $histogram);

        $diceHand = $game->getDiceHand();
        $histogram->injectData($diceHand);

        // Get and verify the data. It should be a string longer
        // than 60 characters, and ontaining 50 '*' characters

        $data = $histogram->getAsText();

        $this->assertGreaterThanOrEqual(70, strlen($data));
        $this->assertEquals(50, substr_count($data, '*'));
    }
}
