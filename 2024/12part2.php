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
            $sides = countCorners($region);
            $price += count($region) * $sides;
        }
    }
}

echo "Part 2: $price\n";

function hikeMap($y, $x, $label, &$region)
{
    global $map, $directions, $hikedPos;

    if (in_array([$y, $x], $hikedPos)) {
        return;
    }

    $hikedPos[] = [$y, $x];

    foreach ($directions as $direction) {
        $newY = $y + $direction[0];
        $newX = $x + $direction[1];

        if ($newY >= 0 && $newY < count($map) && $newX >= 0 && $newX < count($map[0])) {
            if ($map[$newY][$newX] == $label) {
                if (!in_array([$newY, $newX], $hikedPos)) {
                    hikeMap($newY, $newX, $label, $region);
                }
            }
        }
    }

    $region[] = [$y, $x];
}

// AB
// CD
// Check if middle is a corner
function countCorners($region)
{
    global $map;

    $corners = 0;

    for ($y = 0; $y <= count($map); $y++) {
        for ($x = 0; $x <= count($map[0]); $x++) {

            $hits = 0;
            $a = in_array([$y - 1, $x - 1], $region);
            $b = in_array([$y - 1, $x], $region);
            $c = in_array([$y, $x - 1], $region);
            $d = in_array([$y, $x], $region);

            $hits += ($a ? 1 : 0) + ($b ? 1 : 0) + ($c ? 1 : 0) + ($d ? 1 : 0);

            if ($hits == 1 || $hits == 3) {
                $corners++;
            }

            if ($hits == 2) {
                if ($a && $d || $b && $c) {
                    $corners++;
                    $corners++;
                }
            }
        }
    }

    return $corners;
}
