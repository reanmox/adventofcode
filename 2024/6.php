<?php

$filepath = "input/6.txt";
$map = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

$curY = 0;
$curX = 0;

$move = [
    'up' => [-1, 0],
    'down' => [1, 0],
    'left' => [0, -1],
    'right' => [0, 1],
];

$turn = [
    'up' => 'right',
    'right' => 'down',
    'down' => 'left',
    'left' => 'up',
];

foreach ($contents as $y => $line) {
    $map[] = str_split($line);
    if (in_array('^', $map[$y])) {
        $curY = $y;
        $curX = array_search('^', $map[$y]);
    }
}

echo "Start: $curX, $curY\n";

$end = false;
$direction = 'up';

while (!$end) {
    $map[$curY][$curX] = 'X';

    $x = $curX + $move[$direction][1];
    $y = $curY + $move[$direction][0];

    if ($x < 0 || $y < 0 || $x >= count($map[0]) || $y >= count($map)) {
        break;
    }

    if ($map[$y][$x] == '#') {
        $direction = $turn[$direction];
        $x = $curX + $move[$direction][1];
        $y = $curY + $move[$direction][0];
        if ($x < 0 || $y < 0 || $x >= count($map[0]) || $y >= count($map)) {
            break;
        }
    }

    $curX = $x;
    $curY = $y;
}

// foreach ($map as $line) {
//     echo implode('', $line) . "\n";
// }

$sum = 0;
foreach ($map as $line) {
    foreach ($line as $char) {
        if ($char == 'X') {
            $sum++;
        }
    }
}
echo "Part 1: $sum\n";
