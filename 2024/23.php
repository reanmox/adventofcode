<?php

$filepath = "input/23.txt";
$contents = explode("\n", file_get_contents($filepath));
$connections = [];

foreach ($contents as $c) {
    $connections[] = explode("-", $c);
}

$computers = [];
foreach ($connections as $c) {
    $computers[$c[0]][] = $c[1];
    $computers[$c[1]][] = $c[0];
}

$three_connected = [];
foreach ($computers as $c => $cs) {
    for ($i = 0; $i < count($cs); $i++) {
        for ($j = $i + 1; $j < count($cs); $j++) {
            if (in_array($cs[$j], $computers[$cs[$i]])) {
                if (substr($c, 0, 1) == "t" || substr($cs[$i], 0, 1) == "t" || substr($cs[$j], 0, 1) == "t") {
                    $names = [$c, $cs[$i], $cs[$j]];
                    sort($names);
                    $three_connected[implode("-", $names)] = true;
                }
            }
        }
    }
}

print_r($three_connected);
echo "Part 1: " . count($three_connected) . "\n";
