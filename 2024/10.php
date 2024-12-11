<?php

$filepath = "input/10.txt";
$map = [];

$directions = [
    [0, 1], // right
    [1, 0], // down
    [0, -1], // left
    [-1, 0], // up
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $line) {
    $map[] = str_split($line);
}

$sum = 0;
foreach ($map as $y => $row) {
    foreach ($row as $x => $value) {
        if ($value == "0") {
            $nines = [];
            hikeMap($y, $x, 0, $nines);
            $sum += count($nines);
        }
    }
}

echo "Part 1: " . $sum . "\n";

function hikeMap($y, $x, $height, &$nines)
{
    global $map, $directions;

    if ($map[$y][$x] == "9" && !in_array([$y, $x], $nines)) {
        $nines[] = [$y, $x];
        return;
    }

    foreach ($directions as $direction) {
        $newY = $y + $direction[0];
        $newX = $x + $direction[1];

        if ($newY >= 0 && $newY < count($map) && $newX >= 0 && $newX < count($map[0])) {
            if ($map[$newY][$newX] == $height + 1) {
                hikeMap($newY, $newX, $height + 1, $nines);
            }
        }
    }
}
