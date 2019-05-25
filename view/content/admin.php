<?php
namespace Anax\View;

/**
 * Render the view to show all content, and administrate it (CRUD)
 */

if (!$res) {
    return;
}

?><table class="content">
    <tr>
        <th class="center w3">Id</th>
        <th class="left w19">Titel</th>
        <th class="center w6">Path</th>
        <th class="center w6">Slug</th>
        <th class="center w16">Published</th>
        <th class="center w16">Created</th>
        <th class="center w16">Updated</th>
        <th class="center w16">Deleted</th>
        <th class="center w8">Action</th>
    </tr>
    <?php foreach ($res as $row) : ?>
    <tr>
        <td class="center w3"><?= $row->id ?></td>
        <td class="left w19"><?= $row->title ?></td>
        <td class="center w6"><?= $row->path ?></td>
        <td class="center w6"><?= $row->slug ?></td>
        <td class="center w16"><?= $row->published ?></td>
        <td class="center w16"><?= $row->created ?></td>
        <td class="center w16"><?= $row->updated ?></td>
        <td class="center w16"><?= $row->deleted ?></td>
        <td class="center w8">
        <a class="icon" title="Edit content" href="<?= url("content/edit/$row->id") ?>">
            <i class="fas fa-edit" aria-hidden="true"></i>
        </a>
        <a class="icon" title="Delete content" href="<?= url("content/delete/$row->id") ?>">
            <i class="fas fa-trash-alt" aria-hidden="true"></i>
        </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
