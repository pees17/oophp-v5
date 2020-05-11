<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceCreateObjectTest extends TestCase
{
    /**
     * Construct a Dice object and verify that the object has the expected
     * properties. Also the roll method is tested.
     * Use no arguments.
     */
    public function testCreateObjectNoArgument()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Peo\Dice\Dice", $dice);

        // Check that every roll is between 1 and 6
        $min = 100;
        $max = 0;
        for ($i = 0; $i < 1000; $i++) {
            $current = $dice->roll();
            if ($current > $max) {
                $max = $current;
            }
            if ($current < $min) {
                $min = $current;
            }
        }
        $this->assertLessThanOrEqual(6, $max);
        $this->assertGreaterThanOrEqual(1, $min);
    }

    /**
     * Construct a Dice object and verify that the object has the expected
     * properties. Also the roll method is tested.
     * Use an argument.
     */
    public function testCreateObjectWithArgument()
    {
        $dice = new Dice(13);
        $this->assertInstanceOf("\Peo\Dice\Dice", $dice);

        // Check that every roll is between 1 and 6
        $min = 100;
        $max = 0;
        for ($i = 0; $i < 1000; $i++) {
            $current = $dice->roll();
            if ($current > $max) {
                $max = $current;
            }
            if ($current < $min) {
                $min = $current;
            }
        }
        $this->assertLessThanOrEqual(13, $max);
        $this->assertGreaterThanOrEqual(1, $min);
    }
}
