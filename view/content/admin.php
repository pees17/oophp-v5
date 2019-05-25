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
        <th class="center">Id</th>
        <th class="left">Titel</th>
        <th class="left nowrap">Path</th>
        <th class="left">Slug</th>
        <th class="center fixed-width">Published</th>
        <th class="center fixed-width">Created</th>
        <th class="center fixed-width">Updated</th>
        <th class="center fixed-width">Deleted</th>
        <th class="center">Action</th>
    </tr>
    <?php foreach ($res as $row) : ?>
    <tr>
        <td class="center"><?= $row->id ?></td>
        <td class="left"><?= $row->title ?></td>
        <td class="left nowrap"><?= $row->path ?></td>
        <td class="left"><?= $row->slug ?></td>
        <td class="center fixed-width"><?= $row->published ?></td>
        <td class="center fixed-width"><?= $row->created ?></td>
        <td class="center fixed-width"><?= $row->updated ?></td>
        <td class="center fixed-width"><?= $row->deleted ?></td>
        <td class="center">
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
