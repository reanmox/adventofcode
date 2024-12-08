<?php

$filepath = "input/7.txt";
$equations = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $line) {
    $equation = explode(":", $line);
    $equations[$equation[0]] = explode(" ", trim($equation[1]));
}

$sum = 0;
foreach ($equations as $result => $equation) {
    $results = [];
    solve(0, $equation, $equation[0], $results);
    if (in_array($result, $results)) {
        $sum += $result;
    }
}
echo "Part 1: $sum\n";

function solve($index, $numbers, $result, &$results)
{
    if ($index + 1 == count($numbers)) {
        $results[] = $result;
        return;
    }

    solve($index + 1, $numbers, $result + $numbers[$index + 1], $results);
    solve($index + 1, $numbers, $result * $numbers[$index + 1], $results);
}
