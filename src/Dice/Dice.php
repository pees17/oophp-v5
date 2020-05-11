<?php
namespace Peo\Dice;

/**
 * A class representing a dice.
 */
class Dice
{
    /**
    * @var int $sides Number of sides on the dice.
    * @var int $lastRoll The result of the last roll
     */
    private $sides;
    private $lastRoll;

    /**
     * Constructor to create a Dice with a number of sides
     *
     * @param int $sides Number of sides on the dice.
     */
    public function __construct(int $sides = 6)
    {
        $this->sides = $sides;
    }

    /**
     * Roll the dice
     *
     * @return int the result of the roll.
     */
    public function roll() : int
    {
        $this->lastRoll = rand(1, $this->sides);
        return $this->lastRoll;
    }

    /**
     * Get the result of the last roll
     *
     * @return int the result of the roll.
     */
    public function getLastRoll() : int
    {
        return $this->lastRoll;
    }
}
