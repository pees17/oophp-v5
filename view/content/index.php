<?php
namespace Anax\View;

/**
 * Render the view to show all content
 */

if (!$res) {
    return;
}

?><table class="content">
    <tr>
        <th class="center">Rad</th>
        <th class="center">Id</th>
        <th class="left">Titel</th>
        <th class="center">Type</th>
        <th class="center">Published</th>
        <th class="center">Created</th>
        <th class="center">Updated</th>
        <th class="center">Deleted</th>
        <th class="center">Action</th>
    </tr>
<?php $id = -1; foreach ($res as $row) :
    $id++; ?>
    <tr>
        <td class="center"><?= $id ?></td>
        <td class="center"><?= $row->id ?></td>
        <td class="left"><?= $row->title ?></td>
        <td class="center"><?= $row->type ?></td>
        <td class="center"><?= $row->published ?></td>
        <td class="center"><?= $row->created ?></td>
        <td class="center"><?= $row->updated ?></td>
        <td class="center"><?= $row->deleted ?></td>
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
