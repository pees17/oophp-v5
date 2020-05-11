<?php
namespace Anax\View;

/**
 * Render the guess game when the game is over
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Guess my number</h1>

<p>Guess a number between 1 and 100, you have <?= $tries ?> guesses left.</p>

<div class="game-container">
<form class="game" method="post">
    <input type="text" name="guess">
    <input type="submit" name="doGuess" value="Make a guess">
</form>

<div class="game">
    <a class="button" title="Start from beginning" href="init">Restart</a>
    <a class="button" title="View the secret number" href="gameover">Cheat</a>
</div>
</div>

<p class="game"><?= $res ?></p>
