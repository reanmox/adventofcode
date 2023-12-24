<?php

$start = microtime(true);

$filepath = "input/23.txt";
$map = [];
$canClimbSlopes = false;

$directions = [
    "^" => ["x" => 0, "y" => -1],
    "v" => ["x" => 0, "y" => 1],
    ">" => ["x" => 1, "y" => 0],
    "<" => ["x" => -1, "y" => 0]
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $map[$index] = str_split($line);
}

$startX = strpos($contents[0], '.');
$endX = strpos($contents[count($contents) - 1], '.');

$paths = [];
$longestHike = 0;
hikeMap($startX, 0, [], $paths);

foreach ($paths as $path) {
    if (count($path) > $longestHike) {
        $longestHike = count($path);
    }
}
echo "Task 1: " . $longestHike - 1 . "\n";

$time_elapsed_secs = microtime(true) - $start;
echo "Time elapsed: $time_elapsed_secs seconds\n";

function hikeMap($x, $y, $path, &$paths)
{
    global $map, $endX, $directions, $canClimbSlopes, $longestHike;

    if ($x < 0 || $x >= count($map[0]) || $y < 0 || $y >= count($map)) {
        return;
    }
    if ($map[$y][$x] == '#') {
        return;
    }
    if (isset($path["$x;$y"])) {
        return;
    }

    $path["$x;$y"] = ["x" => $x, "y" => $y];

    // end reached
    if ($x == $endX && $y == count($map) - 1) {
        // $paths[] = count($path);
        $paths[] = $path;
        echo count($paths) . "\n";
        if (count($path) > $longestHike) {
            $longestHike = count($path);
            echo "New longest hike: $longestHike\n";
        }
        return;
    }

    if ($map[$y][$x] == '.' || $canClimbSlopes) {
        foreach ($directions as $direction) {
            $newX = $x + $direction["x"];
            $newY = $y + $direction["y"];

            hikeMap($newX, $newY, $path, $paths);
        }
    } else {
        $direction = $directions[$map[$y][$x]];
        $newX = $x + $direction["x"];
        $newY = $y + $direction["y"];

        hikeMap($newX, $newY, $path, $paths);
    }

    return $paths;
}
