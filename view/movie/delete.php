<?php
namespace Anax\View;

/**
 * Render the view to delete a movie
 */

if (!$res) {
    return;
}
$movie = $res[0];

?><form class="movie" method="post">
    <fieldset>
    <legend>Delete movie</legend>

    <p>
        <label>Title:<br>
        <input type="text" name="movieTitle" value="<?= $movie->title ?>" readonly>
        </label>
    </p>
    <p>
        <label>Year:<br>
        <input type="number" name="movieYear" value="<?= $movie->year ?>" readonly>
    </p>
    <p>
        <label>Image:<br>
        <input type="text" name="movieImage" value="<?= $movie->image ?>" readonly>
        </label>
    </p>
    <p>
        <input type="submit" name="doDelete" value="Delete">
    </p>
    </fieldset>
</form>
