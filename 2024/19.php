<?php

$filepath = "input/19.txt";
$towels = [];
$designs = [];

$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);
$towels = explode(",", str_replace(" ", "", $contents[0]));
$designs = explode("\n", $contents[1]);

$possibleDesigns = [];
foreach ($designs as $design) {
    $solutions = [];
    echo "Check design " . $design . PHP_EOL;
    createDesign($design, 0, []);
    if (count($solutions) > 0) {
        $possibleDesigns[] = $design;
    }
}
echo "Part 1: " . count($possibleDesigns) . PHP_EOL;

function createDesign($design, $index, $ts)
{
    global $towels, $solutions;

    if (count($solutions) > 0) {
        return;
    }

    if ($index == strlen($design)) {
        $solutions[] = $ts;
        return;
    }

    foreach ($towels as $towel) {
        if (strpos($design, $towel, $index) === $index) {
            $ts[] = $towel;
            createDesign($design, $index + strlen($towel), $ts);
            array_pop($ts);
        }
    }
}
