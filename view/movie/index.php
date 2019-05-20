<?php
namespace Anax\View;

/**
 * Render the view to show all movies
 */

if (!$res) {
    return;
}

?><table class="movie">
    <tr>
        <th class="center">Rad</th>
        <th class="center">Id</th>
        <th class="center">Bild</th>
        <th class="left">Titel</th>
        <th class="center">År</th>
        <th class="center">Åtgärd</th>
    </tr>
<?php $id = -1; foreach ($res as $row) :
    $id++; ?>
    <tr>
        <td class="center"><?= $id ?></td>
        <td class="center"><?= $row->id ?></td>
        <td class="center"><img class="thumb" src="<?= url($row->image) ?>"></td>
        <td class="left"><?= $row->title ?></td>
        <td class="center"><?= $row->year ?></td>
        <td class="center">
        <a class="icon" title="Edit movie" href="<?= url("movie/edit/$row->id") ?>">Edit</a>
        <a class="icon" title="Delete movie" href="<?= url("movie/delete/$row->id") ?>">Delete</a>
    </a>
</td>
    </tr>
<?php endforeach; ?>
</table>
