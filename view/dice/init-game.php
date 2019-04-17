<?php
namespace Anax\View;

/**
 * Render the view to enter number of players and number of dices
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Dice 100 game</h1>

<p>Enter number of players in the game and number of dices to use.</p>

<form class="game" method="post">
<div class="game-container">
    <label for="nrPlayers">Nr players (2-6)</label>
    <input id="nrPlayers" type="number" name="nrPlayers" min="2" max="6" required>
</div>
<div class="game-container">
    <label for="nrDices">Nr dices (1-5)</label>
    <input id="nrDices" type="number" name="nrDices" min="1" max="5" required>
</div>
    <input type="submit" name="Init" value="Enter">
</form>
