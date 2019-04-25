<?php
namespace Anax\View;

/**
 * Render the view to enter number of players and number of dices
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Dice 100 game</h1>

<p>In this game you will play against the computer. Enter your name and the number of dices to use.</p>

<form class="game dices" method="post">

<div class="game-container">
    <label for="nrDices">Nr dices (1-5)</label>
    <input id="nrDices" type="number" name="nrDices" min="1" max="5" required>
</div>
<div class="game-container">
    <label for="name">Name</label>
    <input id="name" type="text" name="name" required>
</div>

    <input type="submit" name="Init" value="Play">
</form>
