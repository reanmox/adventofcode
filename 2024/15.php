<?php

$filepath = "input/15.txt";
$map = [];
$moves = [];

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

foreach ($moves as $move) {
    $newY = $y + $directions[$move][0];
    $newX = $x + $directions[$move][1];

    if ($map[$newY][$newX] == '#') {
        continue;
    } else if ($map[$newY][$newX] == '.') {
    } else if ($map[$newY][$newX] == 'O') {
        $push = checkPush($newY, $newX, $move);
        if ($push) {
            $map[$push['y']][$push['x']] = 'O';
        } else {
            continue;
        }
    }
    $map[$y][$x] = '.';
    $map[$newY][$newX] = '@';
    $y = $newY;
    $x = $newX;
}

$gps = 0;
foreach ($map as $y => $line) {
    foreach ($line as $x => $cell) {
        if ($cell == 'O') {
            $gps += ($y * 100) + $x;
        }
    }
}
echo "Part 1: $gps\n";

foreach ($map as $line) {
    echo implode('', $line) . "\n";
}

function checkPush($y, $x, $direction)
{
    global $directions, $map;

    while ($map[$y][$x] != '.') {
        $y += $directions[$direction][0];
        $x += $directions[$direction][1];
        if ($y < 0 || $x < 0 || $y >= count($map) || $x >= count($map[0])) {
            return false;
        }
        if ($map[$y][$x] == '#') {
            return false;
        }
    }
    return [
        'y' => $y,
        'x' => $x,
    ];
}
