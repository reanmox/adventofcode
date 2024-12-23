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

$lan = [];
foreach ($computers as $c => $cs) {
    $all_connected = [];
    $all_connected[] = $c;
    for ($i = 0; $i < count($cs); $i++) {
        $is_all_connected = true;
        for ($j = $i + 1; $j < count($cs); $j++) {
            if (!in_array($cs[$j], $computers[$cs[$i]])) {
                $is_all_connected = false;
                break;
            }
        }
        if ($is_all_connected) {
            $all_connected[] = $cs[$i];
        }
    }
    if (count($all_connected) > count($lan)) {
        $lan = $all_connected;
    }
}
sort($lan);
echo "Part 2: " . implode(",", $lan) . "\n";
