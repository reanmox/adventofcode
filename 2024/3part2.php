<?php

$filepath = "input/3.txt";
$instructions = [];

$contents = file_get_contents($filepath);
preg_match_all("/mul\(\d{1,3},\d{1,3}\)|do\(\)|don't\(\)/", $contents, $instructions);

$sum = 0;
$enabled = true;
foreach ($instructions[0] as $instruction) {

    if ($instruction == "do()") {
        $enabled = true;
        continue;
    } elseif ($instruction == "don't()") {
        $enabled = false;
        continue;
    }

    if (!$enabled) {
        continue;
    }

    $instruction = str_replace(['mul(', ')'], '', $instruction);
    $instruction = explode(',', $instruction);
    $sum += $instruction[0] * $instruction[1];
}

// echo print_r($instructions, true);

echo "Part 2: $sum\n";
