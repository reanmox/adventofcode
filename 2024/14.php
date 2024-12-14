<?php

$filepath = "input/14.txt";
$robots = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $line) {
    $parts = explode(" ", $line);
    $robots[] = [
        "p" => explode(",", explode("=", $parts[0])[1]),
        "v" => explode(",", explode("=", $parts[1])[1]),
    ];
}

$seconds = 100;
$q1 = 0;
$q2 = 0;
$q3 = 0;
$q4 = 0;

$w = 101;
$h = 103;

$w_mid = floor($w / 2);
$h_mid = floor($h / 2);

foreach ($robots as &$robot) {
    $robot["pnew"] = [
        ($robot["p"][0] + $robot["v"][0] * $seconds) % $w,
        ($robot["p"][1] + $robot["v"][1] * $seconds) % $h,
    ];
    if ($robot["pnew"][0] < 0) {
        $robot["pnew"][0] += $w;
    }
    if ($robot["pnew"][1] < 0) {
        $robot["pnew"][1] += $h;
    }

    if ($robot["pnew"][0] < $w_mid && $robot["pnew"][1] < $h_mid) {
        $q1++;
    } elseif ($robot["pnew"][0] > $w_mid && $robot["pnew"][1] < $h_mid) {
        $q2++;
    } elseif ($robot["pnew"][0] < $w_mid && $robot["pnew"][1] > $h_mid) {
        $q3++;
    } elseif ($robot["pnew"][0] > $w_mid && $robot["pnew"][1] > $h_mid) {
        $q4++;
    }
}

$result = $q1 * $q2 * $q3 * $q4;
echo "Part1: $result ($q1 $q2 $q3 $q4)\n";
