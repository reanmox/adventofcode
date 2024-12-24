<?php

$filepath = "input/24.txt";
$contents = explode("\n\n", file_get_contents($filepath));
$wires_raw = explode("\n", $contents[0]);
$gates_raw = explode("\n", $contents[1]);

$wires = [];
$gates = [];

foreach ($wires_raw as $w) {
    $wire_parts = explode(": ", $w);
    $wires[$wire_parts[0]] = $wire_parts[1];
}

foreach ($gates_raw as $g) {
    $gate_parts = explode(" -> ", $g);
    $gate_inputs = explode(" ", $gate_parts[0]);
    $gates[] = [
        "input1" => $gate_inputs[0],
        "input2" => $gate_inputs[2],
        "output" => $gate_parts[1],
        "gate" => $gate_inputs[1]
    ];
}

$all_gates = false;
while (!$all_gates) {
    $all_gates = true;
    foreach ($gates as $gate) {
        if (!isset($wires[$gate["input1"]]) || !isset($wires[$gate["input2"]])) {
            $all_gates = false;
            continue;
        }
        switch ($gate["gate"]) {
            case "AND":
                $wires[$gate["output"]] = ($wires[$gate["input1"]] && $wires[$gate["input2"]]) ? 1 : 0;
                break;
            case "OR":
                $wires[$gate["output"]] = ($wires[$gate["input1"]] || $wires[$gate["input2"]]) ? 1 : 0;
                break;
            case "XOR":
                $wires[$gate["output"]] = ($wires[$gate["input1"]] != $wires[$gate["input2"]]) ? 1 : 0;
                break;
            default:
                echo "Unknown gate: " . $gate["gate"];
        }
    }
}

$bits = "";
$curren_bit = 0;
while (isset($wires["z" . sprintf('%02d', $curren_bit)])) {
    $bits = $wires["z" . sprintf('%02d', $curren_bit)] . $bits;
    $curren_bit++;
}

echo "Part 1: $bits : " .  bindec($bits) . "\n";
