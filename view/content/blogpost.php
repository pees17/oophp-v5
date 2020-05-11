<?php
namespace Anax\View;

/**
 * Render the view to view a specific blog posts
 */
$filter = new \Peo\MyTextFilter\TextFilter();
?><article>
    <header>
        <h1 class="content blog"><?= esc($res->title) ?></h1>
        <p><i>Published: <time datetime="<?= esc($res->published_iso8601) ?>" pubdate>
            <?= esc($res->published) ?></time></i></p>
    </header>
    <?= $filter->parse(esc($res->data), explode(",", $res->filter)) ?>
</article>
