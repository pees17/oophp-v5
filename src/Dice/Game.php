<?php
namespace Peo\Dice;

/**
 * A class representing a dice 100 game.
 */
class Game
{
    /**
    * @var object $players  Array with the names and points of the players
    * @var int $nrDices     The number of dices to use in a roll
    * @var int $sumCurrent  The sum of the rolls in current round for current player
    * @var int $lastHand    Array with the values from the last roll
    * @var int WIN          The nunber of points to win the game
     */
    private $players;
    private $nrDices;
    private $sumCurrent = 0;
    private $lastHand = [];

    const WIN = 100;

    /**
     * Constructor to create a Game with a number of players
     *
     * @param string $players  Array with the names of the players.
     */
    public function __construct(array $players, int $nrDices = 1)
    {
        foreach ($players as $player) {
            $this->players[$player] = null;    // Add players with no points
        }
        $this->nrDices = $nrDices;
    }

    /**
     * Get the number of dices. Only needed for module test
     *
     * @return int The number of dices
     */
    public function getNrDices() : int
    {
        return $this->nrDices;
    }


    /**
     * Roll a hand of dices and adds the result to $sumCurrent
     *
     * @return void
     */
    public function roll() : void
    {
        $diceHand = new DiceHand($this->nrDices);
        $diceHand->roll();
        $this->sumCurrent += $diceHand->getSum();
        $this->lastHand = $diceHand->getValues();
    }


    /**
     * Gets the graphic representation of the last hand of dices
     *
     * @return string Array with the graphic representation of the dices
     */
    public function getGraphicHand() : array
    {
        $graphHand = [];
        foreach ($this->lastHand as $dice) {
            $graphHand[] = ("dice-" . $dice);
        }
        return $graphHand;
    }


    /**
     * Checks if the last roll of dices had a '1'.
     * If there is a '1' the $sumCurrent is cleared
     *
     * @return bool True if the last hand had a '1'.
     */
    public function checkOne() : bool
    {
        foreach ($this->lastHand as $dice) {
            if ($dice == 1) {
                $this->sumCurrent = 0;
                return true;
            }
        }
        return false;
    }


    /**
     * Takes the points in $sumCurrent and adds to the player.
     *
     * @param string $name  The player to adfd points to
     *
     * @return void.
     */
    public function addPoints(string $name) : void
    {
        $this->players[$name] += $this->sumCurrent;
    }


    /**
     * Get the sum of the current round
     *
     * @return int The sum
     */
    public function getSumCurrent() : int
    {
        return $this->sumCurrent;
    }

    /**
     * Sets sumCurrent to 0.
     *
     * @return void
     */
    public function resetSumCurrent() : void
    {
        $this->sumCurrent = 0;
    }

    /**
     * Get all players, names and points
     *
     * @return object Array with the names and points of the players
     */
    public function getPlayers() : array
    {
        return $this->players;
    }


    /**
     * Returns a string with the name of the winner, if any
     *
     * @return string The name if there is a winner, otherwise null.
     */
    public function checkWinner()
    {
        if ($winner = $this->getHighest()) {
            if ($this->players[$winner] >= self::WIN) {
                return $winner;
            }
        }
        return null;
    }


    /**
     * Returns the name of the player with the highest score
     *
     * @return string name, or null if no single player with highest score
     */
    private function getHighest()
    {
        $max = 0;
        $nextMax = 0;
        foreach ($this->players as $player => $points) {
            if ($points >= $max) {
                $nextMax = $max;
                $max = $points;
                $maxPlayer = $player;
            }
        }
        if ($max > $nextMax) {
            return $maxPlayer;
        }
        return null;
    }
}
