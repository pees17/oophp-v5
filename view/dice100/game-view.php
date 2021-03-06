<?php
namespace Anax\View;

$pointsOffset = ($state === "Throw") ? $sumCurrent : 0;
$nextRoute = ($current === "Computer") ?
url("dice100/playFirst") :
url("dice100/playComputer");

/**
 * Render the view to show the status of the game
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());
?><h1>Dice 100 game, controller version</h1>

<p>All players plays a round and after each round an evaluation is done to see if we have a winner.
</p>

<div class="game-container">
<div class="game_col1">

<p class="dice game">Round: <?= $round ?></p>
<p class="dice game">Current player: <?= $current ?></p>

<table class="game dices">
<tr>
    <th class="left">Player</td>
    <th class="center">Points</td>
</tr>
<?php foreach ($players as $player => $points) : ?>
<tr>
    <td class="left"><?= $player ?></td>
    <td class="center"><?= $points + (($player === $current) ? $pointsOffset : 0) ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php if ($winner) : ?>
    <div class="game">
        <a class="button dice" href="<?= url("dice100/startGame") ?>">Play again</a>
        <a class="button dice" href="<?= url("dice100/init") ?>">Re-Initialize</a>
    </div>
<?php elseif ($state === "Throw") : ?>
    <div class="game">
        <a class="button dice" href="<?= url("dice100/playNext") ?>">Throw</a>
        <a class="button dice" href="<?= url("dice100/playStop") ?>">Ready</a>
    </div>
<?php elseif ($state === "Lost" || $state === "Ready") : ?>
    <div class="game">
        <a class="button dice" href="<?= $nextRoute ?>">Throw next</a>
    </div>
<?php endif; ?>

</div>

<div class="game_col2">

<?php if ($dices) : ?>
    <p class="dice game"><?= $current ?> throws:</p>
    <?php foreach ($dices as $hand) : ?>
        <p class="dice-utf8">
        <?php foreach ($hand as $dice) : ?>
            <i class="<?= $dice ?>"></i>
        <?php endforeach; ?>
        </p>
    <?php endforeach; ?>

<p class="dice game">
    <?= $state === "Lost" ? "$current got a '1', the points are lost!" : "Current hand: $sumCurrent" ?>
</p>

<?php endif; ?>

<?php if ($winner) : ?>
    <p class="dice game winner">The winner is <?= $winner ?>!!!</p>
<?php endif; ?>

</div>
</div>
<div class="game_col2">
    <p class="dice game">Histogram in current game:</p>
    <p class="dice game"><?= $histogram ?></p>
</div>
