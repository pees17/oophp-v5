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
 * Get number of players and number of dices from  and then render view
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
    $game->resetSumCurrent();

    // Check if all players has thrown a dice
    $res = null;
    if ($game->lastPlayer()) {
        // Get the player with the highest score
        $res = $game->getHighest() ?? "Tie";
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
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
    }
    // Get variables from session
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
    $_SESSION["winner"] = null;
    $_SESSION["state"] = "Throw";
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
    $winner = $_SESSION["winner"] ?? null;
    $state = $_SESSION["state"] ?? null;
    $dices = $_SESSION["dices"] ?? null;
    $round = $_SESSION["round"] ?? null;

    // Render view
    $data = [
        "dices" => $dices,
        "players" => $game->getPlayers(),
        "current" => $game->getCurrentPlayer(),
        "sumCurrent" => $game->getSumCurrent(),
        "winner" => $winner,
        "state" => $state,
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
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
    }
    // If player is 'Computer' redirect to special Computer route
    if ($game->getCurrentPlayer() === "Computer") {
        return $app->response->redirect("dice/play-computer");
    }

    // Get variables from session
    $dices = $_SESSION["dices"] ?? null;
    $state = $_SESSION["state"] ?? null;
    $winner = $_SESSION["winner"] ?? null;

    // Throw a dice hand, and get the graphic representation
    $game->roll();
    $dices[] = $game->getGraphicHand();

    // Check if a '1' has been thrown
    if ($game->checkOne()) {
        $state = "Lost";
        // If last player in round, check for winner
        if ($game->lastPlayer()) {
            $winner = $game->checkWinner();
        }
    }

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["winner"] = $winner;
    $_SESSION["state"] = $state;
    $_SESSION["dices"] = $dices;

    return $app->response->redirect("dice/play-view");
});

/**
 * Playing the game - Stop throwing dices
 */
$app->router->get("dice/play-stop", function () use ($app) {
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
    }
    // Update the points with the result
    $game->addPoints();

    // If last player in round, check for winner
    $winner = null;
    if ($game->lastPlayer()) {
        $winner = $game->checkWinner();
    }

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["winner"] = $winner;
    $_SESSION["state"] = "Ready";

    if ($winner) {
        return $app->response->redirect("dice/play-view");
    }
    return $app->response->redirect("dice/play-next");
});

/**
 * Playing the game - Special route for computer throwing
 */
$app->router->get("dice/play-computer", function () use ($app) {
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
    }

    $dices = [];
    $state = "Ready";

    $nrThrows = 2;

    while ($nrThrows > 0) {
        // Throw a dice hand, and get the graphic representation
        $game->roll();
        $dices[] = $game->getGraphicHand();

        // Check if a '1' has been thrown
        if ($game->checkOne()) {
            $state = "Lost";

            break;  // Break out of while loop
        }
        $nrThrows -= 1;
    }
    // Update the points with the result
    $game->addPoints();

    // If last player in round, check for winner
    $winner = $_SESSION["winner"] ?? null;
    if ($game->lastPlayer()) {
        $winner = $game->checkWinner();
    }

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["winner"] = $winner;
    $_SESSION["state"] = $state;
    $_SESSION["dices"] = $dices;


    return $app->response->redirect("dice/play-view");
});


/**
 * Playing the game - Next player
 */
$app->router->get("dice/play-next", function () use ($app) {
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
    }

    // If last player in round, update round
    if ($game->lastPlayer()) {
        $_SESSION["round"] += 1;
    }
    // Advance current player with one
    $game->advanceCurrent();

    // Set sumCurrent to 0
    $game->resetSumCurrent();

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["state"] = "Throw";
    $_SESSION["dices"] = [];

    return $app->response->redirect("dice/play-throw");
});

/**
 * Playing the game - New game
 */
$app->router->get("dice/play-again", function () use ($app) {
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
    }

    // Initialize the points for all players to null,
    // and reset current to first player
    $game->resetPoints();
    $game->resetCurrent();
    $game->resetSumCurrent();

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["winner"] = null;
    $_SESSION["state"] = "Throw";
    $_SESSION["round"] = 1;
    $_SESSION["dices"] = [];

    // View the game
    return $app->response->redirect("dice/play-view");
});
