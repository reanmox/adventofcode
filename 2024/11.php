<?php

$filepath = "input/11.txt";
$stones = [];

$contents = file_get_contents($filepath);
$stones = explode(" ", $contents);

for ($i = 0; $i < 25; $i++) {
    $newStones = [];
    foreach ($stones as $s) {
        if ($s == 0) {
            $newStones[] = 1;
        } else if (strlen($s) % 2 == 0) {
            $middle = strlen($s) / 2;
            $newStones[] = substr($s, 0, $middle);
            $second = ltrim(substr($s, $middle), "0");
            $newStones[] = $second == "" ? 0 : $second;
        } else {
            $newStones[] = $s * 2024;
        }
    }
    $stones = $newStones;
    //echo implode(" ", $stones) . "\n";
}

echo "Part 1: " . count($stones) . "\n";
