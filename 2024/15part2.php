<?php

$filepath = "input/15.txt";
$map = [];
$cleanMap = [];
$moves = [];
$boxes = [];

$directions = [
    '^' => [-1, 0],
    'v' => [1, 0],
    '<' => [0, -1],
    '>' => [0, 1],
];

$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);

$x = 0;
$y = 0;

foreach (explode("\n", $contents[0]) as $index => $line) {
    $line = str_replace('#', '##', $line);
    $line = str_replace('.', '..', $line);
    $line = str_replace('O', '[]', $line);
    $line = str_replace('@', '@.', $line);

    $l = str_split($line);
    $map[] = $l;
    if (in_array('@', $l)) {
        $x = array_search('@', $l);
        $y = $index;
    }
}

foreach (explode("\n", $contents[1]) as $line) {
    $moves = array_merge($moves, str_split($line));
}

$cleanMap = $map;
foreach ($map as $y1 => $line) {
    foreach ($line as $x1 => $cell) {
        if ($cell == '[') {
            $boxes[] = [
                'y' => $y1,
                'x' => $x1,
            ];
            $cleanMap[$y1][$x1] = '.';
            $cleanMap[$y1][$x1 + 1] = '.';
        }
    }
}

printMap();

foreach ($moves as $moveIndex => $move) {
    $debug = false;
    $newY = $y + $directions[$move][0];
    $newX = $x + $directions[$move][1];
    // echo "Move $move\n";

    if ($map[$newY][$newX] == '#') {
        continue;
    } else if ($map[$newY][$newX] == '.') {
    } else if ($map[$newY][$newX] == '[' || $map[$newY][$newX] == ']') {
        $possible = true;
        if ($move == '<' || $move == '>') {
            $objects = checkPush($newY, $newX, $move);
        }
        if ($move == '^' || $move == 'v') {
            $objects = [];
            $obj = [
                'y' => $newY,
                'x' => $newX + ($map[$newY][$newX] == '[' ? 0 : -1),
            ];
            checkPushRecursive($obj, $move, $objects, $possible);
        }
        if ($objects && $possible) {
            moveBoxes($objects, $move);
        } else {
            continue;
        }
    }
    $cleanMap[$y][$x] = '.';
    $cleanMap[$newY][$newX] = '@';
    $y = $newY;
    $x = $newX;

    recreateMap();
}

printMap();

$gps = 0;
foreach ($map as $y => $line) {
    foreach ($line as $x => $cell) {
        if ($cell == '[') {
            $gps += ($y * 100) + $x;
        }
    }
}
echo "Part 2: $gps\n";

function printMap()
{
    global $map;
    foreach ($map as $line) {
        echo implode('', $line) . "\n";
    }
    echo "\n";
}

function checkPush($y, $x, $direction)
{
    global $directions, $map;

    $obj = [];

    while ($map[$y][$x] != '.') {
        if ($y < 0 || $x < 0 || $y >= count($map) || $x >= count($map[0])) {
            return false;
        }
        if ($map[$y][$x] == '#') {
            return false;
        }
        if ($map[$y][$x] == '[') {
            $obj[] = [
                'y' => $y,
                'x' => $x,
            ];
        }
        $y += $directions[$direction][0];
        $x += $directions[$direction][1];
    }
    return $obj;
}

function checkPushRecursive($obj, $direction, &$objects, &$possible)
{
    global $directions, $map;

    if (in_array($obj, $objects)) {
        return;
    }

    $objects[] = $obj;

    // echo "Checking " . $obj['y'] . " " . $obj['x'] . "\n";

    $newY = $obj['y'] + $directions[$direction][0];
    $newX = $obj['x'] + $directions[$direction][1];

    $newX2 = $newX + 1;

    if ($map[$newY][$newX] == '#' || $map[$newY][$newX2] == '#') {
        $possible = false;
        return;
    }

    if ($map[$newY][$newX] == '[') {
        $newObj = [
            'y' => $newY,
            'x' => $newX,
        ];
        checkPushRecursive($newObj, $direction, $objects, $possible);
    }
    if ($map[$newY][$newX] == ']') {
        $newObj = [
            'y' => $newY,
            'x' => $newX - 1,
        ];
        checkPushRecursive($newObj, $direction, $objects, $possible);
    }
    if ($map[$newY][$newX2] == '[') {
        $newObj = [
            'y' => $newY,
            'x' => $newX2,
        ];
        checkPushRecursive($newObj, $direction, $objects, $possible);
    }
}

function moveBoxes($objects, $direction)
{
    //echo "Move " . count($objects) . " boxes\n";
    global $directions, $boxes;
    foreach ($objects as $obj) {
        $newY = $obj['y'] + $directions[$direction][0];
        $newX = $obj['x'] + $directions[$direction][1];

        $index = array_search($obj, $boxes);
        $boxes[$index] = [
            'y' => $newY,
            'x' => $newX,
        ];
    }
}

function recreateMap()
{
    global $map, $cleanMap, $boxes;
    $map = $cleanMap;
    foreach ($boxes as $box) {
        $map[$box['y']][$box['x']] = '[';
        $map[$box['y']][$box['x'] + 1] = ']';
    }
}
