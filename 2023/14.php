<?php

$filepath = "input/14.txt";
$map = [];

$contents = file_get_contents($filepath);
// $contents = "O....#....
// O.OO#....#
// .....##...
// OO.#O....O
// .O.....O#.
// O.#..O.#.#
// ..O..#O..O
// .......O..
// #....###..
// #OO..#....";
$contents = explode("\n", $contents);

foreach ($contents as $y => $line) {
    $map[$y] = str_split($line);
}

// tilt north
for ($y = count($map) - 1; $y >= 1; $y--) {
    echo "y: $y\n";
    for ($x = 0; $x < count($map[$y]); $x++) {
        // echo "x: $x, value: " . $map[$y][$x] . ", north: " . $map[$y - 1][$x] . "\n";
        if ($map[$y][$x] == "O" && $map[$y - 1][$x] == ".") {
            $map[$y - 1][$x] = "O";
            $map[$y][$x] = ".";

            if ($y != count($map) - 1) {
                $pastCount = 1;
                while ($map[$y + $pastCount][$x] == "O") {
                    $map[$y + $pastCount - 1][$x] = "O";
                    $map[$y + $pastCount][$x] = ".";
                    $pastCount++;
                }
            }
        }
    }
}

$sum = 0;
foreach ($map as $y => $line) {
    foreach ($line as $x => $value) {
        if ($value == "O") {
            $sum += count($map) - $y;
        }
    }
}
echo "Task 1: $sum\n";

// echo print_r($map, true);
