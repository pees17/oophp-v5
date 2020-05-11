<?php

namespace Peo\Guess;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for negative testing of the makeGuess method in class Guess
 */
class GuessMakeGuessNegTest extends TestCase
{

    /**
     * Verify that the makeGuess method throws the expected exeption for
     * out of bounds arguments (too low)
     */
    public function testMakeGuessLow()
    {
        $guess = new Guess();
        $this->assertInstanceOf("\Peo\Guess\Guess", $guess);

        $this->expectException(GuessException::class);
        $guess->makeGuess(0);
    }

    /**
     * Verify that the makeGuess method throws the expected exeption for
     * out of bounds arguments (too high)
     */
    public function testMakeGuessHigh()
    {
        $guess = new Guess();
        $this->assertInstanceOf("\Peo\Guess\Guess", $guess);

        $this->expectException(GuessException::class);
        $guess->makeGuess(101);
    }
}
