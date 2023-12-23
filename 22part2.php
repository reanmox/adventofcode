<?php

$filepath = "input/22.txt";
$room = [];
$rocks = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $positions = explode("~", $line);
    $start = explode(",", $positions[0]);
    $end = explode(",", $positions[1]);

    $startPos = [
        "x" => $start[0],
        "y" => $start[1],
        "z" => $start[2]
    ];

    $endPos = [
        "x" => $end[0],
        "y" => $end[1],
        "z" => $end[2]
    ];

    $rock = [
        "start" => $startPos,
        "end" => $endPos,
        "z" => $startPos["z"] < $endPos["z"] ? $startPos["z"] : $endPos["z"],
        "expansion" => $startPos["x"] == $endPos["x"] ? ($startPos["y"] == $endPos["y"] ? "z" : "y")  : "x",
        "id" => $index
    ];
    $rocks[] = $rock;
}

usort($rocks, function ($a, $b) {
    return $a["z"] <=> $b["z"];
});

$hasFallen = true;
while ($hasFallen) {
    $hasFallen = false;
    $room = createRoomByRocks($rocks);
    foreach ($rocks as $index => &$rock) {
        if (!canRockFall($rock)) {
            continue;
        }
        $rock["start"]["z"]--;
        $rock["end"]["z"]--;
        $hasFallen = true;
    }
}

$room = createRoomByRocks($rocks);

foreach ($rocks as &$rock) {
    $rock["supportedRocks"] = getSupportedRocks($rock);
    $rock["basedRocks"] = getBasedRocks($rock);
}

$sumCanBeRemoved = 0;

foreach ($rocks as &$rock) {
    if (count($rock["supportedRocks"]) == 0) {
        $sumCanBeRemoved++;
        continue;
    } else {
        $supportedRocks = [];
        foreach ($rock["supportedRocks"] as $supportedRock) {
            foreach ($rocks as $rock2) {
                if ($rock2["id"] != $rock["id"]) {
                    if (in_array($supportedRock, $rock2["supportedRocks"]) && !in_array($supportedRock, $supportedRocks)) {
                        $supportedRocks[] = $supportedRock;
                    }
                }
            }
        }
        if (count($supportedRocks) == count($rock["supportedRocks"])) {
            $sumCanBeRemoved++;
        } else {
            $rock["fallingRocks"] = array_diff($rock["supportedRocks"], $supportedRocks);
        }
    }
}

// echo print_r($rocks, true);
echo "Task 1: $sumCanBeRemoved\n";

$sumFallingRocks = 0;
foreach ($rocks as &$rock) {
    echo "Rock: " . $rock["id"] . "\n";
    $currentRocks = array_fill(0, count($rocks), true);
    unset($currentRocks[$rock["id"]]);
    $fallingRocks = 0;
    foreach ($rocks as $rock2) {
        if ($rock2["id"] == $rock["id"]) {
            continue;
        }
        $intersect = array_intersect($rock2["basedRocks"], array_keys($currentRocks));
        if (isset($rock2["basedRocks"]) && count($rock2["basedRocks"]) > 0 && count($intersect) == 0) {
            unset($currentRocks[$rock2["id"]]);
            $fallingRocks++;
        }
    }
    $sumFallingRocks += $fallingRocks;
}
echo "Task 2: $sumFallingRocks\n";

function canRockFall($rock)
{
    global $room;
    if ($rock["start"]["z"] == 1 || $rock["end"]["z"] == 1) {
        return false;
    }
    if ($rock["expansion"] == "z") {
        $pos = $rock["start"]["z"] < $rock["end"]["z"] ? $rock["start"] : $rock["end"];
        if (isset($room[$pos["x"]][$pos["y"]][$pos["z"] - 1])) {
            return false;
        }
        return true;
    }
    for ($x = $rock["start"]["x"]; $x <= $rock["end"]["x"]; $x++) {
        for ($y = $rock["start"]["y"]; $y <= $rock["end"]["y"]; $y++) {
            for ($z = $rock["start"]["z"]; $z <= $rock["end"]["z"]; $z++) {
                if (isset($room[$x][$y][$z - 1])) {
                    return false;
                }
            }
        }
    }
    return true;
}

function getSupportedRocks($rock)
{
    global $room;
    $supportedRocks = [];
    for ($x = $rock["start"]["x"]; $x <= $rock["end"]["x"]; $x++) {
        for ($y = $rock["start"]["y"]; $y <= $rock["end"]["y"]; $y++) {
            for ($z = $rock["start"]["z"]; $z <= $rock["end"]["z"]; $z++) {
                if (isset($room[$x][$y][$z + 1]) && $room[$x][$y][$z + 1] != $rock["id"] && !in_array($room[$x][$y][$z + 1], $supportedRocks)) {
                    $supportedRocks[] = $room[$x][$y][$z + 1];
                }
            }
        }
    }
    return $supportedRocks;
}

function getBasedRocks($rock)
{
    global $room;
    $basedRocks = [];
    for ($x = $rock["start"]["x"]; $x <= $rock["end"]["x"]; $x++) {
        for ($y = $rock["start"]["y"]; $y <= $rock["end"]["y"]; $y++) {
            for ($z = $rock["start"]["z"]; $z <= $rock["end"]["z"]; $z++) {
                if (isset($room[$x][$y][$z - 1]) && $room[$x][$y][$z - 1] != $rock["id"] && !in_array($room[$x][$y][$z - 1], $basedRocks)) {
                    $basedRocks[] = $room[$x][$y][$z - 1];
                }
            }
        }
    }
    return $basedRocks;
}

function createRoomByRocks($rocks)
{
    $room = [];
    foreach ($rocks as $rock) {
        for ($x = $rock["start"]["x"]; $x <= $rock["end"]["x"]; $x++) {
            for ($y = $rock["start"]["y"]; $y <= $rock["end"]["y"]; $y++) {
                for ($z = $rock["start"]["z"]; $z <= $rock["end"]["z"]; $z++) {
                    $room[$x][$y][$z] = $rock["id"];
                }
            }
        }
    }
    return $room;
}
