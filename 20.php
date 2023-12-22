<?php

$filepath = "input/20.txt";
$modules = [];

$contents = file_get_contents($filepath);
$contents = explode("\n", $contents);

foreach ($contents as $line) {
    $moduleParts = explode(" -> ", $line);
    $moduleType = substr($moduleParts[0], 0, 1);
    $name = $moduleParts[0] == "broadcaster" ? "broadcaster" : substr($moduleParts[0], 1);
    $module = [
        "type" => $moduleParts[0] == "broadcaster" ? "broadcaster" : $moduleType,
        "destinations" => explode(", ", $moduleParts[1]),
        "name" => $name
    ];
    $modules[$name] = $module;
}

foreach ($modules as $name => $module) {
    if ($module["type"] == "&") {
        foreach ($modules as $name2 => $module2) {
            foreach ($module2["destinations"] as $destination) {
                if ($destination == $name) {
                    $modules[$name]["inputs"][$name2] = true;
                }
            }
        }
    }
    if ($module["type"] == "%") {
        $modules[$name]["state"] = false;
    }
}

$queue = [];
$countLowpulses = 0;
$countHighpulses = 0;

for ($i = 0; $i < 1000; $i++) {
    $queue[] = [
        "destination" => "broadcaster",
        "lowpulse" => true,
        "origin" => "button"
    ];
    while (count($queue) > 0) {
        $pulse = array_shift($queue);
        echo $pulse["origin"] . " -> " . $pulse["destination"] . " (" . ($pulse["lowpulse"] ? "low" : "high") . ")\n";
        if ($pulse["lowpulse"]) {
            $countLowpulses++;
        } else {
            $countHighpulses++;
        }
        if (!isset($modules[$pulse["destination"]]))
            continue;
        $currentModule = &$modules[$pulse["destination"]];

        if ($currentModule["type"] == "broadcaster") {
            foreach ($currentModule["destinations"] as $destination) {
                $queue[] = [
                    "destination" => $destination,
                    "lowpulse" => $pulse["lowpulse"],
                    "origin" => $currentModule["name"]
                ];
            }
        }

        if ($currentModule["type"] == "%") {
            if (!$pulse["lowpulse"]) {
                continue;
            } else {
                echo "FlipFlop " . $currentModule["state"] . " -> " . !$currentModule["state"] . "\n";
                $currentModule["state"] = !$currentModule["state"];
                foreach ($currentModule["destinations"] as $destination) {
                    $queue[] = [
                        "destination" => $destination,
                        "lowpulse" => !$currentModule["state"],
                        "origin" => $currentModule["name"]
                    ];
                }
            }
        }

        if ($currentModule["type"] == "&") {
            $currentModule["inputs"][$pulse["origin"]] = $pulse["lowpulse"];
            $lowpulse = true;
            foreach ($currentModule["inputs"] as $input) {
                if ($input) {
                    $lowpulse = false;
                    break;
                }
            }
            foreach ($currentModule["destinations"] as $destination) {
                $queue[] = [
                    "destination" => $destination,
                    "lowpulse" => $lowpulse,
                    "origin" => $currentModule["name"]
                ];
            }
        }
        // echo "Lowpulses: " . $countLowpulses . "; Highpulses: " . $countHighpulses . "\n";
        // echo print_r($queue, true);
        // if ($countLowpulses == 2) {
        //     break;
        // }
    }
}

echo "Lowpulses: " . $countLowpulses . "; Highpulses: " . $countHighpulses . "\n";
echo "Task 1: " . $countLowpulses * $countHighpulses . "\n";
