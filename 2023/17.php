<?php

$filepath = "input/17_test.txt";
$map = [];

$directions = [
    "N" => ["x" => 0, "y" => -1, "opposite" => "S"],
    "S" => ["x" => 0, "y" => 1, "opposite" => "N"],
    "E" => ["x" => 1, "y" => 0, "opposite" => "W"],
    "W" => ["x" => -1, "y" => 0, "opposite" => "E"]
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $map[$index] = str_split($line);
}

echo print_r($map, true);

$endX = count($map[0]) - 1;
$endY = count($map) - 1;

$paths = [];
$leastHeatLoss = 0;

hikeMap(0, 0, [], $paths);

echo "Task 1: " . $leastHeatLoss . "\n";

function hikeMap($x, $y, $path, &$paths, $curDirection = null)
{
    global $map, $endX, $endY, $directions, $leastHeatLoss;

    $path["$x;$y"] = ($x == 0 && $y == 0) ? 0 : $map[$y][$x];

    // end reached
    if ($x == $endX && $y == $endY) {
        $sum = array_sum($path);
        $paths[] = $sum;
        echo count($paths) . "\n";
        if ($sum < $leastHeatLoss || $leastHeatLoss == 0) {
            $leastHeatLoss = $sum;
            echo "New path with least heat loss: $leastHeatLoss\n";
        }
        return;
    }

    foreach ($directions as $keyDirection => $direction) {

        if ($curDirection != null && $curDirection["opposite"] == $direction) {
            continue;
        }

        $newX = $x + $direction["x"];
        $newY = $y + $direction["y"];

        if ($newX < 0 || $newX >= count($map[0]) || $newY < 0 || $newY >= count($map)) {
            continue;
        }
        if (isset($path["$newX;$newY"])) {
            continue;
        }

        hikeMap($newX, $newY, $path, $paths, $keyDirection);
    }
}
