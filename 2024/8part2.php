<?php

$filepath = "input/8.txt";
$map = [];
$antennas = [];
$antinodes = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $line) {
    $map[] = str_split($line);
}

foreach ($map as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell != ".") {
            $antennas[$cell][] = [$y, $x];
        }
    }
}

foreach ($antennas as $key => $key_antennas) {
    foreach ($key_antennas as $i => $a) {
        foreach ($key_antennas as $j => $b) {
            if ($i != $j) {
                $disY = $a[0] - $b[0];
                $disX = $a[1] - $b[1];
                addAntinode([$a[0], $a[1]], $antinodes, $map);
                addAntinode([$b[0], $b[1]], $antinodes, $map);

                $y = $a[0] + $disY;
                $x = $a[1] + $disX;
                while (addAntinode([$y, $x], $antinodes, $map)) {
                    $y += $disY;
                    $x += $disX;
                };

                $y = $b[0] - $disY;
                $x = $b[1] - $disX;
                while (addAntinode([$y, $x], $antinodes, $map)) {
                    $y -= $disY;
                    $x -= $disX;
                };
            }
        }
    }
}

echo "Part 2: " . count($antinodes) . "\n";

function addAntinode($a, &$antinodes, $map)
{
    if ($a[0] < 0 || $a[0] >= count($map) || $a[1] < 0 || $a[1] >= count($map[0])) {
        return false;
    }
    if (!in_array($a, $antinodes)) {
        $antinodes[] = $a;
    }
    return true;
}
