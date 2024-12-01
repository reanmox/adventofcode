<?php

$filepath = "input/11.txt";
$universe = [];
$cleanUniverse = [];

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

$galaxyIndex = 0;
$expandRows = [];
foreach ($contents as $index => $line) {
    $line = str_split($line);
    $rowHasGalaxy = false;
    array_walk($line, function (&$value) use (&$galaxyIndex, &$rowHasGalaxy) {
        if ($value === "#") {
            $rowHasGalaxy = true;
            $value = ["value" => $value, "galaxy" => $galaxyIndex];
            $galaxyIndex++;
        } else {
            $value = ["value" => $value];
        }
    });
    $universe[] = $line;
    $cleanUniverse[] = $line;
    if (!$rowHasGalaxy) {
        $expandRows[] = $index;
        $universe[] = $line;
    }
}

// expand universe
$expandColumns = [];
for ($x = 0; $x < count($universe[0]); $x++) {
    $columnHasGalaxy = false;
    for ($y = 0; $y < count($universe); $y++) {
        if ($universe[$y][$x]["value"] === "#") {
            $columnHasGalaxy = true;
            break;
        }
    }
    if (!$columnHasGalaxy) {
        $expandColumns[] = $x;
    }
}

insertColumnsToUniverse($universe, $expandColumns);

// echo count($universe) . "\n";
// echo count($universe[0]) . "\n";
// echo print_r($expandColumns, true);

$galaxies = [];
foreach ($universe as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell["value"] != ".") {
            $galaxies[$y][$x] = $cell["galaxy"];
        }
    }
}

$sum = 0;
foreach ($galaxies as $y => $row) {
    foreach ($row as $x => $cell) {
        // get Distance to all other galaxies
        foreach ($galaxies as $y2 => $row2) {
            foreach ($row2 as $x2 => $cell2) {
                if ($x === $x2 && $y === $y2) {
                    continue;
                }
                if ($cell < $cell2) {
                    $distance = getDistanceBetweenPoints($x, $y, $x2, $y2);
                    $sum += $distance;
                }
            }
        }
    }
}

echo "Task 1: " . $sum . "\n";
// echo print_r($galaxies, true);

// Task 2
echo print_r(count($cleanUniverse), true) . "\n";
echo print_r(count($cleanUniverse[0]), true) . "\n";
echo print_r($expandRows, true) . "\n";
echo print_r($expandColumns, true) . "\n";

$galaxies = [];
foreach ($cleanUniverse as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell["value"] != ".") {
            $galaxies[$y][$x] = $cell["galaxy"];
        }
    }
}

$sum = 0;
foreach ($galaxies as $y => $row) {
    foreach ($row as $x => $cell) {
        // get Distance to all other galaxies
        foreach ($galaxies as $y2 => $row2) {
            foreach ($row2 as $x2 => $cell2) {
                if ($x === $x2 && $y === $y2) {
                    continue;
                }
                if ($cell < $cell2) {
                    $distance = getDistanceBetweenPointsInExpanded($x, $y, $x2, $y2, $expandRows, $expandColumns, 1000000);
                    $sum += $distance;
                }
            }
        }
    }
}
echo "Task 2: " . $sum . "\n";

function insertColumnsToUniverse(&$universe, $columns)
{
    foreach ($columns as $cIndex => $column) {
        $column = $column + $cIndex;
        foreach ($universe as $index => $row) {
            $universe[$index][] = [];
            for ($i = count($row); $i > $column; $i--) {
                $universe[$index][$i] = $universe[$index][$i - 1];
            }
        }
    }
}

function getDistanceBetweenPoints($x1, $y1, $x2, $y2)
{
    return abs($x1 - $x2) + abs($y1 - $y2);
}

function getDistanceBetweenPointsInExpanded($x1, $y1, $x2, $y2, $expandRows, $expandColumns, $expansion = 999999)
{
    $x1Expanded = $x1;
    $y1Expanded = $y1;
    $x2Expanded = $x2;
    $y2Expanded = $y2;

    foreach ($expandRows as $row) {
        if ($y1 > $row) {
            $y1Expanded += $expansion;
        }
        if ($y2 > $row) {
            $y2Expanded += $expansion;
        }
    }

    foreach ($expandColumns as $column) {
        if ($x1 > $column) {
            $x1Expanded += $expansion;
        }
        if ($x2 > $column) {
            $x2Expanded += $expansion;
        }
    }

    return abs($x1Expanded - $x2Expanded) + abs($y1Expanded - $y2Expanded);
}
