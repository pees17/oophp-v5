<?php
namespace Anax\View;

/**
 * Render the view to show the header for all pages
 */

?><h1 class="content">My Database Driven Website</h1>

<navbar class="game content">
    <a class="button content" title="Home page" href="<?= url("content/page/hem") ?>">Home</a>
    <a class="button content" title="Blog" href="<?= url("content/blog") ?>">Blog</a>
    <a class="button content" title="About this website" href="<?= url("content/page/om") ?>">About</a>
</navbar>
