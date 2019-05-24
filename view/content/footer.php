<?php
namespace Anax\View;

/**
 * Render the view to show the footer
 */

?><div class="game">
    <a class="button dice" title="Create content" href="<?= url("content/create") ?>">Create content</a>
    <a class="button dice red" title="Reset the database to its initial state"
    href="<?= url("content/reset") ?>">Reset database</a>
</div>
