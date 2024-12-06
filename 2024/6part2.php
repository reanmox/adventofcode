<?php

$filepath = "input/6.txt";
$map = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

$startY = 0;
$startX = 0;
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
        $startY = $y;
        $startX = array_search('^', $map[$y]);
    }
}

$loops = 0;

foreach ($map as $globalY => $row) {
    foreach ($row as $globalX => $field) {
        if ($field == '^' || $field == '#') {
            continue;
        }
        $map[$globalY][$globalX] = '#';

        $direction = 'up';
        $curY = $startY;
        $curX = $startX;
        $curMap = $map;

        // foreach ($map as $line) {
        //     echo implode('', $line) . "\n";
        // }
        // echo "\n";

        while (true) {
            $curMap[$curY][$curX] = !is_numeric($curMap[$curY][$curX]) ? 1 : $curMap[$curY][$curX] + 1;

            $x = $curX + $move[$direction][1];
            $y = $curY + $move[$direction][0];

            if ($x < 0 || $y < 0 || $x >= count($curMap[0]) || $y >= count($curMap)) {
                break;
            }

            if ($curMap[$y][$x] == '100') {
                $loops++;
                break;
            }

            if ($curMap[$y][$x] == '#') {
                $direction = $turn[$direction];
                $x = $curX + $move[$direction][1];
                $y = $curY + $move[$direction][0];
                if ($x < 0 || $y < 0 || $x >= count($curMap[0]) || $y >= count($curMap)) {
                    break;
                }
                if ($curMap[$y][$x] == '100') {
                    $loops++;
                    break;
                }

                if ($curMap[$y][$x] == '#') {
                    $direction = $turn[$direction];
                    $x = $curX + $move[$direction][1];
                    $y = $curY + $move[$direction][0];
                    if ($x < 0 || $y < 0 || $x >= count($curMap[0]) || $y >= count($curMap)) {
                        break;
                    }
                    if ($curMap[$y][$x] == '100') {
                        $loops++;
                        break;
                    }
                }
            }

            $curX = $x;
            $curY = $y;
        }

        $map[$globalY][$globalX] = '.';
    }
}

echo "Part 2: $loops\n";
