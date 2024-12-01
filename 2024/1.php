<?php

$filepath = "input/1.txt";

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

$arr1 = [];
$arr2 = [];

foreach ($contents as $line) {
    $lineArr = explode("   ", $line);
    $arr1[] = $lineArr[0];
    $arr2[] = $lineArr[1];
}

sort($arr1);
sort($arr2);

$sum = 0;
for ($i = 0; $i < count($arr1); $i++) {
    $sum += abs($arr1[$i] - $arr2[$i]);
}

echo "Part 1: " . $sum . "\n";
