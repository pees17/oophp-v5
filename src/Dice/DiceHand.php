<?php
namespace Peo\Dice;

/**
 * A class representing a hand of dices.
 */
class DiceHand
{
    /**
     * @var Dice $dices    Array consisting of dices.
     * @var int  $lastRoll Array consisting of last roll of the dices.
     * @var int  $sides    Number of sides on the dices.
     */
    private $dices;
    private $lastRoll;
    protected $sides;

    /**
    * Constructor to initiate the dicehand with a number of dices.
    *
    * @param int $nrDices Number of dices to create, defaults to two.
    * @param int $sides   Number of sides on the dices, defaults to 6.
    */
    public function __construct(int $nrDices = 2, int $sides = 6)
    {
        $this->dices  = [];
        $this->lastRoll = [];
        $this->sides = $sides;
        for ($i = 0; $i < $nrDices; $i++) {
            $this->dices[] = new Dice($sides);
        }
    }

    /**
     * Roll all dices and save their values
     *
     * @return array with the values of the rolled dices
     */
    public function roll() : array
    {
        $this->lastRoll = [];
        foreach ($this->dices as $dice) {
            $this->lastRoll[] = $dice->roll();
        }
        return $this->getLastRoll();
    }

    /**
     * Get values of dices from last roll.
     *
     * @return array with values of the last roll.
     */
    public function getLastRoll() : array
    {
        return $this->lastRoll;
    }

    /**
     * Get the sum of all dices in the hand
     *
     * @return int  the sum
     */
    public function getSum() : int
    {
        return array_sum($this->lastRoll);
    }
}
