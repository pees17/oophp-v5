<?php
namespace Anax\View;

/**
 * Render the guess game
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Guess my number</h1>

<p>Guess a number between 1 and 100, you have <?= $tries ?> guesses left.</p>

<div class="guess-container">
<form class="guess-game" method="post">
    <input type="text" name="guess">
    <input type="submit" name="doGuess" value="Make a guess">
</form>

<div class="guess-game">
    <a class="button" title="Start from beginning" href="init">Restart</a>
    <a class="button" title="View the secret number" href="cheat">Cheat</a>
</div>
</div>

<p class="guess-game"><?= $res ?></p>
