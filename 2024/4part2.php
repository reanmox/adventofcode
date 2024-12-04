<?php

$filepath = "input/4.txt";
$map = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $map[] = str_split($line);
}

$sum = 0;

for ($x = 0; $x < count($map); $x++) {
    for ($y = 0; $y < count($map); $y++) {
        if ($map[$y][$x] == "A") {
            if ($y - 1 >= 0 && $y + 1 < count($map) && $x - 1 >= 0 && $x + 1 < count($map)) {
                // left diagonal 44 55 66
                $leftDiagonal = $map[$y - 1][$x - 1] . $map[$y][$x] . $map[$y + 1][$x + 1];

                // right diagonal 46 55 64
                $rightDiagonal = $map[$y - 1][$x + 1] . $map[$y][$x] . $map[$y + 1][$x - 1];

                if (($leftDiagonal == "MAS" || $leftDiagonal == "SAM") && ($rightDiagonal == "MAS" || $rightDiagonal == "SAM")) {
                    $sum++;
                }
            }
        }
    }
}

echo "Part 2: $sum\n";
