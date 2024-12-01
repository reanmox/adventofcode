<?php

$filepath = "input/18.txt";
$instructions = [];
$map = [];

$directions = [
    "U" => ["x" => 0, "y" => -1, "key" => "U"],
    "D" => ["x" => 0, "y" => 1, "key" => "D"],
    "R" => ["x" => 1, "y" => 0, "key" => "R"],
    "L" => ["x" => -1, "y" => 0, "key" => "L"]
];

$directionsHex = [
    "0" => "R",
    "1" => "D",
    "2" => "L",
    "3" => "U"
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $instruction = explode(" ", $line);
    $instructionHex = str_replace(["(#", ")"], "", $instruction[2]);
    $steps = hexdec(substr($instructionHex, 0, 5));
    $direction = substr($instructionHex, 5);
    $instructions[$index] =
        [
            "direction" => $directionsHex[$direction],
            "steps" => $steps,
            "color" => $instructionHex
        ];
}

// find ggt of steps to reduce map size
// echo print_r(array_column($instructions, "steps"), true);
// echo array_reduce(array_column($instructions, "steps"), 'gcd');

// echo print_r($instructions, true);
// return;

$x = 0;
$y = 0;
$map[$y][$x] = "#";

foreach ($instructions as $instruction) {
    $direction = $directions[$instruction["direction"]];
    $steps = $instruction["steps"];

    echo "x: $x, y: $y, direction: " . $direction["key"] . ", steps: $steps\n";

    for ($i = 0; $i < $steps; $i++) {
        $x += $direction["x"];
        $y += $direction["y"];
        $map[$y][$x] = "#";
    }
}
ksort($map);

// get map size
$maxY = max(array_keys($map));
$maxX = 0;
$minY = min(array_keys($map));
$minX = 0;
foreach ($map as $y => $row) {
    $maxX = max($maxX, max(array_keys($row)));
    $minX = min($minX, min(array_keys($row)));
}
echo "Map size: $minX, $minY ; $maxX, $maxY\n";

// fill map with .
for ($y = $minY; $y <= $maxY; $y++) {
    for ($x = $minX; $x <= $maxX; $x++) {
        if (!isset($map[$y][$x])) {
            $map[$y][$x] = "*";
        }
        ksort($map[$y]);
    }
}

// fill inner fields
// echo $maxX . " " . $maxY . "\n";
// for ($y = $minY; $y <= $maxY; $y++) {
//     for ($x = $minX; $x <= $maxX; $x++) {
//         if ($map[$y][$x] == ".") {
//             // check if is in field
//             // echo "x: $x, y: $y\n";
//             $countHash = 0;
//             $lastHash = false;
//             for ($i = $x - 1; $i >= $minX; $i--) {
//                 if ($map[$y][$i] == "#") {
//                     if (!$lastHash) {
//                         $countHash++;
//                     }
//                     $lastHash = true;
//                 } else {
//                     $lastHash = false;
//                 }
//             }
//             if ($countHash % 2 != 0) {
//                 $map[$y][$x] = "*";
//             }
//         }
//     }
// }

// loop over top & bottom borders
for ($i = $minX; $i <= $maxX; $i++) {
    if ($map[$minY][$i] != "#" && $map[$minY][$i] != ".") {
        markFreeTiles($i, $minY);
    }
    if ($map[$maxY][$i] != "#" && $map[$maxY][$i] != ".") {
        markFreeTiles($i, $maxY);
    }
}

// loop over left & right borders
for ($i = $minY; $i <= $maxY; $i++) {
    if ($map[$i][$minX] != "#" && $map[$i][$minX] != ".") {
        markFreeTiles($minX, $i);
    }
    if ($map[$i][$maxX] != "#" && $map[$i][$maxX] != ".") {
        markFreeTiles($maxX, $i);
    }
}

$countHash = 0;
for ($y = $minY; $y <= $maxY; $y++) {
    for ($x = $minX; $x <= $maxX; $x++) {
        if ($map[$y][$x] == "#" || $map[$y][$x] == "*") {
            $countHash++;
        }
    }
}

// echo print_r($instructions, true);
// echo print_r($map, true);

createMapTxtFile($map);

echo "Part 1: $countHash\n";

function createMapTxtFile($map)
{
    $file = fopen("output/18.txt", "w");
    foreach ($map as $y => $line) {
        fwrite($file, sprintf("%5s", $y) . " " . implode("", $line) . "\n");
    }
    fclose($file);
}

function markFreeTiles($x, $y)
{
    global $map, $directions, $maxX, $maxY, $minX, $minY;

    $map[$y][$x] = ".";
    foreach ($directions as $direction) {
        $newX = $x + $direction["x"];
        $newY = $y + $direction["y"];
        // is in border?
        if ($newX < $minX || $newY < $minY || $newX > $maxX || $newY > $maxY) {
            continue;
        }
        if ($map[$newY][$newX] != "#" && $map[$newY][$newX] != ".") {
            markFreeTiles($newX, $newY);
        }
    }
}

function gcd($a, $b)
{
    return $b ? gcd($b, $a % $b) : $a;
}
