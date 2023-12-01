<?php

function getLastValidString($numbers, $currentString)
{
    while (strlen($currentString) > 0) {
        $currentString = substr($currentString, 1, strlen($currentString));
        foreach ($numbers as $number) {
            if (strlen($currentString) <= strlen($number) && substr($number, 0, strlen($currentString)) === $currentString) {
                return $currentString;
            }
        }
    }
    return $currentString;
}

// read 1.txt
$fp = fopen("input/1.txt", "r");
$contents = fread($fp, filesize("input/1.txt"));
fclose($fp);

$result = [];

// split content on double new line
$contents = explode("\n", $contents);

$numbers = ["one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];

// testing array
//$contents = ["two1nine", "eightwothree", "abcone2threexyz", "xtwone3four", "4nineeightseven2", "zoneight234", "7pqrstsixteen"];
//$contents = ["six", "onsix", "sevenine", "eighthree", "eight3sevenine", "fouroneqmffoursix9eightwo1kv", "55", "acaseighthreesvdsd"];

// loop over each line
foreach ($contents as $key => $line) {
    $firstDigit = 0;
    $lastDigit = 0;
    $boolIsFirstDigit = true;
    $currentString = "";

    // testing
    // $line = "fkxxqxdfsixgthreepvzjxrkcfk6twofour";

    // loop over each char of line
    foreach (str_split($line) as $char) {
        // if char is a number
        if (is_numeric($char)) {
            // add to result
            if ($boolIsFirstDigit) {
                $firstDigit = intval($char);
                $boolIsFirstDigit = false;
            }
            $lastDigit = intval($char);

            $currentString = "";
        } else {
            $currentString .= $char;
            //echo $currentString . "\n";
            // check if current string starts like on of the numbers
            $match = false;
            foreach ($numbers as $value => $number) {
                if (strlen($currentString) <= strlen($number) && substr($number, 0, strlen($currentString)) === $currentString) {
                    $match = true;
                }
                if ($currentString == $number) {
                    // add to result
                    if ($boolIsFirstDigit) {
                        $firstDigit = $value + 1;
                        $boolIsFirstDigit = false;
                    }
                    $lastDigit = $value + 1;
                    $currentString = getLastValidString($numbers, $currentString);
                }
            }
            if (!$match) {
                $currentString = getLastValidString($numbers, $currentString);
            }
        }
    }

    $result[] = intval($firstDigit . "" . $lastDigit);
    //echo $line . " " . $firstDigit . " " . $lastDigit . "\n";
    // if ($key == 100) {
    //     break;
    // }
}

echo array_sum($result) . "\n";
