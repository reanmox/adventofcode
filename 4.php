<?php

$filepath = "input/4.txt";
$result = [];

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $line = str_replace("  ", " ", $line);
    $game = explode("|", trim(explode(":", $line)[1]));
    $result[$index] = ["winning" => explode(" ", trim($game[0])), "mynumbers" => explode(" ", trim($game[1]))];

    // if ($index == 0)
    //     break;
}

$sum = 0;
foreach ($result as $index => $game) {
    $matches = count(array_intersect($game["winning"], $game["mynumbers"]));
    if ($matches > 0)
        $sum += 1 * pow(2, $matches - 1);
}
echo "Task 1: " . $sum . "\n";

$cardCount = [];
for ($i = 0; $i < count($result); $i++) {
    $cardCount[$i] = 1;
}
foreach ($result as $index => $game) {
    $matches = count(array_intersect($game["winning"], $game["mynumbers"]));
    for ($i = 1; $i <= $matches; $i++) {
        $cardCount[$index + $i] += 1 * $cardCount[$index];
    }
}
echo print_r($cardCount, true) . "\n";
echo "Task 2: " . array_sum($cardCount) . "\n";

// room for helper functions
