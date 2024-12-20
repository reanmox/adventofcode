<?php

$filepath = "input/20.txt";
$map = [];
$y = 0;
$x = 0;

$directions = [
    'N' => [-1, 0],
    'S' => [1, 0],
    'E' => [0, 1],
    'W' => [0, -1],
];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $map[] = str_split($line);
    if (str_contains($line, "S")) {
        $x = strpos($line, "S");
        $y = $index;
    }
}

echo "Start at " . $y . " " . $x . PHP_EOL;
$track = [];
while ($map[$y][$x] != "E") {
    $track[] = [$y, $x];
    foreach ($directions as $dir => $move) {
        $newY = $y + $move[0];
        $newX = $x + $move[1];
        if ($map[$newY][$newX] != "#" && !in_array([$newY, $newX], $track)) {
            $y = $newY;
            $x = $newX;
            break;
        }
    }
}
$track[] = [$y, $x];
//print_r($track);
echo "Track length: " . count($track) . PHP_EOL;

$seconds = 20;
$cheats = [];
foreach ($track as $index => $point) {
    $y = $point[0];
    $x = $point[1];

    if ($index % 100 == 0) {
        echo "Index: " . $index . PHP_EOL;
    }

    for ($ry = $y - $seconds; $ry <= $y + $seconds; $ry++) {
        for ($rx = $x - $seconds; $rx <= $x + $seconds; $rx++) {
            if ($ry < 0 || $rx < 0 || $ry >= count($map) || $rx >= count($map[0])) {
                continue;
            }
            if ($ry == $y && $rx == $x) {
                continue;
            }
            $distance = abs($ry - $y) + abs($rx - $x);
            if ($distance > $seconds) {
                continue;
            }
            if (in_array([$ry, $rx], $track)) {
                $endIndex = array_search([$ry, $rx], $track);
                if ($endIndex - $distance > $index) {
                    $cheats[] = [
                        "start" => $index,
                        "end" => array_search([$ry, $rx], $track),
                        "saving" => $endIndex - $index - $distance
                    ];
                }
            }
        }
    }
}
// print_r($cheats);

$goodCheats = 0;
$savings = [];
foreach ($cheats as $cheat) {
    if (isset($savings[$cheat["saving"]])) {
        $savings[$cheat["saving"]] += 1;
    } else {
        $savings[$cheat["saving"]] = 1;
    }
    $goodCheats += $cheat["saving"] >= 100 ? 1 : 0;
}
ksort($savings);
print_r($savings);
echo "Part 2: " . $goodCheats . PHP_EOL;
