<?php

$filepath = "input/21.txt";
$map = [];

$directions = [
    "N" => ["x" => 0, "y" => -1],
    "S" => ["x" => 0, "y" => 1],
    "E" => ["x" => 1, "y" => 0],
    "W" => ["x" => -1, "y" => 0]
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $map[$index] = str_split(str_replace("S", "O", $line));
}

for ($i = 0; $i < 64; $i++) {
    $newMap = $map;
    foreach ($map as $y => $row) {
        foreach ($row as $x => $value) {
            if ($value == "O") {
                $newMap[$y][$x] = ".";
                foreach ($directions as $direction) {
                    $newX = $x + $direction["x"];
                    $newY = $y + $direction["y"];
                    if (isset($map[$newY][$newX]) && $map[$newY][$newX] == ".") {
                        $newMap[$newY][$newX] = "O";
                    }
                }
            }
        }
    }
    $map = $newMap;
}

$sum = 0;
foreach ($map as $row) {
    foreach ($row as $value) {
        if ($value == "O") {
            $sum++;
        }
    }
}
echo "Task 1: $sum\n";

// echo print_r($map, true);
