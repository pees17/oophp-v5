<?php
namespace Peo\Dice;
use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;
// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;
/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Dice100Controller implements AppInjectableInterface
{
    use AppInjectableTrait;
    /**
     * @var string $db a sample member variable that gets initialised
     */
    // private $db = "not active";

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    // public function initialize() : void
    // {
    //     // Use to initialise member variables.
    //     $this->db = "active";
    //     // Use $this->app to access the framework services.
    // }


    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexAction() : string
    {
        // Deal with the action and return a response.
        return "Index!!";
    }


    /**
     * This is the init method action, it handles:
     * GET mountpoint/init
     * It will render the init view to name to get the name of the player
     * and get the number of dices
     *
     * @return object rendering the init view
     */
    public function initActionGet() : object
    {
        $title = "Dice 100 game";

        $this->app->page->add("dice100/game-init");

        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the init method action, it handles:
     * POST mountpoint/init
     * It will get the name of the player and the number of dices
     * and then redirect to start the game
     *
     * @return object redirect to the start of the game
     */
    public function initActionPost() : object
    {
        // Get post variables
        $name = $this->app->request->getPost("name");
        $nrDices = $this->app->request->getPost("nrDices");

        // Simple AI
        if ($nrDices == 1) {
            $computerThrows = 3;
        } elseif ($nrDices == 2) {
            $computerThrows = 2;
        } else {
            $computerThrows = 1;
        }

        // Update session
        $this->app->session->set("name", $name);
        $this->app->session->set("nrDices", $nrDices);
        $this->app->session->set("computerThrows", $computerThrows);

        return $this->app->response->redirect("dice100/startGame");
    }


    /**
     * This is the startGame method action, it handles:
     * GET mountpoint/startGame
     * It creates the game and then redirects to view the status of the game.
     *
     * @return object redirect to view the status of the game
     */
    public function startGameActionGet() : object
    {
        // Get data from session
        $name = $this->app->session->get("name");
        $nrDices = $this->app->session->get("nrDices");

        // Create the game
        $game = new Game([$name, "Computer"], $nrDices);

        // Update session
        $this->app->session->set("game", $game);
        $this->app->session->set("current", $name);
        $this->app->session->set("winner", null);
        $this->app->session->set("state", "Trow");
        $this->app->session->set("round", 1);
        $this->app->session->set("dices", []);

        // View the game
        return $this->app->response->redirect("dice100/gameView");
    }


    /**
     * This is the gameView method action, it handles:
     * GET mountpoint/gameView
     * It will render the game view to show the game status
     *
     * @return object rendering the game view
     */
    public function gameViewActionGet() : object
    {
        $title = "Dice 100 game";

        // Get game and data from session
        $game = $_SESSION["game"] ?? null;
        $winner = $_SESSION["winner"] ?? null;
        $state = $_SESSION["state"] ?? null;
        $dices = $_SESSION["dices"] ?? null;
        $round = $_SESSION["round"] ?? null;
        $current = $_SESSION["current"] ?? null;

        // Render view
        $data = [
            "dices" => $dices,
            "players" => $game->getPlayers(),
            "current" => $current,
            "sumCurrent" => $game->getSumCurrent(),
            "winner" => $winner,
            "state" => $state,
            "round" => $round
        ];

        $this->app->page->add("dice100/game-view", $data);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This is the playFirst method action, it handles:
     * GET mountpoint/playFirst
     * Playing the game - Throwing dices, first hand
     *
     * @return object redirect to view the status of the game
     */
    public function playFirstActionGet() : object
    {
        // Get game from session. If session has timed out, restart game
        if (!($game = $_SESSION["game"] ?? null)) {
            return $app->response->redirect("dice100/init");
        }

        $state = "Throw";
        $dices = [];

        // Set sumCurrent to 0
        $game->resetSumCurrent();

        // Get variables from session
        $name = $_SESSION["name"] ?? null;

        // Throw a dice hand, and get the graphic representation
        $game->roll();
        $dices[] = $game->getGraphicHand();

        // Check if a '1' has been thrown
        if ($game->checkOne()) {
            $state = "Lost";
        }

        // Update session
        $_SESSION["game"] = $game;
        $_SESSION["state"] = $state;
        $_SESSION["dices"] = $dices;
        $_SESSION["current"] = $name;
        $_SESSION["round"] += 1;

        return $this->app->response->redirect("dice100/gameView");
    }


    /**
     * This is the playNext method action, it handles:
     * GET mountpoint/playNext
     * Playing the game - Throwing dices, next hand
     *
     * @return object redirect to view the status of the game
     */
    public function playNextActionGet() : object
    {
        // Get game from session. If session has timed out, restart game
        if (!($game = $_SESSION["game"] ?? null)) {
            return $app->response->redirect("dice100/init");
        }

        // Get variables from session
        $dices = $_SESSION["dices"] ?? null;
        $name = $_SESSION["name"] ?? null;

        // Throw a dice hand, and get the graphic representation
        $game->roll();
        $dices[] = $game->getGraphicHand();

        // Check if a '1' has been thrown
        $state = "Throw";
        if ($game->checkOne()) {
            $state = "Lost";
        }

        // Update session
        $_SESSION["game"] = $game;
        $_SESSION["state"] = $state;
        $_SESSION["dices"] = $dices;
        $_SESSION["current"] = $name;

        return $this->app->response->redirect("dice100/gameView");
    }


    /**
     * This is the playStop method action, it handles:
     * GET mountpoint/playStop
     * Playing the game - Stop throwing dices
     *
     * @return object redirect to next player (computer)
     */
    public function playStopActionGet() : object
    {
        // Get game from session. If session has timed out, restart game
        if (!($game = $_SESSION["game"] ?? null)) {
            return $app->response->redirect("dice100/init");
        }

        // Update the points with the result
        $name = $_SESSION["name"] ?? null;
        $game->addPoints($name);

        // Set sumCurrent to 0
        $game->resetSumCurrent();

        // Update session
        $_SESSION["game"] = $game;

        return $this->app->response->redirect("dice100/playComputer");
    }


    /**
     * This is the playComputer method action, it handles:
     * GET mountpoint/playComputer
     * Playing the game - Computer throwing
     *
     * @return object redirect to view the status of the game
     */
    public function playComputerActionGet() : object
    {
        // Get from session.
        $game = $_SESSION["game"] ?? null;
        $computerThrows = $_SESSION["computerThrows"] ?? null;

        $dices = [];
        $state = "Ready";

        while ($computerThrows > 0) {
            // Throw a dice hand, and get the graphic representation
            $game->roll();
            $dices[] = $game->getGraphicHand();

            // Check if a '1' has been thrown
            if ($game->checkOne()) {
                $state = "Lost";

                break;  // Break out of while loop
            }
            $computerThrows -= 1;
        }
        // Update the points with the result
        $game->addPoints("Computer");

        // Check for winner
        $winner = $game->checkWinner();

        // Update session
        $_SESSION["game"] = $game;
        $_SESSION["winner"] = $winner;
        $_SESSION["state"] = $state;
        $_SESSION["dices"] = $dices;
        $_SESSION["current"] = "Computer";

        return $this->app->response->redirect("dice100/gameView");
    }
}
