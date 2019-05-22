<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));


/**
 * Render a view to demonstrate the effects of the BBCode formatting convertion to HTML
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
