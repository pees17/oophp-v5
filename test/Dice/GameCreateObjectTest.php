<?php

namespace Peo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameCreateObjectTest extends TestCase
{
    /**
     * Construct a game object and verify that the object has the expected
     * properties. Use only first arguments.
     */
    public function testCreateObjectFirstArguments()
    {
        $game = new Game(["Computer"]);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        $res = $game->getNrDices();
        $exp = 1;
        $this->assertEquals($exp, $res);
    }


    /**
     * Construct object and verify that the object has the expected
     * properties. Use both arguments.
     */
    public function testCreateObjectBothArguments()
    {
        $game = new Game(["Computer"], 5);
        $this->assertInstanceOf("\Peo\Dice\Game", $game);

        $res = $game->getNrDices();
        $exp = 5;
        $this->assertEquals($exp, $res);
    }
}
