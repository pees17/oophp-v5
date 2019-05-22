<?php
namespace Anax\View;

/**
 * Render the view to enter years to search between
 */

?><form class="movie" method="get">
    <fieldset>
    <legend>Search movie</legend>
    <p>
        <label>Created between:
        <input type="number" name="year1" value="<?= $year1 ?: 1900 ?>" min="1900" max="2100">
        -
        <input type="number" name="year2" value="<?= $year2 ?: 2100 ?>" min="1900" max="2100">
        </label>
    </p>
    <p>
        <input type="submit" name="doSearch" value="Search">
    </p>
    </fieldset>
</form>
