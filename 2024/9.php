<?php

$filepath = "input/9.txt";
$map = [];

$contents = file_get_contents($filepath);
$map = str_split($contents);

$id = 0;
$blocks = [];
$freeSpaces = [];

foreach ($map as $key => $value) {
    $isFree = $key % 2 == 1;

    for ($i = 0; $i < $value; $i++) {
        $blocks[] = $isFree ? "." : $id;
        if ($isFree) {
            $freeSpaces[] = count($blocks) - 1;
        }
    }

    if (!$isFree) {
        $id++;
    }
}

for ($i = count($blocks) - 1; $i >= 0; $i--) {
    if ($blocks[$i] != ".") {
        $freePos = array_shift($freeSpaces);
        if ($freePos === null || $freePos > $i) {
            break;
        }
        $blocks[$freePos] = $blocks[$i];
        $blocks[$i] = ".";
    }
}

$sum = 0;
foreach ($blocks as $key => $value) {
    $sum += $value == "." ? 0 : $value * $key;
}
echo "Part 1: $sum\n";
