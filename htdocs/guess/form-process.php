<?php
/**
 * Get the posted form and update session variables
 */
require __DIR__ . "/config.php";


// Handle incoming variables and write to session
$_SESSION["guess"] = $_POST["guess"] ?? null;
$_SESSION["doInit"] = $_POST["doInit"] ?? null;
$_SESSION["doGuess"] = $_POST["doGuess"] ?? null;
$_SESSION["doCheat"] = $_POST["doCheat"] ?? null;

// Redirect back to index.php
$url = "index.php";
header("Location: $url");
