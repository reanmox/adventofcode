<?php

$filepath = "input/9.txt";
$histories = [];

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

foreach ($contents as $index => $history) {
    $histories[$index][] = explode(" ", $history);
    $indexSeq = 0;
    while ($indexSeq == 0 || !isAllZero($seq)) {
        $seq = getSequenceOfDifferences($histories[$index][$indexSeq]);
        $histories[$index][] = $seq;
        $indexSeq++;
    }
    for ($i = $indexSeq - 1; $i >= 0; $i--) {
        $histories[$index][$i][] = end($histories[$index][$i]) + end($histories[$index][$i + 1]);
    }
    for ($i = $indexSeq - 1; $i >= 0; $i--) {
        array_unshift($histories[$index][$i], $histories[$index][$i][0] - $histories[$index][$i + 1][0]);
    }
    // if ($index == 0)
    //     break;
}

// echo print_r($histories, true);

$sum = 0;
foreach ($histories as $history) {
    $sum += end($history[0]);
}
echo "Task 1: " . $sum . "\n";

$sum = 0;
foreach ($histories as $history) {
    $sum += $history[0][0];
}
echo "Task 2: " . $sum . "\n";


function isAllZero($array)
{
    foreach ($array as $value) {
        if ($value != 0) {
            return false;
        }
    }
    return true;
}

function getSequenceOfDifferences($array)
{
    $differences = [];
    for ($i = 0; $i < count($array) - 1; $i++) {
        $differences[] = $array[$i + 1] - $array[$i];
    }
    return $differences;
}
