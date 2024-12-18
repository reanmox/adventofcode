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

$w = 101;
$h = 103;
$w_mid = floor($w / 2);
$h_mid = floor($h / 2);

$map = [];

// repeats after 101 * 103 = 10403
for ($seconds = 0; $seconds <= 10403; $seconds++) {

    $q1 = 0;
    $q2 = 0;
    $q3 = 0;
    $q4 = 0;
    $topMid = false;
    $bottomMid = false;
    $mid = 0;

    foreach ($robots as &$robot) {
        $robot["pnew"] = [];
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

        if ($robot["pnew"][0] == $w_mid && $robot["pnew"][1] == 0) {
            $topMid = true;
        }
        if ($robot["pnew"][0] == $w_mid && $robot["pnew"][1] == $h - 1) {
            $bottomMid = true;
        }
        if ($robot["pnew"][0] == $w_mid) {
            $mid++;
        }
    }

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $map[$y][$x] = ".";
        }
    }
    foreach ($robots as $r) {
        $y = $r["pnew"][1];
        $x = $r["pnew"][0];
        $map[$y][$x] = "#";
    }
    $foundExit = false;

    // look for connected robots
    for ($y = 0; $y < $h; $y++) {
        $lineString = implode("", $map[$y]);
        if (str_contains($lineString, "###############")) {
            $foundExit = true;
            break;
        }
    }

    if ($foundExit) {
        echo "Part2: $seconds, $mid, ($q1 $q2 $q3 $q4)\n";
        createMapTxtFile($map);
        break;
    }
}

function createMapTxtFile($map)
{
    $file = fopen("output/14.txt", "w");
    foreach ($map as $line) {
        fwrite($file, implode("", $line) . "\n");
    }
    fclose($file);
}
