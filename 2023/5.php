<?php

$filepath = "input/5.txt";
$maps = [];
$seeds = [];

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n\n", $contents);

foreach ($contents as $index => $line) {
    if ($index == 0) {
        $seeds = explode(" ", explode(": ", $line)[1]);
    } else {
        $conversions = explode("\n", $line);
        $conversionName = "";
        foreach ($conversions as $cIndex => $conversion) {
            if ($cIndex == 0)
                $conversionName = explode(" ", $conversion)[0];
            else {
                $conversion = explode(" ", $conversion);
                $maps[$conversionName][] = new Conversion($conversion[0], $conversion[1], $conversion[2]);
            }
        }
    }
}

// echo print_r($maps, true);
// echo print_r($seeds, true);

$convertedSeeds = [];
foreach ($seeds as $index => $seed) {
    $currentValue = $seed;
    //echo "Seed: " . $currentValue . "\n";
    foreach ($maps as $mapName => $map) {
        foreach ($map as $conversion) {
            if ($currentValue >= $conversion->sourceStart && $currentValue < $conversion->sourceStart + $conversion->range) {
                $currentValue = $conversion->destinationStart + ($currentValue - $conversion->sourceStart);
                //echo "Conversion (Source Range:" . $conversion->sourceStart . " - " . $conversion->sourceStart + $conversion->range . "): " . $mapName . " " . $currentValue . "\n";
                break;
            }
        }
    }
    $convertedSeeds[$index]["seed"] = $seed;
    $convertedSeeds[$index]["location"] = $currentValue;
}
//echo print_r($convertedSeeds, true);

// get lowest locatioin value
$lowestLocation = 0;
foreach ($convertedSeeds as $index => $convertedSeed) {
    if ($index == 0)
        $lowestLocation = $convertedSeed["location"];
    else if ($convertedSeed["location"] < $lowestLocation)
        $lowestLocation = $convertedSeed["location"];
}
echo "Task 1: " . $lowestLocation . "\n";

// Task 2
$ranges = [];
// create initial ranges
for ($i = 0; $i < count($seeds); $i += 2) {
    $start = $seeds[$i];
    $end = $start + $seeds[$i + 1] - 1;
    $ranges[] = ["start" => $start, "end" => $end];
}

// sort maps by sourceStart
foreach ($maps as $mapName => &$map) {
    usort($map, function ($a, $b) {
        return $a->sourceStart <=> $b->sourceStart;
    });
}

// echo print_r($ranges, true);
// echo print_r($maps, true);

foreach ($ranges as $index => &$range) {;
    checkRange($range);
}
echo print_r($ranges, true);

// get lowest locatioin value
$lowestLocation = 0;
foreach ($ranges as $index => $range) {
    if ($index == 0)
        $lowestLocation = $range["start"];
    else if ($range["start"] < $lowestLocation)
        $lowestLocation = $range["start"];
}
echo "Task 2: " . $lowestLocation . "\n";

// room for helper functions

class Conversion
{
    public $destinationStart = 0;
    public $destinationEnd = 0;
    public $sourceStart = 0;
    public $sourceEnd = 0;
    public $range = 0;

    public function __construct($destinationStart, $sourceStart, $range)
    {
        $this->destinationStart = $destinationStart;
        $this->sourceStart = $sourceStart;
        $this->range = $range;
        $this->destinationEnd = $destinationStart + $range - 1;
        $this->sourceEnd = $sourceStart + $range - 1;
    }
}

function checkRange(&$range)
{
    global $maps;
    global $ranges;

    echo "Range: " . $range["start"] . " - " . $range["end"] . " " . (isset($range["startMap"]) ? $range["startMap"] : "") . "\n";
    // if (isset($range["startMap"]))
    //     return;

    foreach ($maps as $mapName => $map) {
        // echo "Map Name: " . $mapName . "\n";
        if (isset($range["startMap"]) && $range["startMap"] != $mapName)
            continue;
        else if (isset($range["startMap"]) && $range["startMap"] == $mapName) {
            unset($range["startMap"]);
            echo "Continue with Map: " . $mapName . " for range " . $range["start"] . " - " . $range["end"] . "\n";
        }
        foreach ($map as $conversion) {
            // complete range conversion
            if ($range["start"] >= $conversion->sourceStart && $range["end"] < $conversion->sourceStart + $conversion->range) {
                echo "Complete Conversion (Source Range: " . $conversion->sourceStart . " - " . $conversion->sourceStart + $conversion->range . "): " . $mapName . " for Range " . $range["start"] . " - " . $range["end"] . "\n";
                $range["start"] = $conversion->destinationStart + ($range["start"] - $conversion->sourceStart);
                $range["end"] = $conversion->destinationStart + ($range["end"] - $conversion->sourceStart);
                break;
            }
            // partial range conversion
            else if ($range["start"] >= $conversion->sourceStart && $range["start"] < $conversion->sourceStart + $conversion->range) {
                echo "Partial Conversion (Source Range: " . $conversion->sourceStart . " - " . $conversion->sourceStart + $conversion->range . "): " . $mapName . " for Range " . $range["start"] . " - " . $range["end"] . "\n";
                $partialRange = [
                    "start" => $conversion->sourceStart + $conversion->range,
                    "end" => $range["end"],
                    "startMap" => $mapName
                ];
                $ranges[] = $partialRange;
                checkRange($partialRange);
                echo "New Range: " . ($conversion->sourceStart + $conversion->range) . " - " . $range["end"] . "\n";
                $range["end"] = $conversion->sourceEnd;
                $range["start"] = $conversion->destinationStart + ($range["start"] - $conversion->sourceStart);
                $range["end"] = $conversion->destinationStart + ($range["end"] - $conversion->sourceStart);
                break;
            }
        }
    }
}
