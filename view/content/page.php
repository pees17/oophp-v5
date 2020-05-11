<?php
namespace Anax\View;

$modified = null;
if ($viewModified) {
    $modified = "<p><i>Latest update: <time datetime=" .
    esc($res->modified_iso8601) . " pubdate>" .
    esc($res->modified) . "</time></i></p>";
}
/**
 * Render the view to show a specific page
 */
$filter = new \Peo\MyTextFilter\TextFilter();
?><article>
    <header>
        <h1 class="content"><?= esc($res->title) ?></h1>
        <?= $modified ?>
    </header>
    <?= $filter->parse(esc($res->data), $filters) ?>
</article>
