<?php

$filepath = "input/11.txt";
$stones = [];

$contents = file_get_contents($filepath);
$stones = explode(" ", $contents);

$newStones = [];
foreach ($stones as $s) {
    $newStones[$s] = 1;
}
$stones = $newStones;

for ($i = 0; $i < 75; $i++) {
    $newStones = [];
    foreach ($stones as $s => $amount) {
        if ($s == 0) {
            if (!isset($newStones[1])) $newStones[1] = $amount;
            else $newStones[1] += $amount;
        } else if (strlen($s) % 2 == 0) {
            $middle = strlen($s) / 2;
            $first = substr($s, 0, $middle);
            if (!isset($newStones[$first])) $newStones[$first] = $amount;
            else $newStones[$first] += $amount;
            $second = ltrim(substr($s, $middle), "0");
            $second = $second == "" ? 0 : $second;
            if (!isset($newStones[$second])) $newStones[$second] = $amount;
            else $newStones[$second] += $amount;
        } else {
            if (!isset($newStones[$s * 2024])) $newStones[$s * 2024] = $amount;
            else $newStones[$s * 2024] += $amount;
        }
    }
    $stones = $newStones;
    // echo "$i " . array_sum($stones) . " " . implode($stones) . "\n";
}

echo "Part 1: " . array_sum($stones) . "\n";
