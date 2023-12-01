<?php

$filepath = "input/.txt";

// read input data
$fp = fopen($filepath, "r");
$contents = fread($fp, filesize($filepath));
fclose($fp);

$result = [];

// split content on new line
$contents = explode("\n", $contents);

// loop over each line
foreach ($contents as $index => $line) {
    // do something with each line

    // add to result
    $result[$index] = $line;
}

echo print_r($result, true);

// room for helper functions
