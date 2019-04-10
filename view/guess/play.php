<?php
namespace Anax\View;

/**
 * Render the guess game
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Guess my number</h1>

<p>Guess a number between 1 and 100, you have <?= $tries ?> guesses left.</p>

<form method="post">
    <input type="text" name="guess">
    <input type="submit" name="doGuess" value="Make a guess">
</form>

<?= $res ?>

<a class="button" title="Start from beginning" href="init">Restart</a>
<a class="button" title="View the secret number" href="cheat">Cheat</a>
