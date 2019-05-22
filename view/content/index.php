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
        <th class="center w4">Rad</th>
        <th class="center w4">Id</th>
        <th class="left w18">Titel</th>
        <th class="center w6">Type</th>
        <th class="center w17">Published</th>
        <th class="center w17">Created</th>
        <th class="center w17">Updated</th>
        <th class="center w17">Deleted</th>
    </tr>
<?php $id = -1; foreach ($res as $row) :
    $id++; ?>
    <tr>
        <td class="center w4"><?= $id ?></td>
        <td class="center w4"><?= $row->id ?></td>
        <td class="left w18"><?= $row->title ?></td>
        <td class="center w6"><?= $row->type ?></td>
        <td class="center w17"><?= $row->published ?></td>
        <td class="center w17"><?= $row->created ?></td>
        <td class="center w17"><?= $row->updated ?></td>
        <td class="center w17"><?= $row->deleted ?></td>
    </tr>
<?php endforeach; ?>
</table>
