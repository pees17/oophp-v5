<?php
namespace Anax\View;

/**
 * Render the view to edit a movie
 */

if (!$res) {
    return;
}
$movie = $res[0];

?><h1>My Movie Database</h1>
<form class="movie" method="post">
    <fieldset>
    <legend>Edit</legend>
    <input type="hidden" name="movieId" value="<?= $movie->id ?>"/>

    <p>
        <label>Title:<br>
        <input type="text" name="movieTitle" value="<?= $movie->title ?>"/>
        </label>
    </p>

    <p>
        <label>Year:<br>
        <input type="number" name="movieYear" value="<?= $movie->year ?>"/>
    </p>

    <p>
        <label>Image:<br>
        <input type="text" name="movieImage" value="<?= $movie->image ?>"/>
        </label>
    </p>

    <p>
        <input type="submit" name="doSave" value="Save">
        <input type="reset" value="Reset">
    </p>
    </fieldset>
</form>
<!-- <div class="game">
    <a class="button dice" title="Add movie" href="<?= url("movie/add") ?>">Add</a>
</div>
<div class="game">
    <a class="button dice" title="Reset the database" href="<?= url("movie/reset") ?>">Reset database</a>
</div> -->
