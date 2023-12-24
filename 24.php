<?php

$filepath = "input/24.txt";
$hailstones = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $line = explode(" @ ", $line);
    $pos = explode(",", $line[0]);
    $vel = explode(",", $line[1]);
    $hailstone = [
        "x" => $pos[0],
        "y" => $pos[1],
        "z" => $pos[2],
        "vx" => $vel[0],
        "vy" => $vel[1],
        "vz" => $vel[2]
    ];
    $hailstone["m"] = $hailstone["vy"] / $hailstone["vx"];
    $hailstone["b"] = $hailstone["y"] - $hailstone["m"] * $hailstone["x"];
    $hailstones[$index] = $hailstone;
}

echo print_r($hailstones, true);

// $minBorder = 7;
// $maxBorder = 27;

$minBorder = 200000000000000;
$maxBorder = 400000000000000;

$countCollisions = 0;
foreach ($hailstones as $index => $hailstone) {
    foreach ($hailstones as $index2 => $hailstone2) {
        if ($index2 <= $index) {
            continue;
        }
        // m2x2 + b2 = mx + b
        // x = (b - b2) / (m2 - m)
        if ($hailstone["m"] == $hailstone2["m"]) {
            continue;
        }
        $x = ($hailstone["b"] - $hailstone2["b"]) / ($hailstone2["m"] - $hailstone["m"]);
        $y = $hailstone["m"] * $x + $hailstone["b"];
        // echo "x: $x, y: $y\n";

        if ($hailstone["vx"] > 0 && $x < $hailstone["x"] || $hailstone["vx"] < 0 && $x > $hailstone["x"]) {
            continue;
        }
        if ($hailstone["vy"] > 0 && $y < $hailstone["y"] || $hailstone["vy"] < 0 && $y > $hailstone["y"]) {
            continue;
        }
        if ($hailstone2["vx"] > 0 && $x < $hailstone2["x"] || $hailstone2["vx"] < 0 && $x > $hailstone2["x"]) {
            continue;
        }
        if ($hailstone2["vy"] > 0 && $y < $hailstone2["y"] || $hailstone2["vy"] < 0 && $y > $hailstone2["y"]) {
            continue;
        }

        if ($x >= $minBorder && $x <= $maxBorder && $y >= $minBorder && $y <= $maxBorder) {
            $countCollisions++;
            echo "Collision at x: $x, y: $y\n";
        }
    }
}
echo "Task 1: $countCollisions\n";
