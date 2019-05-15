<?php
namespace Anax\View;

/**
 * Render the view to reset the database
 */

// Restore the database to its original settings
$file   = "../sql/movie/setup.sql";
// $mysql  = "/usr/bin/mysql";
$mysql  = '"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql"';
$output = null;

// Extract hostname and databasename from dsn
$dsnDetail = [];
preg_match("/mysql:host=(.+);dbname=([^;.]+)/", $config["dsn"], $dsnDetail);
$host = $dsnDetail[1];
$database = $dsnDetail[2];
$login = $config["username"];
$password = $config["password"];

if (isset($_POST["reset"]) || isset($_GET["reset"])) {
    $command = "$mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
    $output = [];
    $status = null;
    $res = exec($command, $output, $status);
    $output = "<p>The command was: <code>$command</code>.<br>The command exit status was $status."
        . "<br>The output from the command was:</p><pre>"
        . print_r($output, 1)
        . "</pre>";
}
?><h1>My Movie Database</h1>
<p>Reset the database to its initial state.</p>
<form method="post">
    <input type="submit" name="reset" value="Reset database">
    <?= $output ?>
</form>
<div class="game">
    <a class="button dice" title="View all movies" href="<?= url("movie/index") ?>">View all</a>
</div>
