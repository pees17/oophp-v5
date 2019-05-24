<?php
namespace Anax\View;

/**
 * Render the view to show the footer
 */

?><div class="game">
    <a class="button content" title="Create new content" href="<?= url("content/create") ?>">Create content</a>
    <a class="button content red" title="Reset the database to its initial state"
    href="<?= url("content/reset") ?>">Reset database</a>
</div>
