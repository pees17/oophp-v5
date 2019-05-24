<?php
namespace Anax\View;

/**
 * Render the view to edit a content in the database
 */

if (!$res) {
    return;
}

?><form class="content" method="post">
    <fieldset>
    <legend>Edit content</legend>

    <p>
        <label>Title:</label><br>
        <input type="text" name="contentTitle" value="<?= esc($res->title) ?>">
    </p>
    <p>
        <label>Path:</label><br>
        <input type="text" name="contentPath" value="<?= esc($res->path) ?>">
    </p>
    <p>
        <label>Slug:</label><br>
        <input type="text" name="contentSlug" value="<?= esc($res->slug) ?>">
    </p>
    <p>
        <label>Text:</label><br>
        <textarea name="contentData"><?= esc($res->data) ?></textarea>
    </p>
    <p>
        <label>Type:</label><br>
        <input type="text" name="contentType" value="<?= esc($res->type) ?>">
    </p>
    <p>
        <label>Filter:</label><br>
        <input type="text" name="contentFilter" value="<?= esc($res->filter) ?>">
    </p>
    <p>
        <label>Publish:</label><br>
        <input type="text" name="contentPublish" value="<?= esc($res->published) ?>">
    </p>

    <p>
        <button type="submit" name="doSave"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
        <button type="reset"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button>
        <button type="submit" name="doDelete" formaction=<?= url("content/delete/$res->id") ?>>
        <i class="fas fa-trash-alt" aria-hidden="true"></i> Delete</button>
    </p>

    </fieldset>
</form>
