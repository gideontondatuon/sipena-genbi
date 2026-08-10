<?php

$html = file_get_contents(__DIR__ . '/embed.html');

// Strip all HTML tags, script tags, style tags
$text = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);
$text = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $text);
$text = strip_tags($text);

$lines = explode("\n", $text);
foreach ($lines as $l) {
    $clean = trim($l);
    if (strlen($clean) > 3) {
        echo "TEXT LINE: " . $clean . "\n";
    }
}
