<?php

namespace Peo\Guess;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the makeGuess method in class Guess
 */
class GuessMakeGuessTest extends TestCase
{

    /**
     * Construct object with 50 as the secret number and verify that the method
     * returns the expected result for too high, too low and correct argument.
     */
    public function testMakeGuessDifferentArg()
    {
        $guess = new Guess(50);
        $this->assertInstanceOf("\Peo\Guess\Guess", $guess);

        // Test too low
        $res = $guess->makeGuess(49);
        $exp = "TOO LOW.";
        $this->assertEquals($exp, $res);

        // Test correct
        $res = $guess->makeGuess(50);
        $exp = "CORRECT - Congratulations, you won!";
        $this->assertEquals($exp, $res);

        // Test too high
        $res = $guess->makeGuess(51);
        $exp = "TOO HIGH.";
        $this->assertEquals($exp, $res);
    }
}
