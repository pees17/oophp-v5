<?php
namespace Anax\View;

/**
 * Render the view to show the status of the start phase and
 * handle the throwing of dices to decide the start order
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Dice 100 game</h1>

<p>First all players need to throw one dice each to decide who shall start.
Highest dice starts, and then the others follow in descending order.</p>

<div class="game-container">
<table class="game dices">
<tr>
    <th class="left">Player</td>
    <th class="center">Dice</td>
</tr>
<?php foreach ($players as $player => $points) : ?>
<tr>
    <td class="left"><?= $player ?></td>
    <td class="center"><?= $points ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

<?php if (!$res) : ?>
    <div class="game">
        <a class="button" title="Throw dice" href="start-throw">Throw dice</a>
    </div>
<?php elseif ($res === "Tie") : ?>
    <p class="game">It is a tie, throw again!</p>
    <div class="game">
        <a class="button" title="Throw dice" href="start-restart">Throw dice</a>
    </div>
<?php else : ?>
    <p class="game"><?= $res ?></p>
    <div class="game">
        <a class="button" title="Start game" href="start-game">Start the game</a>
    </div>
<?php endif; ?>
