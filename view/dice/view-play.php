<?php
namespace Anax\View;

/**
 * Render the view to show the status of the game
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());
?><h1>Dice 100 game</h1>

<p>All players plays a round and after each round an evaluation is done to see if we have a winner.
If one of the players name is "Computer", then the number of throws in each row for that player
will be decided and done by the computer automatically.</p>
<div class="game-container">
<div class="game_col1">

<p class="dice game">Round: <?= $round ?></p>
<p class="dice game">Player: <?= $current ?></p>

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

<p class="dice game">Current sum: <?= $sumCurrent ?></p>

</div>

<?php if ($dices) : ?>
<div class="game_col2">
    <p class="dice game">Player <?= $current ?> throws:</p>
    <?php foreach ($dices as $hand) : ?>
        <p class="dice-utf8">
        <?php foreach ($hand as $dice) : ?>
            <i class="<?= $dice ?>"></i>
        <?php endforeach; ?>
        </p>
    <?php endforeach; ?>

    <?php if ($state === "Lost") : ?>
        <p class="dice game">You got a '1', your points are lost!</p>
    <?php endif; ?>
    <?php if ($res) : ?>
        <p class="dice game winner">The winner is <?= $res ?>!!!</p>
    <?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ($res) : ?>
    <div class="game">
        <a class="button dice" href="play-again">Play again</a>
        <a class="button dice" href="init">Re-Initialize</a>
    </div>
<?php elseif ($state === "Throw") : ?>
    <div class="game">
        <a class="button dice" href="play-throw">Throw</a>
        <a class="button dice" href="play-stop">Ready</a>
    </div>
<?php elseif ($state === "Lost" || $state === "Ready") : ?>
    <div class="game">
        <a class="button dice" href="play-next">Throw next</a>
    </div>
<?php endif; ?>
