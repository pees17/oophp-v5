<?php
namespace Anax\View;

/**
 * Render the view to show the header for all pages
 */

?><h1 class="content">My Content Database</h1>

<navbar class="content">
    <a href="<?= url("content") ?>">Show all content</a> |
    <a href="<?= url("content/admin") ?>">Admin</a> |
    <a href="<?= url("content/create") ?>">Create content</a> |
    <a href="<?= url("content/reset") ?>">Reset database</a> |
    <a href="<?= url("content/pages") ?>">View pages</a> |
    <a href="<?= url("content/blog") ?>">View blog</a>
</navbar>
