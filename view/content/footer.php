<?php
namespace Anax\View;

/**
 * Render the view to show the footer for all pages
 */

?><div class="game">
    <a class="button dice" title="View all movies" href="<?= url("movie/index") ?>">View all</a>
    <a class="button dice" title="Add movie" href="<?= url("movie/add") ?>">Add movie</a>
    <a class="button dice" title="Search on title" href="<?= url("movie/searchtitle") ?>">Search title</a>
    <a class="button dice" title="Search on year" href="<?= url("movie/searchyear") ?>">Search year</a></div>
<div class="game">
    <a class="button dice red" title="Reset the database to its initial state"
    href="<?= url("movie/reset") ?>">Reset database</a>
</div>
