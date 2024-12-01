<?php

$filepath = "input/13.txt";
$notes = [];

$contents = file_get_contents($filepath);

// $contents = "#.##..##.
// ..#.##.#.
// ##......#
// ##......#
// ..#.##.#.
// ..##..##.
// #.#.##.#.

// #...##..#
// #....#..#
// ..##..###
// #####.##.
// #####.##.
// ..##..###
// #....#..#";

// $contents = "...#..#
// ...#..#
// ##..##.
// .#.##.#
// ..#..##
// #.#.##.
// #.#.###
// ##.##..
// ##.##..
// #.#.###
// ..#.##.
// ..#..##
// .#.##.#
// ##..##.
// ...#..#";

$contents = explode("\n\n", $contents);

foreach ($contents as $index => $note) {
    $note = explode("\n", $note);
    $vertical = array_fill(0, strlen($note[0]), "");
    foreach ($note as $y => $line) {
        $notes[$index]["horizontal"][$y][] = $line;
        for ($x = 0; $x < strlen($line); $x++) {
            $vertical[$x] .= $line[$x];
        }
    }
    $notes[$index]["vertical"] = $vertical;
}

$sum = 0;
foreach ($notes as $note) {
    $sumHorizontal = findReflection($note["horizontal"]);
    $sumVertical = findReflection($note["vertical"]);
    if ($sumHorizontal !== false) {
        $sum += ($sumHorizontal + 1) * 100;
    } else if ($sumVertical !== false) {
        $sum += $sumVertical + 1;
    } else {
        echo "No reflection found\n";
    }
}
echo "Task 1: $sum\n";

// findReflection($notes[0]["horizontal"]);
// findReflection($notes[5]["vertical"]);
// echo print_r($notes[0]["vertical"], true);

// room for helper functions
function findReflection($note)
{
    $reflections = [];
    for ($i = 0; $i < count($note) - 1; $i++) {
        if ($note[$i] == $note[$i + 1]) {
            $reflections[] = $i;
        }
    }
    echo print_r($reflections, true);
    // check if the reflection is "perfect"
    foreach ($reflections as $reflection) {
        $perfect = true;
        for ($i = 0; $i < count($note); $i++) {
            if ($i + 1 + $reflection >= count($note) || $reflection - $i < 0) {
                break;
            }
            if ($note[$reflection - $i] != $note[$reflection + $i + 1]) {
                $perfect = false;
                break;
            }
        }
        if ($perfect) {
            echo "Perfect reflection at $reflection\n";
            return $reflection;
        }
    }
    return false;
}
