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
foreach ($updates as $update) {
    $valid = true;
    foreach ($rules as $rule) {
        if (!checkRule($update, $rule)) {
            $valid = false;
            break;
        }
    }
    if ($valid) {
        $sum += $update[floor(count($update) / 2)];
    }
}

echo "Part 1: $sum\n";

function checkRule($update, $rule)
{
    $foundFirst = false;
    foreach ($update as $page) {
        if ($page == $rule[0]) {
            $foundFirst = true;
        }
        if (!$foundFirst && $page == $rule[1] && in_array($rule[0], $update)) {
            return false;
        }
    }
    return true;
}
