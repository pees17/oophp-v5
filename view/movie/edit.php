<?php
namespace Anax\View;

/**
 * Render the view to edit a movie
 */

if (!$res) {
    return;
}
$movie = $res[0];

?><form class="movie" method="post">
    <fieldset>
    <legend>Edit movie</legend>

    <p>
        <label>Title:<br>
        <input type="text" name="movieTitle" value="<?= $movie->title ?>">
        </label>
    </p>

    <p>
        <label>Year:<br>
        <input type="number" name="movieYear" value="<?= $movie->year ?>">
    </p>

    <p>
        <label>Image:<br>
        <input type="text" name="movieImage" value="<?= $movie->image ?>">
        </label>
    </p>

    <p>
        <input type="submit" name="doSave" value="Save">
        <input type="reset" value="Reset">
    </p>
    </fieldset>
</form>

<div class="game">
    <a class="button dice" title="View all movies" href="<?= url("movie/index") ?>">Back</a>
</div>
