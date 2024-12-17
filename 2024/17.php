<?php

$filepath = "input/17.txt";
$register = [];
$program = [];
$output = [];

$contents = file_get_contents($filepath);
$contents = explode("\n\n", $contents);

foreach (explode("\n", $contents[0]) as $index => $line) {
    $parts = explode(":", $line);
    $register[substr($parts[0], -1)] = trim($parts[1]);
}
$program = explode(",", explode(" ", $contents[1])[1]);

print_r($register);
print_r($program);

$pointer = 0;
while ($pointer < count($program) - 1) {
    $instruction = $program[$pointer];
    $operand = $program[$pointer + 1];
    $jump = false;

    switch ($instruction) {
        case 0:
            adv(getComboOperand($operand));
            break;
        case 1:
            bxl($operand);
            break;
        case 2:
            bst(getComboOperand($operand));
            break;
        case 3:
            $jump = jnz($operand);
            break;
        case 4:
            bxc();
            break;
        case 5:
            out(getComboOperand($operand));
            break;
        case 6:
            adv(getComboOperand($operand), "B");
            break;
        case 7:
            adv(getComboOperand($operand), "C");
            break;
        default:
            echo "Invalid instruction";
    }

    if (!$jump)
        $pointer += 2;
}

print_r($register);
print_r($output);

echo "Part 1: " . implode(",", $output) . PHP_EOL;

function adv($co, $r = "A")
{
    global $register;
    $register[$r] = floor($register["A"] / pow(2, $co));
}

function bxl($lo)
{
    global $register;
    $register["B"] = $register["B"] ^ $lo;
}

function bst($co)
{
    global $register;
    $register["B"] = $co % 8;
}

function jnz($lo)
{
    global $register, $pointer;
    if ($register["A"] != 0) {
        $pointer = $lo;
        return true;
    }
    return false;
}

function bxc()
{
    global $register;
    $register["B"] = $register["B"] ^ $register["C"];
}

function out($co)
{
    global $output;
    $output[] = $co % 8;
}

function getComboOperand($o)
{
    global $register;
    switch ($o) {
        case 4:
            return $register["A"];
        case 5:
            return $register["B"];
        case 6:
            return $register["C"];
        case "7":
            echo "Invalid operand";
        default:
            return $o;
    }
}
