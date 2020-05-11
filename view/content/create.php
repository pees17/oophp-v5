<?php
namespace Anax\View;

/**
 * Render the view to create content in the database
 */

?><form class="content" method="post">
    <fieldset>
    <legend>Create content</legend>
    <p>
        <label>Title:</label><br>
        <input type="text" name="contentTitle" value="A Title">
    </p>

    <p>
        <button type="submit" name="doCreate"><i class="fa fa-plus" aria-hidden="true">
        </i> Create</button>
        <button type="reset"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button>
    </p>
    </fieldset>
</form>
