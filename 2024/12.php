<?php

$filepath = "input/12.txt";
$map = [];
$hikedPos = [];

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

$price = 0;
foreach ($map as $y => $row) {
    foreach ($row as $x => $cell) {
        if (!in_array([$y, $x], $hikedPos)) {
            $region = [];
            hikeMap($y, $x, $cell, $region);

            $pSum = 0;
            foreach ($region as $r) {
                $pSum += $r[2];
            }
            $price += count($region) * $pSum;
        }
    }
}

echo "Part 1: $price\n";

function hikeMap($y, $x, $label, &$region)
{
    global $map, $directions, $hikedPos;

    if (in_array([$y, $x], $hikedPos)) {
        return;
    }

    $hikedPos[] = [$y, $x];
    $perimeter = 4;

    foreach ($directions as $direction) {
        $newY = $y + $direction[0];
        $newX = $x + $direction[1];

        if ($newY >= 0 && $newY < count($map) && $newX >= 0 && $newX < count($map[0])) {
            if ($map[$newY][$newX] == $label) {
                if (!in_array([$newY, $newX], $hikedPos)) {
                    hikeMap($newY, $newX, $label, $region);
                }
                $perimeter--;
            }
        }
    }

    $region[] = [$y, $x, $perimeter];
}
