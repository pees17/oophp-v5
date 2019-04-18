<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));

/**
 * Render the init view to get number of players and number of dices
 */
$app->router->get("dice/init", function () use ($app) {
    $title = "Dice 100 game";

    $app->page->add("dice/init-game");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Get number of players and number of dices from $_POST and then render view
 * to get the players names
 */
$app->router->post("dice/init", function () use ($app) {
    $title = "Dice 100 game";

    // Get post variables
    $nrPlayers = $_POST["nrPlayers"] ?? null;
    $nrDices = $_POST["nrDices"] ?? null;

    // Update session
    $_SESSION["nrPlayers"] = $nrPlayers;
    $_SESSION["nrDices"] = $nrDices;

    // Render view
    $data = [
        "nrPlayers" => $nrPlayers,
    ];
    $app->page->add("dice/name-players", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Get the players names from $_POST, create the game and then redirect to start the game
 */
$app->router->post("dice/get-names", function () use ($app) {
    // Get post variables
    $players = $_POST["players"] ?? null;

    // Create the game, with one dice
    $game = new Peo\Dice\Game($players, 1);

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["res"] = null;

    return $app->response->redirect("dice/start-view");
});

/**
 * Let all players throw a dice to decide who shall start
 * This route shows the status of the start phase
 */
$app->router->get("dice/start-view", function () use ($app) {
    $title = "Dice 100 game";

    // Get game and res from session
    $game = $_SESSION["game"] ?? null;
    $res = $_SESSION["res"] ?? null;

    // Render view
    $data = [
        "players" => $game->getPlayers(),
        "res" => $res
    ];

    $app->page->add("dice/start", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Let all players throw a dice to decide who shall start
 * This route handles one throw and then redirects to view the status.
 */
$app->router->get("dice/start-throw", function () use ($app) {
    // Get game from session
    $game = $_SESSION["game"] ?? null;

    // Throw a dice and update the points with the result
    $game->roll();
    $game->addPoints();

    // Check if all players has thrown a dice
    $res = null;
    if ($game->lastPlayer()) {
        $res = $game->getHighest();
    }

    // Advance current player with one
    $game->advanceCurrent();

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["res"] = $res;

    return $app->response->redirect("dice/start-view");
});

/**
 * Let all players throw a dice to decide who shall start
 * This route handles the case when there is a tie. I.e. there are more than
 * one player with the highest dice. Then everyone trows a new dice,
 */
$app->router->get("dice/start-restart", function () use ($app) {
    // Get game from session
    $game = $_SESSION["game"] ?? null;

    // Re-imitialize the points for all players to null.
    $game->resetPoints();

    // Update session
    $_SESSION["game"] = $game;

    return $app->response->redirect("dice/start-throw");
});

/**
 * The start order has been determined so now the game can start.
 * This route handles the game start and then redirects to view the
 * status of the play.
 */
$app->router->get("dice/start-game", function () use ($app) {

    // Get game from session
    $game = $_SESSION["game"] ?? null;
    $nrDices = $_SESSION["nrDices"] ?? null;

    // Sort the players in start order, initialize the points for all
    // players to null, and reset current to first player
    $game->sortHighest();
    $game->resetPoints();
    $game->resetCurrent();

    // Set number of dices to use in the game
    $game->setNrDices($nrDices);

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["res"] = null;
    $_SESSION["round"] = 1;
    $_SESSION["dices"] = [];

    // View the game
    return $app->response->redirect("dice/play-view");
});

/**
 * Playing the game - Show game status
 */
$app->router->get("dice/play-view", function () use ($app) {
    $title = "Dice 100 game";

    // Get game and data from session
    $game = $_SESSION["game"] ?? null;
    $res = $_SESSION["res"] ?? null;
    $dices = $_SESSION["dices"] ?? null;
    $round = $_SESSION["round"] ?? null;

    // Render view
    $data = [
        "dices" => $dices,
        "players" => $game->getPlayers(),
        "current" => $game->getCurrentPlayer(),
        "sumCurrent" => $game->getSumCurrent(),
        "res" => $res,
        "round" => $round
    ];

    $app->page->add("dice/view-play", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Playing the game - Throwing dices
 */
$app->router->get("dice/play-throw", function () use ($app) {
    // Get game from session
    $game = $_SESSION["game"] ?? null;
    $dices = $_SESSION["dices"];

    // Throw a dice hand, and get the graphic representation
    $game->roll();
    $dices[] = $game->getGraphicHand();

    // Check if a '1' has been thrown
    $res = null;
    if ($game->checkOne()) {
        $res = "Lost";
    }

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["res"] = $res;
    $_SESSION["dices"] = $dices;

    return $app->response->redirect("dice/play-view");
});
