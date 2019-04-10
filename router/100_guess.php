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
    $_SESSION["game"] = new Peo\Guess\Guess();
    $_SESSION["res"] = null;

    return $app->response->redirect("guess/play");
});

/**
 * Show the secret number and redirect back to play
 */
$app->router->get("guess/cheat", function () use ($app) {
    // Set res in the session
    $game = $_SESSION["game"] ?? null;
    $_SESSION["res"] = "CHEAT: Current number is: <b>{$game->number()}</b>";

    return $app->response->redirect("guess/play");
});

/**
 * Play the game - show game status
 */
$app->router->get("guess/play", function () use ($app) {
    $title = "Guess my number";

    // Get variables from the session
    $res = $_SESSION["res"] ?? null;
    $game = $_SESSION["game"] ?? null;

    $data = [
        "tries" => $game->tries(),
        "res" => $res
    ];

    $app->page->add("guess/play", $data);
    // $app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Play the game - make a guess
 */
$app->router->post("guess/play", function () use ($app) {

    // Get post variables
    $guess = $_POST["guess"] ?? null;

    // Get variables from the session
    $res = $_SESSION["res"] ?? null;
    $game = $_SESSION["game"] ?? null;

    if (!$game->gameOver()) {
        try {
            $res = "Your guess $guess is <b>{$game->makeGuess($guess)}</b>";
            if ($game->gameOver()) {
                $res .= ". The game is over, click \"Restart\" to play again.";
            }
        } catch (Peo\Guess\GuessException $e) {
            $res = "<b>{$e->getMessage()}</b>";
        } catch (TypeError $e) {
            $res = "<b>Guess must be an integer</b>";
        }
    }
    // Update session
    $_SESSION["res"] = $res;

    return $app->response->redirect("guess/play");
});
