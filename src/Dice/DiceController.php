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
class DiceController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * @var int WIN_LEVEL The nunber of points to win the game
     */
    const WIN_LEVEL = 100;

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

        // Update session
        $this->app->session->set("name", $name);
        $this->app->session->set("nrDices", $nrDices);

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
        $game = new Game([$name, "Computer"], self::WIN_LEVEL, $nrDices);

        // Update session
        $this->app->session->set("game", $game);
        $this->app->session->set("current", $name);
        $this->app->session->set("winner", null);
        $this->app->session->set("state", "Throw");
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
        $game = $this->app->session->get("game");
        $winner = $this->app->session->get("winner");
        $state = $this->app->session->get("state");
        $dices = $this->app->session->get("dices");
        $round = $this->app->session->get("round");
        $current = $this->app->session->get("current");

        // Generate histogram
        $histogram = new Histogram();
        $diceHand = $game->getDiceHand();
        $histogram->injectData($diceHand);

        // Render view
        $data = [
            "dices" => $dices,
            "players" => $game->getPlayers(),
            "current" => $current,
            "sumCurrent" => $game->getSumCurrent(),
            "winner" => $winner,
            "state" => $state,
            "round" => $round,
            "histogram" => $histogram->getAsText()
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
        if (!($game = $this->app->session->get("game"))) {
            return $this->app->response->redirect("dice100/init");
        }

        $state = "Throw";
        $dices = [];

        // Set sumCurrent to 0
        $game->resetSumCurrent();

        // Get variables from session
        $name = $this->app->session->get("name");
        $round = $this->app->session->get("round");

        // Throw a dice hand, and get the graphic representation
        $game->roll();
        $dices[] = $game->getGraphicHand();

        // Check if a '1' has been thrown
        if ($game->checkOne()) {
            $state = "Lost";
        }

        // Update session
        $this->app->session->set("game", $game);
        $this->app->session->set("current", $name);
        $this->app->session->set("state", $state);
        $this->app->session->set("dices", $dices);
        $this->app->session->set("round", $round + 1);

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
        if (!($game = $this->app->session->get("game"))) {
            return $this->app->response->redirect("dice100/init");
        }

        // Get variables from session
        $dices = $this->app->session->get("dices");
        $name = $this->app->session->get("name");

        // Throw a dice hand, and get the graphic representation
        $game->roll();
        $dices[] = $game->getGraphicHand();

        // Check if a '1' has been thrown
        $state = "Throw";
        if ($game->checkOne()) {
            $state = "Lost";
        }

        // Update session
        $this->app->session->set("game", $game);
        $this->app->session->set("current", $name);
        $this->app->session->set("state", $state);
        $this->app->session->set("dices", $dices);

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
        if (!($game = $this->app->session->get("game"))) {
            return $this->app->response->redirect("dice100/init");
        }

        // Update the points with the result
        $name = $this->app->session->get("name");
        $game->addPoints($name);

        // Set sumCurrent to 0
        $game->resetSumCurrent();

        // Update session
        $this->app->session->set("game", $game);

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
        $game = $this->app->session->get("game");

        $dices = [];
        $state = "Ready";

        $nrThrown = 0;
        while ($this->aiPlayCheck($nrThrown)) {
            // Throw a dice hand, and get the graphic representation
            $game->roll();
            $dices[] = $game->getGraphicHand();

            // Check if a '1' has been thrown
            if ($game->checkOne()) {
                $state = "Lost";

                break;  // Break out of while loop
            }
            $nrThrown += 1;
        }
        // Update the points with the result
        $game->addPoints("Computer");

        // Check for winner
        $winner = $game->checkWinner();

        // Update session
        $this->app->session->set("game", $game);
        $this->app->session->set("current", "Computer");
        $this->app->session->set("state", $state);
        $this->app->session->set("dices", $dices);
        $this->app->session->set("winner", $winner);

        return $this->app->response->redirect("dice100/gameView");
    }

    /**
     * This function implements tha AI that decides if the computer shall
     * throw one more hand. It is based on the points of both players and the
     * number of dices used.
     *
     * @param int $nrThrown The number of throws already done
     *
     * @return bool true if computer shall throw one more
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function aiPlayCheck(int $nrThrown) : bool
    {
        // Get from session.
        $game = $this->app->session->get("game");
        $nrDices = $this->app->session->get("nrDices");
        $name = $this->app->session->get("name");

        // Points for computer and player
        $players = $game->getPlayers();
        $computerPoints = $players["Computer"] + $game->getSumCurrent();
        $playerPoints = $players[$name];

        if ($computerPoints > $playerPoints) {
            // Computer is leading
            if ($computerPoints >= self::WIN_LEVEL) {
                return false;   // Computer has won already
            }
            if ($nrDices == 1) {
                if ($nrThrown >= 3) {
                    return false;
                }
            } elseif ($nrThrown >= 1) {
                return false;
            }
            return true;
        } else {
            // Player is leading
            if ($playerPoints >= self::WIN_LEVEL) {
                return true;   // Player has 100 already, Throw again.
            }
            if ($nrDices == 1) {
                if ($nrThrown >= 4) {
                    return false;
                }
            } elseif ($nrDices == 2 || $nrDices == 3) {
                if ($nrThrown >= 2) {
                    return false;
                }
            } elseif ($nrThrown >= 1) {
                return false;
            }
        }
        return true;
    }
}
