<?php

$filepath = "input/3.txt";
$result = [];
$gears = [];
$lineLength = 0;

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

// testing
// $contents = ["5...9", "%.230", "70..%"];

foreach ($contents as $index => $line) {
    if ($index == 0) {
        $lineLength = strlen($line);
    }
    $result[$index] = str_split($line);
}

$sum = 0;
foreach ($result as $index => $line) {
    $currentNumber = "";
    foreach ($line as $charIndex => $char) {
        if ($char == ".") {
            if ($currentNumber != "") {
                // echo $currentNumber . "\n";
                if (checkIfNumberIsValid($result, $charIndex - strlen($currentNumber), $charIndex - 1, $index, intval($currentNumber))) {
                    $sum += intval($currentNumber);
                } else {
                    // echo "invalid number on line " . ($index + 1) . ": " . $currentNumber . "\n";
                }
                $currentNumber = "";
            }
        } else if (is_numeric($char)) {
            $currentNumber .= $char;
            if ($charIndex == $lineLength - 1) {
                // echo $currentNumber . "\n";
                if (checkIfNumberIsValid($result, $charIndex - strlen($currentNumber) + 1, $charIndex, $index, intval($currentNumber))) {
                    $sum += intval($currentNumber);
                } else {
                    // echo "invalid number on line " . ($index + 1) . ": " . $currentNumber . "\n";
                }
                $currentNumber = "";
            }
        } else {
            // symbol
            if ($currentNumber != "") {
                // echo $currentNumber . "\n";
                if (checkIfNumberIsValid($result, $charIndex - strlen($currentNumber), $charIndex - 1, $index, intval($currentNumber))) {
                    $sum += intval($currentNumber);
                } else {
                    // echo "invalid number on line " . ($index + 1) . ": " . $currentNumber . "\n";
                }
                $currentNumber = "";
            }
        }
    }

    // if ($index == 2) break;
}

echo "Part 1: " . $sum . "\n";

// echo print_r($result, true);
// echo print_r($gears, true);

$gearSum = 0;
foreach ($gears as $gear => $numbers) {
    if (count($numbers) == 2) {
        $gearSum += $numbers[0] * $numbers[1];
    }
}

echo "Part 2: " . $gearSum . "\n";

// room for helper functions

function checkIfNumberIsValid($map, $xStart, $xEnd, $y, $currentNumber)
{
    global $lineLength;
    global $gears;
    // echo $xStart . " " . $xEnd . " " . $y . "\n";

    $isValid = false;
    for ($i = $xStart; $i <= $xEnd; $i++) {

        if ($i == $xStart && $i != 0) {
            $x = $i - 1;
            // check left neighbor
            if ($map[$y][$x] != "." && !is_numeric($map[$y][$x])) {
                $isValid = true;
                if ($map[$y][$x] == "*")
                    $gears["$y.$x"][] = $currentNumber;
            }
            // check top left neighbor
            if ($y > 0 && $map[$y - 1][$x] != "." && !is_numeric($map[$y - 1][$x])) {
                $isValid = true;
                if ($map[$y - 1][$x] == "*")
                    $gears[($y - 1) . ".$x"][] = $currentNumber;
            }
            // check bottom left neighbor
            if ($y < count($map) - 1 && $map[$y + 1][$x] != "." && !is_numeric($map[$y + 1][$x])) {
                $isValid = true;
                if ($map[$y + 1][$x] == "*")
                    $gears[($y + 1) . ".$x"][] = $currentNumber;
            }
        }
        if ($i == $xEnd && $i != $lineLength - 1) {
            $x = $i + 1;
            // check right neighbor
            if ($map[$y][$x] != "." && !is_numeric($map[$y][$x])) {
                $isValid = true;
                if ($map[$y][$x] == "*")
                    $gears["$y.$x"][] = $currentNumber;
            }
            // check top right neighbor
            if ($y > 0 && $map[$y - 1][$x] != "." && !is_numeric($map[$y - 1][$x])) {
                $isValid = true;
                if ($map[$y - 1][$x] == "*")
                    $gears[($y - 1) . ".$x"][] = $currentNumber;
            }
            // check bottom right neighbor
            if ($y < count($map) - 1 && $map[$y + 1][$x] != "." && !is_numeric($map[$y + 1][$x])) {
                $isValid = true;
                if ($map[$y + 1][$x] == "*")
                    $gears[($y + 1) . ".$x"][] = $currentNumber;
            }
        }

        // check top neighbor
        if ($y > 0 && $map[$y - 1][$i] != "." && !is_numeric($map[$y - 1][$i])) {
            $isValid = true;
            if ($map[$y - 1][$i] == "*")
                $gears[($y - 1) . ".$i"][] = $currentNumber;
        }
        // check bottom neighbor
        if ($y < count($map) - 1 && $map[$y + 1][$i] != "." && !is_numeric($map[$y + 1][$i])) {
            $isValid = true;
            if ($map[$y + 1][$i] == "*")
                $gears[($y + 1) . ".$i"][] = $currentNumber;
        }
    }

    return $isValid;
}
