<?php
namespace Peo\Dice;

/**
 * A class representing a dice 100 game.
 */
class Game
{
    /**
    * @var object $players  Array with the names and points of the players
    * @var string $current  The name of the current player
    * @var int    WIN       The nunber of points to win the game
     */
    private $players;
    private $current;
    const WIN = 100;

    /**
     * Constructor to create a Game with a number of players
     *
     * @param string $players  Array with the names of the players.
     */
    public function __construct(array $players)
    {
        foreach ($players as $player) {
            $this->players[$player] = null;    // Add player with no points
        }
        $this->current = $players[0];
    }

    /**
     * Add points to the current player
     *
     * @param int $points  The points to add
     *
     * @return void.
     */
    public function addPoints(int $points) : void
    {
        $this->players[$this->current] += $points;
    }

    /**
     * Advances curent player to next player
     *
     * @return void.
     */
    public function advanceCurrent() : void
    {
        $this->current = $this->getNextPlayer($this->players, $this->current);
    }

    /**
     * Get the points for the current player
     *
     * @return int The points for the current player
     */
    public function getPoints() : int
    {
        return $this->players[$this->current];
    }

    /**
     * Get the names of all players
     *
     * @return string Array with the names of the players
     */
    public function getNames() : array
    {
        return array_keys($this->players);
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
     * Get the name of the current player
     *
     * @return string Name of the current player
     */
    public function getCurrentName() : string
    {
        return $this->current;
    }

    /**
     * Check if the current player is the last player
     *
     * @return bool True if current is last
     */
    public function lastPlayer() : bool
    {
        $keys = array_keys($this->players);
        $last = end($keys);
        if ($last == $this->current) {
            return true;
        }
        return false;
    }

    /**
     * Returns a string telling if there is a highest score
     *
     * @return string The result
     */
    public function getHighest() : string
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
            return "$maxPlayer has the highest dice and will play first!";
        }
        return "Tie";
    }

    /**
     * Sort $players in descending order by value
     *
     * @return void
     */
    public function sortHighest() : void
    {
        arsort($this->players);
    }

    /**
     * Resets the points for all players to null
     *
     * @return void
     */
    public function resetPoints() : void
    {
        $this->players = array_fill_keys(array_keys($this->players), null);
    }

    /**
     * Resets the current player to the first player
     *
     * @return void
     */
    public function resetCurrent() : void
    {
        $this->current = array_keys($this->players)[0];
    }

    /**
     * Update the name of the current player with the next player
     *
     * @var object $players  Array with the names and points of the players
     * @var string $current  The name of the current player
     *
     * @return string The name of the next player
     */

    private function getNextPlayer(array $players, string $current) : string
    {
        $keys = array_keys($players);
        $position = array_search($current, $keys);
        if (!isset($keys[$position + 1])) {
            return $keys[0];
        }
        return $keys[$position + 1];
    }
}
