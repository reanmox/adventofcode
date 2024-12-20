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
// print_r($track);

$cheats = [];
foreach ($track as $index => $point) {
    $y = $point[0];
    $x = $point[1];
    $count = 0;
    foreach ($directions as $dir => $move) {
        $newY = $y + $move[0];
        $newX = $x + $move[1];
        $cheatY = $y + $move[0] * 2;
        $cheatX = $x + $move[1] * 2;
        if ($map[$newY][$newX] == "#" && in_array([$cheatY, $cheatX], $track)) {
            $endIndex = array_search([$cheatY, $cheatX], $track);
            if ($endIndex > $index) {
                $cheats[] = [
                    "start" => $index,
                    "end" => array_search([$cheatY, $cheatX], $track),
                    "saving" => $endIndex - $index - 2
                ];
            }
        }
    }
}
print_r($cheats);

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
echo "Part 1: " . $goodCheats . PHP_EOL;
