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
$programString = implode(",", $program);

$num = 1;

while (true) {
    $register["A"] = $num;
    $register["B"] = 0;
    $register["C"] = 0;
    $output = [];
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

        // if ($instruction == 5) {
        //     $outputIndex = count($output) - 1;
        //     if ($program[$outputIndex] != $output[$outputIndex]) {
        //         break;
        //     }
        // }

        if (!$jump)
            $pointer += 2;
    }

    // Compare the output with the program, in reverse
    $failed = false;
    for ($i = 1; $i <= count($output); $i++) {
        echo "Compare: " . $program[count($program) - $i] . " with " . $output[count($output) - $i] . PHP_EOL;
        if ($program[count($program) - $i] != $output[count($output) - $i]) {
            echo "Failed at $num" . PHP_EOL;
            $num++;
            $failed = true;
        }
    }
    if ($failed) {
        continue;
    }

    if (count($program) != count($output)) {
        $num *= 8;
    }

    $outputString = implode(",", $output);
    if ($programString == $outputString) {
        echo "Found a match $num" . PHP_EOL;
        break;
    }
}

echo "Part 2: $num " . $outputString . PHP_EOL;

function adv($co, $r = "A")
{
    global $register;
    $result = floor($register["A"] / pow(2, $co));
    if ($r == "A") {
        echo "A: " . $register["A"] . " / 2^" . $co . " = " . $result . PHP_EOL;
    }
    $register[$r] = $result;
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
    global $output, $register;
    // print_r($register);
    // echo "Output: $co, result: " . $co % 8 . PHP_EOL;
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
