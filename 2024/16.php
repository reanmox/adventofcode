<?php

$filepath = "input/16.txt";
$map = [];
$scoreMap = [];

$directions = [
    'N' => [-1, 0, 'S'],
    'S' => [1, 0, 'N'],
    'E' => [0, 1, 'W'],
    'W' => [0, -1, 'E'],
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

$direction = 'E';
$y = 0;
$x = 0;
$results = [];
$minScore = 0;
$canceledPaths = 0;

foreach ($contents as $index => $line) {
    $map[] = str_split($line);
    if (in_array('S', $map[$index])) {
        $y = $index;
        $x = array_search('S', $map[$index]);
    }
}

echo "Start position: $y, $x\n";

clearMaze($y, $x, $direction, [], -1);

echo "Paths found: " . count($results) . "\n";
echo "Canceled paths: $canceledPaths\n";
echo "Part 1: shortest path: " . min(array_column($results, 'score')) . "\n";

function clearMaze($y, $x, $dir, $moves, $score)
{
    global $map, $directions, $results, $minScore, $canceledPaths, $scoreMap;

    if ($minScore > 0 && $score >= $minScore) {
        $canceledPaths++;
        if ($canceledPaths % 10000 == 0) {
            echo "Canceled paths: $canceledPaths\n";
        }
        return;
    }

    if (!isset($scoreMap[$y . ";" . $x])) {
        $scoreMap[$y . ";" . $x] = $score;
    } else {
        if ($scoreMap[$y . ";" . $x] <= $score) {
            $canceledPaths++;
            return;
        } else {
            $scoreMap[$y . ";" . $x] = $score;
        }
    }

    if (in_array([$y, $x], $moves)) {
        return;
    } else {
        $moves[] = [$y, $x];
        $score++;
    }

    if ($map[$y][$x] == 'E') {
        $results[] = ["moves" => $moves, "score" => $score];
        if ($minScore == 0 || $score < $minScore) {
            $minScore = $score;
        }
        echo "Path found: " . count($moves) . " moves, score: $score\n";
        return;
    }

    foreach ($directions as $d => $direction) {
        $newY = $y + $direction[0];
        $newX = $x + $direction[1];
        // check if we are going back
        if ($d == $directions[$dir][2]) {
            continue;
        }
        if ($map[$newY][$newX] == '.' || $map[$newY][$newX] == 'E') {
            clearMaze($newY, $newX, $d, $moves, $score + ($d == $dir ? 0 : 1000));
        }
    }
}
