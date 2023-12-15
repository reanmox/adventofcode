<?php

$filepath = "input/14.txt";
$map = [];
$history = [];

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

for ($i = 0; $i < 160; $i++) {
    $map = tiltNorth($map);
    $map = tiltWest($map);
    $map = tiltSouth($map);
    $map = tiltEast($map);
    // find patterns
    if (!in_array($map, $history))
        $history[] = $map;
    else {
        $index = array_search($map, $history);
        echo "Found pattern $i equal to $index " . ($index - 119) % 42 . "\n";
    }
}

// looks like the pattern is reached already after 6 iterations for the test data
// looks like the pattern is reached already after 160 iterations for the real data
echo (1000000000 - 119) % 42 . "\n";

// echo print_r($map, true);

$sum = 0;
foreach ($map as $y => $line) {
    foreach ($line as $x => $value) {
        if ($value == "O") {
            $sum += count($map) - $y;
        }
    }
}
echo "Task 2: $sum\n";


function tiltNorth($map)
{
    for ($y = count($map) - 1; $y >= 1; $y--) {
        // echo "y: $y\n";
        for ($x = 0; $x < count($map[$y]); $x++) {
            if ($map[$y][$x] == "O" && $map[$y - 1][$x] == ".") {
                $map[$y - 1][$x] = "O";
                $map[$y][$x] = ".";

                if ($y != count($map) - 1) {
                    $pastCount = 1;
                    while ($y + $pastCount != count($map) && $map[$y + $pastCount][$x] == "O") {
                        $map[$y + $pastCount - 1][$x] = "O";
                        $map[$y + $pastCount][$x] = ".";
                        $pastCount++;
                    }
                }
            }
        }
    }
    return $map;
}

function tiltSouth($map)
{
    for ($y = 0; $y < count($map) - 1; $y++) {
        // echo "y: $y\n";
        for ($x = 0; $x < count($map[$y]); $x++) {
            if ($map[$y][$x] == "O" && $map[$y + 1][$x] == ".") {
                $map[$y + 1][$x] = "O";
                $map[$y][$x] = ".";

                if ($y != 0) {
                    $pastCount = 1;
                    while ($y - $pastCount >= 0 && $map[$y - $pastCount][$x] == "O") {
                        $map[$y - $pastCount + 1][$x] = "O";
                        $map[$y - $pastCount][$x] = ".";
                        $pastCount++;
                    }
                }
            }
        }
    }
    return $map;
}

function tiltWest($map)
{
    for ($x = count($map[0]) - 1; $x >= 1; $x--) {
        // echo "x: $x\n";
        for ($y = 0; $y < count($map); $y++) {
            if ($map[$y][$x] == "O" && $map[$y][$x - 1] == ".") {
                $map[$y][$x - 1] = "O";
                $map[$y][$x] = ".";

                if ($x != count($map[$y]) - 1) {
                    $pastCount = 1;
                    while ($x + $pastCount != count($map[0]) && $map[$y][$x + $pastCount] == "O") {
                        $map[$y][$x + $pastCount - 1] = "O";
                        $map[$y][$x + $pastCount] = ".";
                        $pastCount++;
                    }
                }
            }
        }
    }
    return $map;
}

function tiltEast($map)
{
    for ($x = 0; $x < count($map[0]) - 1; $x++) {
        // echo "x: $x\n";
        for ($y = 0; $y < count($map); $y++) {
            if ($map[$y][$x] == "O" && $map[$y][$x + 1] == ".") {
                $map[$y][$x + 1] = "O";
                $map[$y][$x] = ".";

                if ($x != 0) {
                    $pastCount = 1;
                    while ($x - $pastCount >= 0 && $map[$y][$x - $pastCount] == "O") {
                        $map[$y][$x - $pastCount + 1] = "O";
                        $map[$y][$x - $pastCount] = ".";
                        $pastCount++;
                    }
                }
            }
        }
    }
    return $map;
}
