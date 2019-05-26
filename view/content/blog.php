<?php
namespace Anax\View;

/**
 * Render the view to view all published blog posts
 */
if (!$res) {
    return;
}
$filter = new \Peo\MyTextFilter\TextFilter();
?><article>

<?php foreach ($res as $row) : ?>
<section>
    <header>
        <h1 class="content blog"><a href="<?= url("content/blog/$row->slug") ?>"><?= esc($row->title) ?></a></h1>
        <p><i>Published: <time datetime="<?= esc($row->published_iso8601) ?>" pubdate>
        <?= esc($row->published) ?></time></i></p>
    </header>
    <?= $filter->parse(esc($row->data), explode(",", $row->filter)) ?>
</section>
<?php endforeach; ?>

</article>
