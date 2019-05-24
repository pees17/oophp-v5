<?php

/**
 * Sanitize value for output in view.
 *
 * @param string $value to sanitize
 *
 * @return string beeing sanitized
 */
function esc($value)
{
    return htmlentities($value);
}


/**
 * Check if the slug is unique. If not append an integer at
 * the end until it is.
 *
 * @param string $slug the slug to check
 * @param array $slugs array with current slugs in the database
 *
 * @return string the checked and if applicable changed slug
 */
function makeSlugUnique($slug, $slugs)
{
    $append = 1;
    $tempSlug = $slug;
    while (in_array($tempSlug, $slugs)) {
        $tempSlug = $slug . "-" . $append;
        $append += 1;
    }
    return $tempSlug;
}

/**
 * Create a slug of a string, to be used as url.
 *
 * @param string $str the string to format as slug.
 *
 * @return string the formatted slug.
 */
function slugify($str)
{
    $str = mb_strtolower(trim($str));
    $str = str_replace(['å','ä'], 'a', $str);
    $str = str_replace('ö', 'o', $str);
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = trim(preg_replace('/-+/', '-', $str), '-');
    return $str;
}
