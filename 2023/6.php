<?php

$races = [
    ["time" => 53, "record" => 313, "ways" => 0],
    ["time" => 89, "record" => 1090, "ways" => 0],
    ["time" => 76, "record" => 1214, "ways" => 0],
    ["time" => 98, "record" => 1201, "ways" => 0],
    ["time" => 53897698, "record" => 313109012141201, "ways" => 0],
];

$sum = 1;
foreach ($races as $index => &$race) {
    for ($i = 0; $i <= $race["time"]; $i++) {
        $distance = $i * ($race["time"] - $i);
        if ($distance > $race["record"]) {
            $race["ways"] += 1;
        }
    }
    if ($index != 4)
        $sum *= $race["ways"];
}

echo print_r($races, true);

echo "Task 1: " . $sum . "\n";
echo "Task 2: " . $races[4]["ways"] . "\n";
