<?php

$filepath = "input/21.txt";
$codes = explode("\n", file_get_contents($filepath));

$nk = [
    "7" => ["y" => 0, "x" => 0],
    "8" => ["y" => 0, "x" => 1],
    "9" => ["y" => 0, "x" => 2],
    "4" => ["y" => 1, "x" => 0],
    "5" => ["y" => 1, "x" => 1],
    "6" => ["y" => 1, "x" => 2],
    "1" => ["y" => 2, "x" => 0],
    "2" => ["y" => 2, "x" => 1],
    "3" => ["y" => 2, "x" => 2],
    "0" => ["y" => 3, "x" => 1],
    "A" => ["y" => 3, "x" => 2],
];

$dk = [
    "^" => ["y" => 0, "x" => 1],
    "A" => ["y" => 0, "x" => 2],
    "<" => ["y" => 1, "x" => 0],
    "v" => ["y" => 1, "x" => 1],
    ">" => ["y" => 1, "x" => 2],
];

$orders = [
    [">", "v", "<", "^"],
    [">", "v", "^", "<"],
    [">", "<", "v", "^"],
    [">", "<", "^", "v"],
    [">", "^", "v", "<"],
    [">", "^", "<", "v"],
    ["v", ">", "^", "<"],
    ["v", ">", "<", "^"],
    ["v", "^", ">", "<"],
    ["v", "^", "<", ">"],
    ["v", "<", ">", "^"],
    ["v", "<", "^", ">"],
    ["<", ">", "v", "^"],
    ["<", ">", "^", "v"],
    ["<", "^", ">", "v"],
    ["<", "^", "v", ">"],
    ["<", "v", ">", "^"],
    ["<", "v", "^", ">"],
    ["^", ">", "v", "<"],
    ["^", ">", "<", "v"],
    ["^", "<", ">", "v"],
    ["^", "<", "v", ">"],
    ["^", "v", ">", "<"],
    ["^", "v", "<", ">"],
];

$complexity = 0;
foreach ($codes as $code) {
    $bestSolution = 0;
    foreach ($orders as $order) {
        $y = 3;
        $x = 2;
        $moves = [];
        foreach (str_split($code) as $c) {
            $moves = array_merge($moves, move($c, true, $order));
        }
        //echo implode("", $moves) . PHP_EOL;

        $y = 0;
        $x = 2;
        $moves2 = [];
        foreach ($moves as $c) {
            $moves2 = array_merge($moves2, move($c, false, $order));
        }
        //echo implode("", $moves2) . PHP_EOL;

        $y = 0;
        $x = 2;
        $moves3 = [];
        foreach ($moves2 as $c) {
            $moves3 = array_merge($moves3, move($c, false, $order));
        }
        //echo implode("", $moves3) . PHP_EOL;

        if ($bestSolution == 0 || count($moves3) < $bestSolution) {
            $bestSolution = count($moves3);
        }
    }

    $np = trim($code, "A");
    echo "Code $code has complexity " . $bestSolution . " * " . $np . PHP_EOL;
    $complexity += $bestSolution * $np;
}
echo "Part 1: $complexity" . PHP_EOL;

function move($goal, $numeric, $order)
{
    global $y, $x, $nk, $dk;

    $moves = [];
    $k = $numeric ? $nk : $dk;
    if ($numeric && $y == 3 && in_array($goal, ["1", "4", "7"])) {
        $order = [">", "^", "v", "<"];
    }
    if (!$numeric && $y == 0 && $goal == "<") {
        $order = [">", "v", "<", "^"];
    }

    while ($k[$goal]["y"] != $y || $k[$goal]["x"] != $x) {
        foreach ($order as $o) {
            if ($x < $k[$goal]["x"] && $o == ">") {
                $x++;
                $moves[] = ">";
                break;
            } else if ($y > $k[$goal]["y"] && $o == "^") {
                $y--;
                $moves[] = "^";
                break;
            } else if ($y < $k[$goal]["y"] && $o == "v") {
                $y++;
                $moves[] = "v";
                break;
            } else if ($x > $k[$goal]["x"] && $o == "<") {
                $x--;
                $moves[] = "<";
                break;
            }
        }
    }
    $moves[] = "A";

    return $moves;
}
