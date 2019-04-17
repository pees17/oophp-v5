<?php
namespace Anax\View;

/**
 * Render the view to show the status of the start phase and
 * handle the throwing of dices to decide the start order
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Dice 100 game</h1>

<p>All players plays a round and after each round an evaluation is done to see if we have a winner.
If one of the players name is "Computer", then the number of throws in each row for that player
will be decided and done by the computer automatically.</p>

<p class="dice game">Round: <?= $round ?></p>
<p class="dice game">Player: <?= $current ?></p>

<div class="game-container">
<table class="game dices">
<tr>
    <th class="left">Player</td>
    <th class="center">Points</td>
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
        <a class="button" href="play-throw">Throw</a>
        <a class="button" href="play-stop">Ready</a>
    </div>
<?php elseif ($res === "Lost") : ?>
    <div class="game">
        <a class="button" href="play-next">Next player</a>
    </div>
    <p class="dice game">You got a '1', your points are lost!</p>
<?php elseif ($res === "Tie") : ?>
    <p class="game">It is a tie, throw again!</p>
    <div class="game">
        <a class="button" href="start-restart">Throw dice</a>
    </div>
<?php else : ?>
    <p class="game"><?= $res ?></p>
    <div class="game">
        <a class="button" href="start-game">Start the game</a>
    </div>
<?php endif; ?>
<p class="dice game">Current sum: <?= $sumCurrent ?></p>
