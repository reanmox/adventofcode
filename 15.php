<?php

$filepath = "input/15.txt";
$result = [];

// read input data
$contents = file_get_contents($filepath);
$contents = explode(",", $contents);

$sum = 0;
foreach ($contents as $index => $string) {
    $current = 0;
    foreach (str_split($string) as $char) {
        $current += ord($char);
        $current *= 17;
        $current = $current % 256;
    }
    $result[$string] = $current;
    $sum += $current;
}

echo "Task 1 " . $sum . "\n";
