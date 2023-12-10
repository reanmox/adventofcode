<?php

$filepath = "input/10.txt";
$map = [];
$visual = [];

$pipes = [
    "|" => ["N", "S"],
    "-" => ["E", "W"],
    "L" => ["N", "E"],
    "J" => ["N", "W"],
    "7" => ["S", "W"],
    "F" => ["S", "E"],
    "S" => ["S", "N"]
];

$directions = [
    "N" => ["x" => 0, "y" => -1, "opposite" => "S"],
    "S" => ["x" => 0, "y" => 1, "opposite" => "N"],
    "E" => ["x" => 1, "y" => 0, "opposite" => "W"],
    "W" => ["x" => -1, "y" => 0, "opposite" => "E"]
];

$isLeftOutsidePoV = true;
$borderModel = [
    "N" =>
    [
        "N" => ["W" => "outside", "E" => "inside"],
        "E" => ["N" => "outside", "W" => "outside"],
        "W" => ["N" => "inside", "E" => "inside"],
    ],
    "S" =>
    [
        "S" => ["W" => "inside", "E" => "outside"],
        "E" => ["S" => "inside", "W" => "inside"],
        "W" => ["S" => "outside", "E" => "outside"],
    ],
    "E" =>
    [
        "E" => ["N" => "outside", "S" => "inside"],
        "N" => ["E" => "inside", "S" => "inside"],
        "S" => ["E" => "outside", "N" => "outside"],
    ],
    "W" =>
    [
        "W" => ["N" => "inside", "S" => "outside"],
        "N" => ["W" => "outside", "S" => "outside"],
        "S" => ["W" => "inside", "N" => "inside"],
    ]
];

$x = 0;
$y = 0;
$path = [];
$pathInsideOutside = [];

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $split = str_split($line);
    if (str_contains($line, "S")) {
        $y = $index;
        $x = array_search("S", $split);
        $path[] = "S";
    }
    $map[] = $split;
}
$visual = $map;
$pathInsideOutside;

echo "Start position: " . $x . ", " . $y . "\n";

$current = "S";
$start = true;
while (true) {

    if ($start) {
        $start = false;
        $direction = $pipes[$current][0];
    } else {
        $direction = getNextDirection($current, $directions[$direction]);
    }

    $x += $directions[$direction]["x"];
    $y += $directions[$direction]["y"];
    $current = $map[$y][$x];
    $visual[$y][$x] = "X";
    if ($current == "S") {
        break;
    }

    $path[] = $current;

    $nextDirection = getNextDirection($current, $directions[$direction]);
    $pathInsideOutside[] = ["value" => $current, "x" => $x, "y" => $y, "border" => $borderModel[$direction][$nextDirection], "oldDirection" => $direction, "direction" => $nextDirection];
}
echo "Task 1: " . ceil(count($path) / 2) . "\n";

// loop over top & bottom borders
for ($i = 0; $i < count($visual[0]); $i++) {
    if ($visual[0][$i] != "X" && $visual[0][$i] != "Y") {
        markFreeTiles($i, 0);
    }
    if ($visual[count($visual) - 1][$i] != "X" && $visual[count($visual) - 1][$i] != "Y") {
        markFreeTiles($i, count($visual) - 1);
    }
}

// loop over left & right borders
for ($i = 0; $i < count($visual); $i++) {
    if ($visual[$i][0] != "X" && $visual[$i][0] != "Y") {
        markFreeTiles(0, $i);
    }
    if ($visual[$i][count($visual[0]) - 1] != "X" && $visual[$i][count($visual[0]) - 1] != "Y") {
        markFreeTiles(count($visual[0]) - 1, $i);
    }
}

foreach ($visual as $y => $line) {
    foreach ($line as $x => $position) {
        if ($position != "X" && $position != "Y") {
            $map[$y][$x] = "Z";
        }
    }
}

// echo print_r($pathInsideOutside, true) . "\n";

foreach ($map as $y => $line) {
    foreach ($line as $x => $position) {
        if ($position == "Z") {
            if (isOnOutside($x, $y)) {
                markFreeTiles($x, $y);
            }
        }
    }
}

createPathTxtFile($map);

// room for helper functions
function getNextDirection($current, $lastDirection)
{
    global $pipes;
    $directions = $pipes[$current];
    return $directions[0] == $lastDirection["opposite"] ? $directions[1] : $directions[0];
}

function createPathTxtFile($visual)
{
    $file = fopen("output/10.txt", "w");
    foreach ($visual as $line) {
        fwrite($file, implode("", $line) . "\n");
    }
    fclose($file);
}

function markFreeTiles($x, $y)
{
    global $visual, $map, $directions;

    // echo "Marking free tile: " . $x . ", " . $y . "\n";

    $visual[$y][$x] = "Y";
    $map[$y][$x] = "Y";
    foreach ($directions as $direction) {
        $newX = $x + $direction["x"];
        $newY = $y + $direction["y"];
        // is in border?
        if ($newX == -1 || $newY == -1 || $newX == count($visual[0]) || $newY == count($visual)) {
            continue;
        }
        if ($visual[$newY][$newX] != "X" && $visual[$newY][$newX] != "Y") {
            markFreeTiles($newX, $newY);
        }
    }
}

function isOnOutside($x, $y)
{
    global $map, $visual, $directions;

    echo "Checking if " . $x . ", " . $y . " is on outside\n";

    foreach ($directions as $direction) {
        $newX = $x + $direction["x"];
        $newY = $y + $direction["y"];
        // is in border?
        if ($newX == -1 || $newY == -1 || $newX == count($map[0]) || $newY == count($map)) {
            continue;
        }
        if ($visual[$newY][$newX] == "X") {
            $border = getBorder($newX, $newY);
            // echo $map[$newY][$newX] . "\n";
            // echo print_r($border, true) . "\n";
            if ($border[$direction["opposite"]] == "outside") {
                return true;
            } else if ($border[$direction["opposite"]] == "inside") {
                return false;
            }
            return;
        }
    }
}

function getBorder($x, $y)
{
    global $pathInsideOutside;
    foreach ($pathInsideOutside as $index => $position) {
        if ($position["x"] == $x && $position["y"] == $y) {
            return $pathInsideOutside[$index]["border"];
        }
    }
}
