<?php
namespace Anax\View;

/**
 * Render the view to enter the name of the players
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Dice 100 game</h1>

<p>Enter the name of the players. Player 1 defaults to "Computer", but can be changed.</p>

<form class="game dices" method="post" action="get-names">
<?php for ($player = 1; $player <= $nrPlayers; $player++) : ?>
    <div class="game-container">
        <label for="player_<?= $player ?>">Player <?= $player ?></label>
        <input id="player_<?= $player ?>"
        value="<?= $player == 1 ? 'Computer' : null ?>" type="text" name="players[]" required>
    </div>
<?php endfor; ?>


    <input type="submit" name="Init" value="Enter">
</form>
