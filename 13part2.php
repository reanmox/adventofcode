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
        $notes[$index]["horizontal"][$y] = $line;
        for ($x = 0; $x < strlen($line); $x++) {
            $vertical[$x] .= $line[$x];
        }
    }
    $notes[$index]["vertical"] = $vertical;
}

// echo print_r($notes[0]["vertical"], true);

$sum = 0;
foreach ($notes as $index => $note) {
    // echo "Note horizontal $index\n";
    $sumHorizontal = findReflection($note["horizontal"]);
    // echo "Note vertical $index\n";
    $sumVertical = findReflection($note["vertical"]);
    if ($sumHorizontal !== false) {
        $sum += ($sumHorizontal + 1) * 100;
    } else if ($sumVertical !== false) {
        $sum += $sumVertical + 1;
    } else {
        // echo "Note horizontal $index\n";
        $sumHorizontal = findReflection($note["horizontal"], true);
        // echo "Note vertical $index\n";
        $sumVertical = findReflection($note["vertical"], true);
        if ($sumHorizontal !== false) {
            $sum += ($sumHorizontal + 1) * 100;
        } else if ($sumVertical !== false) {
            $sum += $sumVertical + 1;
        } else {
            echo "No reflection found";
        }
    }
}
echo "Task 2: $sum\n";

function findReflection($note, $oneDifferentCharAllowed = false)
{
    $reflections = [];
    for ($i = 0; $i < count($note) - 1; $i++) {
        if (!$oneDifferentCharAllowed && $note[$i] == $note[$i + 1]) {
            $reflections[] = $i;
        } else if ($oneDifferentCharAllowed && stringOnlyHasOneDifferentCharacter($note[$i], $note[$i + 1])) {
            $reflections[] = $i;
        }
    }
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
        if (!$perfect) {
            // echo "Reflection at $reflection\n";
            // check if reflection can be fixed
            if (canBeFixed($note, $reflection)) {
                // echo "Can be fixed $reflection\n";
                return $reflection;
            } else {
                // echo "Can't be fixed$reflection\n";
            }
        }
    }
    return false;
}

function canBeFixed($note, $reflection)
{
    $bonus = true;
    for ($i = 0; $i < count($note); $i++) {
        if ($i + 1 + $reflection >= count($note) || $reflection - $i < 0) {
            break;
        }
        if ($note[$reflection - $i] != $note[$reflection + $i + 1]) {
            if (stringOnlyHasOneDifferentCharacter($note[$reflection - $i], $note[$reflection + $i + 1])) {
                if ($bonus) {
                    $bonus = false;
                } else return false;
            } else
                return false;
        }
    }
    return true;
}

function stringOnlyHasOneDifferentCharacter($string1, $string2)
{
    $different = false;
    for ($i = 0; $i < strlen($string1); $i++) {
        if ($string1[$i] != $string2[$i]) {
            if ($different) {
                return false;
            }
            $different = true;
        }
    }
    return $different;
}
