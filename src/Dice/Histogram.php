<?php

namespace Peo\Dice;

/**
 * Generating histogram data.
 */
class Histogram
{
    /**
     * @var array $serie  The numbers stored in sequence.
     * @var int   $min    The lowest possible number.
     * @var int   $max    The highest possible number.
     */
    private $serie = [];
    private $min;
    private $max;


    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getSerie()
    {
        return $this->serie;
    }


    /**
     * Return a string with a textual representation of the histogram.
     *
     * @return string representing the histogram.
     */
    public function getAsText()
    {
        $nrRolls = array_fill($this->min, $this->max, 0);
        $hist = "";

        foreach ($this->serie as $roll) {
            $nrRolls[$roll] += 1;
        }
        for ($side = $this->min; $side <= $this->max; $side++) {
            $hist .= $side . ": ";
            for ($i = 0; $i < $nrRolls[$side]; $i++) {
                $hist .= "*";
            }
            $hist .= "<br>";
        }
        return $hist;
    }

    /**
     * Inject the object to use as base for the histogram data.
     *
     * @param HistogramInterface $object The object holding the serie.
     *
     * @return void.
     */
    public function injectData(HistogramInterface $object)
    {
        $this->serie = $object->getHistogramSerie();
        $this->min   = $object->getHistogramMin();
        $this->max   = $object->getHistogramMax();
    }
}
