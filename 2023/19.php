<?php

$filepath = "input/19.txt";
$workflows = [];
$parts = [];

$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);

foreach (explode("\n", $contents[0]) as $line) {
    $workflowParts = explode("{", $line);
    $workflowSections = explode(",", str_replace("}", "", $workflowParts[1]));
    foreach ($workflowSections as $workflowSection) {
        if (!str_contains($workflowSection, ":")) {
            $workflow = [
                "destination" => $workflowSection,
                "condition" => null,
                "key" => null,
                "value" => null
            ];
        } else {
            echo $workflowSection . "\n";
            $workflowDetails = explode(":", $workflowSection);
            $workflow = [
                "destination" => $workflowDetails[1],
                "condition" => substr($workflowDetails[0], 1, 1),
                "key" => substr($workflowDetails[0], 0, 1),
                "value" => substr($workflowDetails[0], 2)
            ];
        }
        $workflows[$workflowParts[0]][] = $workflow;
    }
}

foreach (explode("\n", $contents[1]) as $line) {
    $partDetails = explode(",", str_replace(["{", "}"], "", $line));
    $part = [];
    foreach ($partDetails as $partDetail) {
        $partSection = explode("=", $partDetail);
        $part[$partSection[0]] = $partSection[1];
    }
    $parts[] = $part;
}

// echo print_r($workflows, true);
// echo print_r($parts, true);

$sum = 0;
foreach ($parts as $part) {
    $part["status"] = startWorkflow("in", $part);
    if ($part["status"] == "A") {
        $sum += $part["x"] + $part["m"] + $part["a"] + $part["s"];
    }
}
echo "Task 1: " . $sum . "\n";

function startWorkflow($key, $part)
{
    global $workflows;
    $workflow = $workflows[$key];
    foreach ($workflow as $workflowStep) {
        if ($workflowStep["condition"] == null) {
            $destination = $workflowStep["destination"];
            if ($destination == "A" || $destination == "R") {
                return $workflowStep["destination"];
            } else {
                return startWorkflow($destination, $part);
            }
        } else {
            $condition = $workflowStep["condition"];
            $destination = $workflowStep["destination"];
            if ($condition == ">") {
                if ($part[$workflowStep["key"]] > $workflowStep["value"]) {
                    if ($destination == "A" || $destination == "R") {
                        return $workflowStep["destination"];
                    } else {
                        return startWorkflow($destination, $part);
                    }
                }
            } else if ($condition == "<") {
                if ($part[$workflowStep["key"]] < $workflowStep["value"]) {
                    if ($destination == "A" || $destination == "R") {
                        return $workflowStep["destination"];
                    } else {
                        return startWorkflow($destination, $part);
                    }
                }
            }
        }
    }
}
