<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the getLastRoll method in class Dice.
 */
class DiceGetLastRollTest extends TestCase
{
    /**
     * Construct a Dice object and verify that the getLastRoll method works as expected
     */
    public function testGetLastRoll()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Peo\Dice\Dice", $dice);

        $exp = $dice->roll();
        $res = $dice->getLastRoll();
        $this->assertEquals($exp, $res);
    }
}
