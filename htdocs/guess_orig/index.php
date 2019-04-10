<?php
/**
 * Guess my number, POST version with game as a class.
 */
require __DIR__ . "/autoload.php";
require __DIR__ . "/config.php";

// Get post variables from the session
$guess = $_SESSION["guess"] ?? null;
$doInit = $_SESSION["doInit"] ?? null;
$doGuess = $_SESSION["doGuess"] ?? null;
$doCheat = $_SESSION["doCheat"] ?? null;
$res = $_SESSION["res"] ?? null;

// Init the game
if ($doInit || !isset($_SESSION["game"])) {
    $_SESSION["game"] = new Guess();
    $res = null;
}
$game = $_SESSION["game"];

if (!$game->gameOver()) {
    // Handle user input
    if ($doGuess) {
        $_SESSION["doGuess"] = null;  // To not waste a guess on page reload
        try {
            $res = "<p>Your guess $guess is <b>{$game->makeGuess($guess)}</b></p>";
            if ($game->gameOver()) {
                $res .= "\n<p>The game is over, hit \"Start from beginning\" to play again</p>";
            }
        } catch (GuessException $e) {
            $res = "<p><b>{$e->getMessage()}</b></p>";
        } catch (TypeError $e) {
            $res = "<p><b>Guess must be an integer</b></p>";
        }
    } elseif ($doCheat) {
        $res = "<p>CHEAT: Current number is: <b>{$_SESSION["game"]->number()}</b></p>";
    }
}

// Render the page
$tries = $_SESSION["game"]->tries();
require __DIR__ . "/view/guess_my_number.php";

// Update session
$_SESSION["res"] = $res;                // Keep $res between page reloads
$_SESSION["game"] = $game;              // Maybe not needed?
