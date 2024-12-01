<?php

$filepath = "input/.txt";
$result = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $result[$index] = $line;
}

echo print_r($result, true);
