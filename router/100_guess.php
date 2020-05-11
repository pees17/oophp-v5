<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));

/**
 * Init the game and redirect to play the game
 */
$app->router->get("guess/init", function () use ($app) {
    // Init the session for the gamestart
    $game= new Peo\Guess\Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();
    $_SESSION["res"] = null;

    return $app->response->redirect("guess/play");
});


/**
* Re-init the game after a session time-out and redirect to play the game
 */
$app->router->get("guess/timeout", function () use ($app) {
    // Init the session for the gamestart
    $game= new Peo\Guess\Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();
    $_SESSION["res"] = "<b>Time-out, game has restarted!</b>";

    return $app->response->redirect("guess/play");
});


/**
 * Play the game - show game status
 */
$app->router->get("guess/play", function () use ($app) {

    $title = "Guess my number";

    // Get variables from the session
    $res = $_SESSION["res"] ?? null;
    $tries = $_SESSION["tries"] ?? null;

    $data = [
        "tries" => $tries,
        "res" => $res
    ];

    $app->page->add("guess/play", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
 * Game is over - show game status
 */
$app->router->get("guess/gameover", function () use ($app) {
    $title = "Guess my number";

    // If session has timed out, restart game
    $number = $_SESSION["number"] ?? null;
    if (!isset($number)) {
        return $app->response->redirect("guess/timeout");
    }

    // Get variables from the session
    $res = $_SESSION["res"] ?? null;
    $tries = $_SESSION["tries"] ?? null;

    $data = [
        "tries" => $tries,
        "res" => $res
    ];

    $app->page->add("guess/gameover", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Post route for game over. Do nothing.
 */
$app->router->post("guess/gameover", function () use ($app) {
    return $app->response->redirect("guess/gameover");
});

/**
 * Play the game - make a guess
 */
$app->router->post("guess/play", function () use ($app) {

    // Get post variables
    $guess = $_POST["guess"] ?? null;

    // Get variables from the session
    $number = $_SESSION["number"] ?? null;
    $tries = $_SESSION["tries"] ?? null;
    $res = $_SESSION["res"] ?? null;

    // If session has timed out, restart game
    if (!isset($number)) {
        return $app->response->redirect("guess/timeout");
    }

    // Handle guess
    $game = new Peo\Guess\Guess($number, $tries);
    try {
        $res = "Your guess $guess is <b>{$game->makeGuess($guess)}</b>";
        $tries = $game->tries();
        if ($tries == 0) {
            $gameOver = true;
        }
    } catch (Peo\Guess\GuessException $e) {
        $res = "<b>{$e->getMessage()}</b>";
    } catch (TypeError $e) {
        $res = "<b>Guess must be an integer</b>";
    }
    $gameOver = $gameOver || ($guess == $number);

    // Update session
    $_SESSION["res"] = $res;
    $_SESSION["tries"] = $tries;

    if ($gameOver) {
        $_SESSION["res"] .= "<br><br>The game is over, click \"Restart\" to play again.";
        return $app->response->redirect("guess/gameover");
    }
    return $app->response->redirect("guess/play");
});

/**
 * Show the secret number and redirect back to play
 */
$app->router->get("guess/cheat", function () use ($app) {
    // If session has timed out, restart game
    $number = $_SESSION["number"] ?? null;
    if (!isset($number)) {
        return $app->response->redirect("guess/timeout");
    }

    $_SESSION["res"] = "CHEAT: Current number is: <b>{$number}</b>";

    return $app->response->redirect("guess/play");
});
