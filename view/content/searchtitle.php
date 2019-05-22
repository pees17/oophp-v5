<?php
namespace Anax\View;

/**
 * Render the view to enter search string
 */

?><form class="movie" method="get">
    <fieldset>
    <legend>Search movie</legend>

    <p>
        <label>Title (use % as wildcard):
            <input type="search" name="searchTitle" value="<?= htmlentities($searchTitle) ?>">
        </label>
    </p>
    <p>
        <input type="submit" name="doSearch" value="Search">
    </p>
    </fieldset>
</form>
