<?php

$directions = [
    "N" => ["x" => 0, "y" => -1, "key" => "N", "opposite" => "S", "/" => "E", "\\" => "W", "-" => ["W", "E"], "|" => "N"],
    "S" => ["x" => 0, "y" => 1, "key" => "S", "opposite" => "N", "/" => "W", "\\" => "E", "-" => ["W", "E"], "|" => "S"],
    "E" => ["x" => 1, "y" => 0, "key" => "E", "opposite" => "W", "/" => "N", "\\" => "S", "-" => "E", "|" => ["N", "S"]],
    "W" => ["x" => -1, "y" => 0, "key" => "W", "opposite" => "E", "/" => "S", "\\" => "N", "-" => "W", "|" => ["N", "S"]]
];

$filepath = "input/16.txt";
$map = [];
$positions = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $y => $line) {
    $map[$y] = str_split($line);
}

enterPosition(0, 0, $directions["E"]);

echo print_r($positions, true);
echo "Task 1: " . count($positions) . "\n";

function enterPosition($x, $y, $direction)
{
    global $map, $positions, $directions;

    $k = $direction["key"];

    if (!isset($map[$y][$x])) {
        return;
    }
    if (isset($positions["{$x},{$y}"]) && in_array($k, $positions["{$x},{$y}"])) {
        echo "Already visited {$x},{$y} with direction {$k}\n";
        return;
    }

    echo "Entering {$x},{$y} with direction {$k}\n";
    $value = $map[$y][$x];

    if ($value == '.') {
        $positions["{$x},{$y}"][] = $k;
        enterPosition($x + $direction["x"], $y + $direction["y"], $direction);
    }
    if ($value == "/" || $value == "\\") {
        $positions["{$x},{$y}"][] = $k;
        $direction = $directions[$direction[$value]];
        enterPosition($x + $direction["x"], $y + $direction["y"], $direction);
    }
    if ($value == "-" || $value == "|") {
        $positions["{$x},{$y}"][] = $k;
        $directionKey = $direction[$value];
        if (!is_array($directionKey)) {
            enterPosition($x + $direction["x"], $y + $direction["y"], $direction);
        } else {
            $direction1 = $directions[$directionKey[0]];
            $direction2 = $directions[$directionKey[1]];
            enterPosition($x + $direction1["x"], $y + $direction1["y"], $direction1);
            enterPosition($x + $direction2["x"], $y + $direction2["y"], $direction2);
        }
    }
}
