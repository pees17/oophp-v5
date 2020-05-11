<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandCreateObjectTest extends TestCase
{
    /**
     * Construct a DiceHand object and verify that the object has the expected
     * properties. Also the roll, getValues, and getSum methods are tested.
     * Use no arguments.
     */
    public function testCreateObjectNoArgument()
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf("\Peo\Dice\DiceHand", $diceHand);

        // Check that every roll is between 1 and 6
        $min = 100;
        $max = 0;
        for ($i = 0; $i < 1000; $i++) {
            $diceHand = new DiceHand();
            $diceHand->roll();
            $current = $diceHand->getSum();
            if ($current > $max) {
                $max = $current;
            }
            if ($current < $min) {
                $min = $current;
            }
        }
        $this->assertLessThanOrEqual(12, $max);
        $this->assertGreaterThanOrEqual(2, $min);
    }

    /**
     * Construct a DiceHand object and verify that the object has the expected
     * properties. Also the roll, getValues, and getSum methods argument tested.
     * Use an argument.
     */
    public function testCreateObjectWithArgument()
    {
        $diceHand = new DiceHand(1);
        $this->assertInstanceOf("\Peo\Dice\DiceHand", $diceHand);

        // Check that every roll is between 1 and 6
        $min = 100;
        $max = 0;
        for ($i = 0; $i < 100; $i++) {
            $diceHand = new DiceHand(1);
            $diceHand->roll();
            $current = $diceHand->getSum();
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
}
