<?php
namespace Anax\View;

?><h1>Show off nl2br</h1>

<h2>Source in nl2br.txt</h2>
<pre><?= wordwrap(htmlentities($text)) ?></pre>

<h2>Source formatted as HTML</h2>
<?= $text ?>

<h2>Filter nl2br applied, source</h2>
<pre><?= wordwrap(htmlentities($html)) ?></pre>

<h2>Filter nl2br applied, HTML</h2>
<?= $html ?>
