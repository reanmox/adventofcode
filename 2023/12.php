<?php

$filepath = "input/12.txt";
$result = [];

// 114564842144821 too low
$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

// $contents = [
//     "???.### 1,1,3",
//     ".??..??...?##. 1,1,3",
//     "?#?#?#?#?#?#?#? 1,3,1,6",
//     "????.#...#... 4,1,1",
//     "????.######..#####. 1,6,5",
//     "?###???????? 3,2,1",
// ];

$totalSum = 0;
$totalSumTask2 = 0;
foreach ($contents as $index => $line) {
    echo $index . " " . $line . "\n";
    $parts = explode(" ", $line);
    $result[$index] = [
        "springs" => $parts[0],
        "springsTask2" => getSpringsTask2($parts[0], explode(",", $parts[1])),
        "groups" => explode(",", $parts[1]),
        "possibilities" => [],
        "possibilitiesTask2" => [],
        "count" => 0,
        "countTask2" => 0
    ];
    generateAllPossibilities($parts[0], 0, $result[$index]["possibilities"]);
    generateAllPossibilities($result[$index]["springsTask2"], 0, $result[$index]["possibilitiesTask2"]);
    $sum = 0;
    foreach ($result[$index]["possibilities"] as $possibilitie) {
        $sum += validatePossibilitie($possibilitie, $result[$index]["groups"]) ? 1 : 0;
    }
    $result[$index]["count"] = $sum;
    $sumTask2 = 0;
    foreach ($result[$index]["possibilitiesTask2"] as $possibilitie) {
        $sumTask2 += validatePossibilitie($possibilitie, $result[$index]["groups"]) ? 1 : 0;
    }
    $result[$index]["countTask2"] = $sumTask2;
    $totalSum += $sum;
    $totalSumTask2 += $sum * $sumTask2 * $sumTask2 * $sumTask2 * $sumTask2;

    // clean possibilities
    $result[$index]["possibilities"] = [];
    $result[$index]["possibilitiesTask2"] = [];

    // echo $parts[0] . "\n";
    // echo $result[$index]["springsTask2"] . "\n";
    // echo "Task 1: " . $sum . "\n";
    // echo "Task 2: " . $sum * $sumTask2 * $sumTask2 * $sumTask2 * $sumTask2 . "\n";
}

// echo print_r($result[5]["possibilities"], true);
echo "Task 1: " . $totalSum . "\n";
echo "Task 2: " . $totalSumTask2 . "\n";

function generateAllPossibilities($springs, $index = 0, &$possibilities = [])
{
    if ($index == strlen($springs)) {
        $possibilities[] = $springs;
        return;
    }

    if ($springs[$index] == '?') {
        $springs[$index] = '.';
        generateAllPossibilities($springs, $index + 1, $possibilities);

        $springs[$index] = '#';
        generateAllPossibilities($springs, $index + 1, $possibilities);

        $springs[$index] = '?';
    } else {
        generateAllPossibilities($springs, $index + 1, $possibilities);
    }
}

function validatePossibilitie($possibilitie, $groups)
{
    $hashCount = 0;
    $hashGroups = [];

    for ($i = 0; $i < strlen($possibilitie); $i++) {
        if ($possibilitie[$i] == '#') {
            $hashCount++;
        } else if ($hashCount > 0) {
            $hashGroups[] = $hashCount;
            $hashCount = 0;
        }
    }

    if ($hashCount > 0) {
        $hashGroups[] = $hashCount;
    }

    if ($hashGroups == $groups) {
        return true;
    }
    return false;
}

function getSpringsTask2($springs, $groups)
{
    if ($springs[strlen($springs) - 1] == ".") {
        return "?" . $springs;
    } else if ($springs[strlen($springs) - 1] == "#") {
        return $springs;
        return str_repeat("#", $groups[count($groups) - 1]) . "?" . $springs;
    } else if ($springs[strlen($springs) - 1] == "?") {
        return "?" . $springs . "?";
    }
}
