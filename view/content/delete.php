<?php
namespace Anax\View;

/**
 * Render the view to delete a content in the database
 */

if (!$res) {
    return;
}

?><form class="content" method="post">
    <fieldset>
    <legend>Delete content</legend>

    <p>
        <label>Title:</label><br>
        <input type="text" name="contentTitle" value="<?= esc($res->title) ?>" readonly>
    </p>
    <p>
        <label>Path:</label><br>
        <input type="text" name="contentPath" value="<?= esc($res->path) ?>" readonly>
    </p>
    <p>
        <label>Slug:</label><br>
        <input type="text" name="contentSlug" value="<?= esc($res->slug) ?>" readonly>
    </p>
    <p>
        <label>Text:</label><br>
        <textarea name="contentData" readonly><?= esc($res->data) ?> </textarea>
    </p>
    <p>
        <label>Type:</label><br>
        <input type="text" name="contentType" value="<?= esc($res->type) ?>" readonly>
    </p>
    <p>
        <label>Filter:</label><br>
        <input type="text" name="contentFilter" value="<?= esc($res->filter) ?>" readonly>
    </p>
    <p>
        <label>Publish:</label><br>
        <input type="text" name="contentPublish" value="<?= esc($res->published) ?>" readonly>
    </p>

    <p>
        <button type="submit" name="doDelete"><i class="fas fa-trash-alt" aria-hidden="true"></i> Delete</button>
    </p>

    </fieldset>
</form>
