<?php
namespace Anax\View;

/**
 * Render the view to show a specific page
 */

?><article>
    <header>
        <h1 class="content"><?= esc($res->title) ?></h1>
        <p><i>Latest update: <time datetime="<?= esc($res->modified_iso8601) ?>" pubdate>
        <?= esc($res->modified) ?></time></i></p>
    </header>
    <?= esc($res->data) ?>
</article>
