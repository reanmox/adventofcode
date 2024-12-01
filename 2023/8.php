<?php

$filepath = "input/8.txt";

$map = [];
$order = [];

// read input data
$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);
$order = str_split($contents[0]);
$contents = explode("\n", $contents[1]);

// echo count($order) . "\n";
// echo 1 % count($order) . "\n";
// echo 281 % count($order) . "\n";

foreach ($contents as $index => $line) {
    $map[substr($line, 0, 3)] = [
        "L" => substr($line, 7, 3),
        "R" => substr($line, 12, 3),
    ];
}

// echo print_r($map, true);
// echo print_r($order, true);

$currentPosition = "AAA";
$steps = 0;
while ($currentPosition != "ZZZ") {
    $nextPosition = $map[$currentPosition][$order[$steps % count($order)]];
    $steps++;
    if ($nextPosition == "ZZZ") {
        echo " Task 1: found exit in $steps steps\n";
        break;
    }
    $currentPosition = $nextPosition;
}

// Task 2
$currentPositions = [];
foreach ($map as $key => $value) {
    if (substr($key, 2, 1) == "A") {
        $currentPositions[] = $key;
    }
}
echo print_r($currentPositions, true);
patternRecognition($currentPositions[0]);
patternRecognition($currentPositions[1]);
patternRecognition($currentPositions[2]);
patternRecognition($currentPositions[3]);
patternRecognition($currentPositions[4]);
patternRecognition($currentPositions[5]);

// looks like you can automate this, but only because there is always a pattern which is divisible by the count of orders
echo "Task 2 with new strategy: " .  gmp_lcm(13207, gmp_lcm(19951, gmp_lcm(14893, gmp_lcm(12083, gmp_lcm(20513, 22199))))) . "\n";

// takes years to find the exit ...
// $steps = 0;
// while (!isEnd($currentPositions)) {
//     foreach ($currentPositions as $index => $currentPosition) {
//         $currentPositions[$index] = $map[$currentPosition][$order[$steps % count($order)]];
//     }
//     $steps++;
//     if ($steps % 10000000 == 0) {
//         echo "Task 2: $steps steps, still no exit found\n";
//         echo print_r($currentPositions, true);
//     }
// }
// echo "Task 2: found exit in $steps steps\n";

function isEnd($positions)
{
    foreach ($positions as $position) {
        if (substr($position, 2, 1) != "Z") {
            return false;
        }
    }
    return true;
}

function patternRecognition($position, $steps = 100000)
{
    global $order, $map;

    $steps = 0;
    while ($steps < 100000) {
        $position = $map[$position][$order[$steps % count($order)]];
        $steps++;
        if (substr($position, 2, 1) == "Z") {
            echo "Task 2: found $position after $steps steps\n";
        }
    }
}
