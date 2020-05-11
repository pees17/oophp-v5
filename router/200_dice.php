<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));


/**
 * Render the init view to name and number of dices
 */
$app->router->get("dice/init", function () use ($app) {
    $title = "Dice 100 game";

    $app->page->add("dice/init-game");

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
 * Get name and number of dices and then render view to start the game.
 */
$app->router->post("dice/init", function () use ($app) {
    $title = "Dice 100 game";

    // Get post variables
    $name = $_POST["name"] ?? null;
    $nrDices = $_POST["nrDices"] ?? null;

    // Simple AI
    if ($nrDices == 1) {
        $computerThrows = 3;
    } elseif ($nrDices == 2) {
        $computerThrows = 2;
    } else {
        $computerThrows = 1;
    }

    // Update session
    $_SESSION["name"] = $name;
    $_SESSION["nrDices"] = $nrDices;
    $_SESSION["computerThrows"] = $computerThrows;

    return $app->response->redirect("dice/start-game");
});


/**
 * Create the gane and then redirects to view the status of the game.
 */
$app->router->get("dice/start-game", function () use ($app) {
    // Get data from session
    $name = $_SESSION["name"] ?? null;
    $nrDices = $_SESSION["nrDices"] ?? null;

    // Create the game
    $game = new Peo\Dice\Game([$name, "Computer"], 100, $nrDices);

    // Update session
    $_SESSION["game"] = $game;
    $_SESSION["current"] = $name;
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

    $app->page->add("dice/view-play", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Playing the game - Throwing dices, first hand
 */
$app->router->get("dice/play-first", function () use ($app) {
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
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

    return $app->response->redirect("dice/play-view");
});

/**
 * Playing the game - Throwing dices, next dice
 */
$app->router->get("dice/play-next", function () use ($app) {
    // Get game from session. If session has timed out, restart game
    if (!($game = $_SESSION["game"] ?? null)) {
        return $app->response->redirect("dice/init");
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
    $name = $_SESSION["name"] ?? null;
    $game->addPoints($name);

    // Set sumCurrent to 0
    $game->resetSumCurrent();

    // Update session
    $_SESSION["game"] = $game;

    return $app->response->redirect("dice/play-computer");
});

/**
 * Playing the game - Special route for computer throwing
 */
$app->router->get("dice/play-computer", function () use ($app) {
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

    return $app->response->redirect("dice/play-view");
});
