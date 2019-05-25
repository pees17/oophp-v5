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
        <th class="center">Id</th>
        <th class="left">Titel</th>
        <th class="left nowrap">Path</th>
        <th class="left">Slug</th>
        <th class="center fixed-width">Published</th>
        <th class="center fixed-width">Created</th>
        <th class="center fixed-width">Updated</th>
        <th class="center fixed-width">Deleted</th>
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
    </tr>
    <?php endforeach; ?>
</table>
