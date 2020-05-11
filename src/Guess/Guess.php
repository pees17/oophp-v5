<?php
namespace Peo\Guess;

/**
 * Guess my number, a class supporting the game through GET, POST and SESSION.
 */

class Guess
{
    /**
    * @var int $number     The current secret number.
    * @var int $tries      Number of tries a guess has been made.
    */
    private $number;
    private $tries;


    /**
    * Constructor to initiate the object with current game settings,
    * if available. Randomize the current number if no value is sent in.
    *
    * @param int $number The current secret number, default -1 to initiate
    *                    the number from start.
    * @param int $tries  Number of guesses allowed, default 6.
    */
    public function __construct(int $number = -1, int $tries = 6)
    {
        $this->random();
        $this->tries = $tries;

        if ($number != -1) {
            $this->number = $number;
        }
    }


    /**
    * Randomize the secret number between 1 and 100 to initiate a new game.
    *
    * @return void
    */
    public function random() : void
    {
        $this->number = rand(1, 100);
    }


    /**
    * Get number of tries left.
    *
    * @return int as number of tries left.
    */
    public function tries() : int
    {
        return $this->tries;
    }


    /**
    * Get the secret number.
    *
    * @return int as the secret number.
    */
    public function number() : int
    {
        return $this->number;
    }


    /**
    * Make a guess, decrease remaining guesses and return a string stating
    * if the guess was correct, too low or to high or if no guesses remains.
    *
    * @throws GuessException when guessed number is out of bounds
    *
    * @param int $guess The guessed number.
    *
    * @return string to show the status of the guess made.
    */
    public function makeGuess(int $guess) : string
    {
        if (($guess < 1) || ($guess > 100)) {
            throw new GuessException("The guess must be an integer between 1 - 100.");
        }
        $this->tries -= 1;

        if ($guess > $this->number) {
            return "TOO HIGH.";
        }
        if ($guess < $this->number) {
            return "TOO LOW.";
        }
        return "CORRECT - Congratulations, you won!";
    }
}
