<?php
namespace Anax\View;

/**
 * Render the view to show all content, where type = 'page'
 */

if (!$res) {
    return;
}
?><table class="content page">
    <tr>
        <th class="center">Id</th>
        <th class="left">Titel</th>
        <th class="left">Type</th>
        <th class="left">Status</th>
        <th class="center">Published</th>
        <th class="center">Deleted</th>
    </tr>
    <?php foreach ($res as $row) : ?>
    <tr>
        <td class="center"><?= $row->id ?></td>
        <td class="left"><a href="<?= url("content/pages/$row->path") ?>"><?= $row->title ?></a></td>
        <td class="left"><?= $row->type ?></td>
        <td class="left"><?= $row->status ?></td>
        <td class="center"><?= $row->published ?></td>
        <td class="center"><?= $row->deleted ?></td>
    </tr>
    <?php endforeach; ?>
</table>
