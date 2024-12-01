<?php

$start = microtime(true);

$filepath = "input/23.txt";
$map = [];

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
$failedHikes = 0;

hikeMap($startX, 0, [], $paths);

echo "Task 1: " . $longestHike - 1 . "\n";

$time_elapsed_secs = microtime(true) - $start;
echo "Time elapsed: $time_elapsed_secs seconds\n";

function hikeMap($x, $y, $path, &$paths)
{
    global $map, $endX, $directions, $longestHike, $failedHikes;

    $path["$x;$y"] = true;

    // end reached
    if ($x == $endX && $y == count($map) - 1) {
        $paths[] = count($path);
        echo count($paths) . "\n";
        if (count($path) > $longestHike) {
            $longestHike = count($path);
            echo "New longest hike: $longestHike\n";
        }
        return;
    }

    foreach ($directions as $direction) {
        $newX = $x + $direction["x"];
        $newY = $y + $direction["y"];

        if ($newX < 0 || $newX >= count($map[0]) || $newY < 0 || $newY >= count($map)) {
            continue;
        }
        if ($map[$newY][$newX] == '#') {
            continue;
        }
        if (isset($path["$newX;$newY"])) {
            $failedHikes++;
            if ($failedHikes % 1000 == 0) {
                echo "Failed hikes: $failedHikes\n";
            }
            continue;
        }

        hikeMap($newX, $newY, $path, $paths);
    }
}
