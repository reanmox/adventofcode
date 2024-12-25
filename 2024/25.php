<?php

$filepath = "input/25.txt";
$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);

$keys = [];
$locks = [];

foreach ($contents as $block) {
    $block_lines = explode("\n", $block);

    if ($block_lines[0] == "#####") {
        $locks[] = get_num_values($block_lines);
    } else {
        $keys[] = get_num_values($block_lines, false);
    }
}

$count = 0;
foreach ($locks as $lock) {
    foreach ($keys as $key) {
        $match = true;
        for ($i = 0; $i < count($lock); $i++) {
            if ($lock[$i] + $key[$i] >= 6) {
                $match = false;
                break;
            }
        }
        if ($match) {
            $count++;
        }
    }
}
echo "Part 1: $count\n";

function get_num_values($block_lines, $lock = true)
{
    $c1 = $c2 = $c3 = $c4 = $c5 = 0;
    foreach ($block_lines as $index => $line) {
        if ($lock && $index == 0) {
            continue;
        }
        if (!$lock && $index == count($block_lines) - 1) {
            continue;
        }
        $l = str_split($line);
        if ($l[0] == "#")
            $c1++;
        if ($l[1] == "#")
            $c2++;
        if ($l[2] == "#")
            $c3++;
        if ($l[3] == "#")
            $c4++;
        if ($l[4] == "#")
            $c5++;
    }
    return [$c1, $c2, $c3, $c4, $c5];
}
