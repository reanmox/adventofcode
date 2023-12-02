<?php

// read 0.txt
$filepath = "input/0.txt";
$contents = file_get_contents($filepath);

$result = [];

// split content on double new line
$contents = explode("\n\n", $contents);

// loop over each group
foreach ($contents as $key => $group) {
    // split group on new line
    $group = explode("\n", $group);

    $sum = 0;

    // loop
    foreach ($group as $amount) {
        $sum += $amount;
    }

    // add to result
    $result[$key] = $sum;
}

echo print_r($result, true);

// print highest sum
echo max($result) . "\n";

// sort result
rsort($result);
// print sum of top 3 sums
echo array_sum(array_slice($result, 0, 3)) . "\n";
