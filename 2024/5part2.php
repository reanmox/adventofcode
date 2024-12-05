<?php

$filepath = "input/5.txt";

$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);

$contents[0] = explode("\n", $contents[0]);
$contents[1] = explode("\n", $contents[1]);

foreach ($contents[0] as $rule) {
    $rules[] = explode("|", $rule);
}

foreach ($contents[1] as $update) {
    $updates[] =  explode(",", $update);
}

$sum = 0;
foreach ($updates as &$update) {
    $valid = true;
    $allChecked = false;

    while (!$allChecked) {
        $allChecked = true;
        foreach ($rules as $rule) {
            $check = checkRule($update, $rule);
            if (!$check["result"]) {
                $allChecked = false;
                $valid = false;
                $update[$check["indexSecond"]] = $check["rule"][0];
                $update[$check["indexFirst"]] = $check["rule"][1];
                break;
            }
        }
    }

    if (!$valid) {
        echo "Updated: " . implode(",", $update) . "\n";
        $sum += $update[floor(count($update) / 2)];
    }
}

echo "Part 2: $sum\n";

function checkRule($update, $rule)
{
    $foundFirst = false;
    foreach ($update as $index => $page) {
        if ($page == $rule[0]) {
            $foundFirst = true;
        }
        if (!$foundFirst && $page == $rule[1] && in_array($rule[0], $update)) {
            //echo "Found $rule[1] before $rule[0]\n";
            return [
                "result" => false,
                "rule" => $rule,
                "indexSecond" => $index,
                "indexFirst" => array_search($rule[0], $update)
            ];
        }
    }
    return [
        "result" => true,
    ];
}
