<?php

$filepath = "input/3.txt";
$instructions = [];

$contents = file_get_contents($filepath);
preg_match_all('/mul\(\d{1,3},\d{1,3}\)/', $contents, $instructions);

$sum = 0;
foreach ($instructions[0] as $instruction) {
    $instruction = str_replace(['mul(', ')'], '', $instruction);
    $instruction = explode(',', $instruction);
    $sum += $instruction[0] * $instruction[1];
}

//echo print_r($instructions, true);

echo "Part 1: $sum\n";
