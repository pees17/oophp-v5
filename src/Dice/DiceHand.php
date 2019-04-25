<?php
namespace Peo\Dice;

/**
 * A class representing a hand of dices.
 */
class DiceHand
{
    /**
     * @var Dice $dices   Array consisting of dices.
     * @var int  $values  Array consisting of last roll of the dices.
     */
    private $dices;
    private $values;

    /**
    * Constructor to initiate the dicehand with a number of dices.
    *
    * @param int $dices Number of dices to create, defaults to five.
    */
    public function __construct(int $nrDices = 5)
    {
        $this->dices  = [];
        $this->values = [];
        for ($i = 0; $i < $nrDices; $i++) {
            $this->dices[] = new Dice();
        }
    }

    /**
     * Roll all dices and save their values
     *
     * @return void
     */
    public function roll() : void
    {
        foreach ($this->dices as $dice) {
            $this->values[] = $dice->roll();
        }
    }

    /**
     * Get values of dices from last roll.
     *
     * @return array with values of the last roll.
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Get the sum of all dices in the hand
     *
     * @return int  the sum
     */
    public function getSum() : int
    {
        return array_sum($this->values);
    }
}
