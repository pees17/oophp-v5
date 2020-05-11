<?php
namespace Anax\View;

/**
 * Render the view to show the header for all pages
 */

?><h1 class="content">Edit My Content Database</h1>

<navbar class="content">
    <a href="<?= url("editContent") ?>">Show all content</a> |
    <a href="<?= url("editContent/admin") ?>">Admin</a> |
    <a href="<?= url("editContent/create") ?>">Create content</a> |
    <a href="<?= url("editContent/reset") ?>">Reset database</a> |
    <a href="<?= url("editContent/pages") ?>">View pages</a> |
    <a href="<?= url("editContent/blog") ?>">View blog</a>
</navbar>
