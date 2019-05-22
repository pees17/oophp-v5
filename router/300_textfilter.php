<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));


/**
 * Render a view to demonstrate the effects of the BBCode formatting convertion to HTML.
 */
$app->router->get("textfilter/bbcode", function () use ($app) {
    $title = "Show off BBCode";

    // Get text and filter it
    $filter = new Peo\MyTextFilter\TextFilter();
    $text = file_get_contents(__DIR__ . "/../text/bbcode.txt");
    $html = $filter->parse($text, ["bbcode"]);

    // Render view
    $data = [
        "text" => $text,
        "html" => $html
    ];
    $app->page->add("textfilter/bbcode", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
 * Render a view to demonstrate the effects of makeClickable. A filter
 * that makes clickable links from URLs in text.
 */
$app->router->get("textfilter/clickable", function () use ($app) {
    $title = "Show off Clickable";

    // Get text and filter it
    $filter = new Peo\MyTextFilter\TextFilter();
    $text = file_get_contents(__DIR__ . "/../text/clickable.txt");
    $html = $filter->parse($text, ["link"]);

    // Render view
    $data = [
        "text" => $text,
        "html" => $html
    ];
    $app->page->add("textfilter/clickable", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Render a view to demonstrate the effects of the Markdown formatting convertion to HTML.
 */
$app->router->get("textfilter/markdown", function () use ($app) {
    $title = "Show off Markdown";

    // Get text and filter it
    $filter = new Peo\MyTextFilter\TextFilter();
    $text = file_get_contents(__DIR__ . "/../text/sample.md");
    $html = $filter->parse($text, ["markdown"]);

    // Render view
    $data = [
        "text" => $text,
        "html" => $html
    ];
    $app->page->add("textfilter/markdown", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Render a view to demonstrate the effects of the nl2br formatting of text.
 */
$app->router->get("textfilter/nl2br", function () use ($app) {
    $title = "Show off nl2br";

    // Get text and filter it
    $filter = new Peo\MyTextFilter\TextFilter();
    $text = file_get_contents(__DIR__ . "/../text/nl2br.txt");
    $html = $filter->parse($text, ["nl2br"]);
    // var_dump($text);
    // var_dump($html);
    // die();
    // Render view
    $data = [
        "text" => $text,
        "html" => $html
    ];
    $app->page->add("textfilter/nl2br", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});
