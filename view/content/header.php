<?php
namespace Anax\View;

/**
 * Render the view to show the header for all pages
 */

?><h1>My Content Database</h1>

<navbar class="content">
    <a href="<?= url("content") ?>">Show all content</a> |
    <a href="<?= url("content/admin") ?>">Admin</a> |
    <a href="?route=create">Create</a> |
    <a href="?route=reset">Reset database</a> |
    <a href="?route=pages">View pages</a> |
    <a href="?route=blog">View blog</a>
</navbar>
