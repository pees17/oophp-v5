<?php

namespace Peo\Dice;

/**
 * A dice which has the ability to present data to be used for creating
 * a histogram.
 */
class DiceHandHistogram extends DiceHand implements HistogramInterface
{
    use HistogramTrait;


    /**
     * Get max value for the histogram.
     *
     * @return int with the max value.
     */
    public function getHistogramMax()
    {
        return $this->sides;
    }


    /**
     * Roll the dices, remember their values in the serie and return
     * their values.
     *
     * @return array with the values of the rolled dices
     */
    public function roll() : array
    {
        $currentRoll = parent::roll();
        foreach ($currentRoll as $dice) {
            $this->serie[] = $dice;
        }
        return parent::getLastRoll();
    }
}
