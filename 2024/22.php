<?php

$filepath = "input/22.txt";
$numbers = explode("\n", file_get_contents($filepath));

$sum = 0;
foreach ($numbers as $index => $n) {
    for ($i = 0; $i < 2000; $i++) {
        // Step 1
        $b = $n * 64;
        $n = prune(mix($n, $b));

        // Step 2
        $b = floor($n / 32);
        $n = prune(mix($n, $b));

        // Step 3
        $b = $n * 2048;
        $n = prune(mix($n, $b));
    }
    echo $numbers[$index] . ": $n" . PHP_EOL;
    $sum += $n;
}
echo "Part 1: $sum" . PHP_EOL;

function mix($a, $b)
{
    return $a ^ $b;
}

function prune($a)
{
    return $a % 16777216;
}
