<?php

$filepath = "input/2.txt";
$reports = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $line) {
    $reports[] = explode(" ", $line);
}

$sum = 0;
foreach ($reports as $report) {
    if (checkIfSafe($report)) {
        $sum++;
    }
}
echo "Part 1: " . $sum . "\n";

function checkIfSafe($report)
{
    if ($report[0] == $report[1]) {
        return false;
    }

    $increasing = $report[0] < $report[1];

    for ($i = 0; $i < count($report) - 1; $i++) {
        if ($report[$i] == $report[$i + 1]) {
            return false;
        }

        if ($increasing && ($report[$i] > $report[$i + 1] || $report[$i + 1] - $report[$i] > 3)) {
            return false;
        }

        if (!$increasing && ($report[$i] < $report[$i + 1] || $report[$i] - $report[$i + 1] > 3)) {
            return false;
        }
    }

    return true;
}
