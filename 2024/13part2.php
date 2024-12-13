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
            "p" => ["x" => explode("=", $p[0])[1] + 10000000000000, "y" => explode("=", $p[1])[1] + 10000000000000],
        ];
}

$tokens = 0;

foreach ($machines as $i => $m) {
    $ax = $m["a"]["x"];
    $ay = $m["a"]["y"];
    $bx = $m["b"]["x"];
    $by = $m["b"]["y"];
    $px = $m["p"]["x"];
    $py = $m["p"]["y"];

    // x => 94A + 22B = 8400
    // y => 34A + 67B = 5400 <=> B = (5400 - 34A) / 67

    // 94A + 22 * (5400 - 34A) / 67 = 8400
    // <=> 94A * 67 + 118800 - 748A = 8400 * 67
    // <=> 5550A = 444000
    // <=> A = 80

    // axA + bx * (py - ayA) / by = px
    // <=> axbyA + bxpy - bxayA = bypx
    // <=> A(axby - bxay) = bypx - bxpy
    // <=> A = (bypx - bxpy) / (axby - bxay)
    $A = ($by * $px - $bx * $py) / ($ax * $by - $bx * $ay);

    // B = (5400 - 34 * 80) / 67 = 40
    // B = (py - ayA) / by
    $B = ($py - $ay * $A) / $by;

    if (fmod($A, 1) == 0.00 && fmod($B, 1) == 0.00) {
        $tokens += $A * 3 + $B;
    }
}

echo "Part 2: $tokens\n";
