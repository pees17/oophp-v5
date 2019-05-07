<?php

namespace Peo\Dice;

use Anax\Response\ResponseUtility;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class DiceControllerTest extends TestCase
{
    private $controller;
    private $app;

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        global $di;

        // Init service container $di to contain $app as a service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);

        // Create and initiate the controller
        $this->controller = new DiceController();
        $this->controller->setApp($app);
        // $this->controller->initialize();
    }


    /**
     * Call the controller index action.
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $this->assertIsString($res);
        $this->assertEquals("Index!!", $res);
    }


    /**
     * Call the controller init action GET.
     */
    public function testInitActionGet()
    {
        $res = $this->controller->initActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller init action POST.
     */
    public function testInitActionPost()
    {
        $res = $this->controller->initActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller startGame action GET.
     */
    public function testStartGameActionGet()
    {
        $this->app->session->set("name", "Nisse Nyman");
        $this->app->session->set("nrDices", 2);

        $res = $this->controller->startGameActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller gameView action GET.
     */
    public function testGameViewActionGet()
    {
        $res = $this->controller->gameViewActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller playFirst action GET.
     */
    public function testPlayFirstActionGet()
    {
        // Test session timeout
        $this->app->session->set("game", null);
        $res = $this->controller->playFirstActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        // Test throw with and without a '1'
        $game = new Game(["Nisse Nyman", "Computer"], 100, 2);
        $this->app->session->set("game", $game);

        $throwOther= false;
        $throwOne= false;
        while (true) {
            $res = $this->controller->playFirstActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);

            if ($this->app->session->get("state") == "Throw") {
                $throwOther = true;
            };
            if ($this->app->session->get("state") == "Lost") {
                $throwOne = true;
            };

            if ($throwOther && $throwOne) {
                break;
            }
        }
    }


    /**
     * Call the controller playNext action GET.
     */
    public function testPlayNextActionGet()
    {
        // Test session timeout
        $this->app->session->set("game", null);
        $res = $this->controller->playNextActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        // Test throw with and without a '1'
        $game = new Game(["Nisse Nyman", "Computer"], 100, 2);
        $this->app->session->set("game", $game);

        $throwOther= false;
        $throwOne= false;
        while (true) {
            $res = $this->controller->playNextActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);

            if ($this->app->session->get("state") == "Throw") {
                $throwOther = true;
            };
            if ($this->app->session->get("state") == "Lost") {
                $throwOne = true;
            };

            if ($throwOther && $throwOne) {
                break;
            }
        }
    }


    /**
     * Call the controller playStop action GET.
     */
    public function testPlayStopActionGet()
    {
        // Test session timeout
        $this->app->session->set("game", null);
        $res = $this->controller->playStopActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        // Test normal case
        $game = new Game(["Nisse Nyman", "Computer"], 100, 2);
        $this->app->session->set("game", $game);

        $res = $this->controller->playStopActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller playComputer action GET.
     */
    public function testPlayComputerActionGet()
    {
        $name = "Nisse Nyman";
        $nrDices = 2;
        $game = new Game([$name, "Computer"], 100, $nrDices);

        $this->app->session->set("name", $name);
        $this->app->session->set("game", $game);
        $this->app->session->set("nrDices", $nrDices);

        // Make 50 throws to be sure to get a '1'
        for ($i = 0; $i < 50; $i++) {
            $res = $this->controller->playComputerActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);
        }
    }


    /**
     * Tests the function AIplayCheck
     */
    public function testAIplayCheck()
    {
        $name = "Nisse Nyman";
        $this->app->session->set("name", $name);

        // Tests when computer in lead
        $nrDices = 1;
        $game = new Game([$name, "Computer"], 100, $nrDices);
        $this->app->session->set("game", $game);
        $this->app->session->set("nrDices", $nrDices);
        $game->setPlayers([
            $name => 0,
            "Computer" => 90
        ]);

        $this->assertTrue($this->controller->AIplayCheck(1));
        $this->assertTrue($this->controller->AIplayCheck(2));
        $this->assertFalse($this->controller->AIplayCheck(3));

        $nrDices = 2;
        $game = new Game([$name, "Computer"], 100, $nrDices);
        $this->app->session->set("game", $game);
        $this->app->session->set("nrDices", $nrDices);
        $game->setPlayers([
            $name => 0,
            "Computer" => 90
        ]);

        $this->assertTrue($this->controller->AIplayCheck(0));
        $this->assertFalse($this->controller->AIplayCheck(1));

        $game->setPlayers([
            $name => 0,
            "Computer" => 100
        ]);
        $this->assertFalse($this->controller->AIplayCheck(0));
        $this->assertFalse($this->controller->AIplayCheck(1));

        // Tests when player in lead
        $nrDices = 1;
        $game = new Game([$name, "Computer"], 100, $nrDices);
        $this->app->session->set("game", $game);
        $this->app->session->set("nrDices", $nrDices);
        $game->setPlayers([
            $name => 90,
            "Computer" => 0
        ]);

        $this->assertTrue($this->controller->AIplayCheck(0));
        $this->assertTrue($this->controller->AIplayCheck(1));
        $this->assertTrue($this->controller->AIplayCheck(2));
        $this->assertTrue($this->controller->AIplayCheck(3));
        $this->assertFalse($this->controller->AIplayCheck(4));

        for ($nrDices = 2; $nrDices <= 3; $nrDices++) {
            $game = new Game([$name, "Computer"], 100, $nrDices);
            $this->app->session->set("game", $game);
            $this->app->session->set("nrDices", $nrDices);
            $game->setPlayers([
                $name => 90,
                "Computer" => 0
            ]);

            $this->assertTrue($this->controller->AIplayCheck(0));
            $this->assertTrue($this->controller->AIplayCheck(1));
            $this->assertFalse($this->controller->AIplayCheck(2));
        }

        $nrDices = 4;
        $game = new Game([$name, "Computer"], 100, $nrDices);
        $this->app->session->set("game", $game);
        $this->app->session->set("nrDices", $nrDices);
        $game->setPlayers([
            $name => 90,
            "Computer" => 0
        ]);

        $this->assertTrue($this->controller->AIplayCheck(0));
        $this->assertFalse($this->controller->AIplayCheck(1));

        $game->setPlayers([
            $name => 100,
            "Computer" => 0
        ]);
        $this->assertTrue($this->controller->AIplayCheck(0));
        $this->assertTrue($this->controller->AIplayCheck(1));
    }
}
