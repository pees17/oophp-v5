<h1>Guess my number</h1>

<p>Guess a number between 1 and 100, you have <?= $tries ?> guesses left.</p>

<form method="post" action="form-process.php">
    <input type="text" name="guess">
    <input type="submit" name="doGuess" value="Make a guess">
    <input type="submit" name="doInit" value="Start from beginning">
    <input type="submit" name="doCheat" value="Cheat">
</form>

<?= $res ?>
