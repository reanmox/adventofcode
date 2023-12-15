<?php

$filepath = "input/15.txt";
$result = [];
$boxes = array_fill(0, 256, []);

// read input data
$contents = file_get_contents($filepath);
// $contents = "rn=1,cm-,qp=3,cm=2,qp-,pc=4,ot=9,ab=5,pc-,pc=6,ot=7";
$contents = explode(",", $contents);

foreach ($contents as $string) {
    $current = 0;
    $label = "";
    foreach (str_split($string) as $char) {
        if ($char != "=" && $char != "-") {
            $current += ord($char);
            $current *= 17;
            $current = $current % 256;
            $label .= $char;
        }
        if ($char == "-") {
            // remove label from box
            if (in_array($label, array_column($boxes[$current], "label"))) {
                $index = array_search($label, array_column($boxes[$current], "label"));
                unset($boxes[$current][$index]);
                $boxes[$current] = array_values($boxes[$current]);
            }
            break;
        }
        if ($char == "=") {
            $focalLength = $string[strlen($string) - 1];
            if (in_array($label, array_column($boxes[$current], "label"))) {
                $index = array_search($label, array_column($boxes[$current], "label"));
                $boxes[$current][$index] = ["label" => $label, "focalLength" => $focalLength];
            } else {
                $boxes[$current][] = ["label" => $label, "focalLength" => $focalLength];
            }
            break;
        }
    }
    $result[$string] = $current;
}

$sum = 0;
foreach ($boxes as $index => $box) {
    foreach ($box as $slotNumber => $lens) {
        $sum += (1 + $index) * ($slotNumber + 1) * $lens["focalLength"];
    }
}
echo "Task 2 " . $sum . "\n";

// echo print_r($boxes[3], true) . "\n";
