<?php

$filepath = "input/2.txt";
$result = [];

// read input data
$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $game) {
    // remove Game x: from line
    $game = str_replace("Game " . ($index + 1) . ": ", "", $game);
    // split game on semi-colon
    $gameMoves = explode(";", $game);
    // loop over each move
    foreach ($gameMoves as $moveIndex => $move) {
        // split move on comma
        $moveContents = explode(",", $move);
        // loop over each type
        foreach ($moveContents as $value) {
            $value = trim($value);
            $valueSplit = explode(" ", $value);
            // assign color as key and value to result array
            $result[$index + 1][$moveIndex][$valueSplit[1]] = $valueSplit[0];
        }
    }

    // if ($index == 4)
    //     break;
}

// echo print_r($result, true);

// check which games would have been possible with 12 red, 13 green and 14 blue cubes
$sumOfPossibleGames = 0;
$colors = ["red" => 12, "green" => 13, "blue" => 14];
foreach ($result as $key => $game) {
    $possibleGame = true;
    foreach ($game as $moveIndex => $move) {
        if (!$possibleGame)
            break;
        foreach ($colors as $name => $maxAmount) {
            if (isset($move[$name]) && $move[$name] > $maxAmount) {
                // echo "Game " . $key . " is not possible because there are too many (" . $move[$name]  . ") " . $name . " cubes in move " . ($moveIndex + 1) . "\n";
                $possibleGame = false;
                break;
            }
        }
    }
    if ($possibleGame)
        $sumOfPossibleGames += $key;
}

echo "Part 1: " . $sumOfPossibleGames . "\n";

// get minimum amount of cubes needed for each color
$sumOfPossibleGames = 0;
foreach ($result as $key => $game) {
    $minColors = ["red" => 0, "green" => 0, "blue" => 0];
    foreach ($game as $moveIndex => $move) {
        foreach ($minColors as $name => $minAmount) {
            if (isset($move[$name]) && $move[$name] > $minAmount) {
                $minColors[$name] = $move[$name];
            }
        }
    }
    $sumOfPossibleGames += $minColors["red"] * $minColors["green"] * $minColors["blue"];
}

echo "Part 2: " . $sumOfPossibleGames . "\n";


// room for helper functions
