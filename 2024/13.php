<?php

$filepath = "input/13.txt";
$machines = [];

$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);

foreach ($contents as $machine) {
    $parts = explode("\n", $machine);
    $a = explode(",", explode(":", $parts[0])[1]);
    $b = explode(",", explode(":", $parts[1])[1]);
    $p = explode(",", explode(":", $parts[2])[1]);
    $machines[] =
        [
            "a" => ["x" => explode("+", $a[0])[1], "y" => explode("+", $a[1])[1]],
            "b" => ["x" => explode("+", $b[0])[1], "y" => explode("+", $b[1])[1]],
            "p" => ["x" => explode("=", $p[0])[1], "y" => explode("=", $p[1])[1]],
        ];
}

// print_r($machines);

$tokens = 0;

foreach ($machines as $i => $m) {
    $ax = $m["a"]["x"];
    $ay = $m["a"]["y"];
    $bx = $m["b"]["x"];
    $by = $m["b"]["y"];
    $px = $m["p"]["x"];
    $py = $m["p"]["y"];

    $cheapest = 0;
    for ($A = 0; $A <= 100; $A++) {
        for ($B = 0; $B <= 100; $B++) {
            if ($A * $ax + $B * $bx == $px && $A * $ay + $B * $by == $py) {
                if ($cheapest == 0) {
                    $cheapest = $A * 3 + $B;
                } else if ($A * 3 + $B < $cheapest) {
                    echo "Machine $i: A: $A, B: $B\n";
                    $cheapest = $A * 3 + $B;
                }
            }
        }
    }
    $tokens += $cheapest;
}

echo "Part 1: $tokens\n";

// x => A*94 + B*22 = 8400
// y => A*34 + B*67 = 5400, B = (5400 - A*34) / 67
// A = (8400 - ((5400 - A*34) / 67) * 22) / 94
// $A = ($px - (($py - $ay) / $by) * $bx) / $ax;