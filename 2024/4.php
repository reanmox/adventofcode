<?php

$filepath = "input/4.txt";
$map = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $map[] = str_split($line);
}

$sum = 0;

// horizontal
foreach ($map as $row) {
    $line = implode("", $row);
    $sum += substr_count($line, "XMAS");
    $sum += substr_count($line, "SAMX");
}

// vertical
for ($x = 0; $x < count($map[0]); $x++) {
    $line = "";
    for ($y = 0; $y < count($map); $y++) {
        $line .= $map[$y][$x];
    }
    $sum += substr_count($line, "XMAS");
    $sum += substr_count($line, "SAMX");
}

for ($x = 0; $x < count($map); $x++) {
    for ($y = 0; $y < count($map); $y++) {
        if ($map[$y][$x] == "X") {
            // top right 55 46 37 28 (where 55 is the current position)
            if ($y - 3 >= 0 && $x + 3 < count($map)) {
                if ($map[$y][$x] . $map[$y - 1][$x + 1] . $map[$y - 2][$x + 2] . $map[$y - 3][$x + 3] == "XMAS") {
                    $sum++;
                }
            }

            // bottom right 55 66 77 88
            if ($y + 3 < count($map) && $x + 3 < count($map)) {
                if ($map[$y][$x] . $map[$y + 1][$x + 1] . $map[$y + 2][$x + 2] . $map[$y + 3][$x + 3] == "XMAS") {
                    $sum++;
                }
            }

            // bottom left 55 64 73 82
            if ($y + 3 < count($map) && $x - 3 >= 0) {
                if ($map[$y][$x] . $map[$y + 1][$x - 1] . $map[$y + 2][$x - 2] . $map[$y + 3][$x - 3] == "XMAS") {
                    $sum++;
                }
            }

            // top left 55 44 33 22
            if ($y - 3 >= 0 && $x - 3 >= 0) {
                if ($map[$y][$x] . $map[$y - 1][$x - 1] . $map[$y - 2][$x - 2] . $map[$y - 3][$x - 3] == "XMAS") {
                    $sum++;
                }
            }
        }
    }
}

echo "Part 1: $sum\n";
