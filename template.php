<?php

$filepath = "input/.txt";
$result = [];

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    // do something with each line

    // add to result
    $result[$index] = $line;
}

echo print_r($result, true);

// room for helper functions
