<?php

$filepath = "input/9.txt";
$map = [];

$contents = file_get_contents($filepath);
$map = str_split($contents);

$id = 0;
$blocks = [];
$freeBlocks = [];
$fileBlocks = [];

foreach ($map as $key => $value) {
    $isFree = $key % 2 == 1;

    if ($isFree) {
        $freeBlocks[] = ["pos" => count($blocks), "size" => $value];
    } else {
        $fileBlocks[$id] = ["pos" => count($blocks), "size" => $value];
    }

    for ($i = 0; $i < $value; $i++) {
        $blocks[] = $isFree ? "." : $id;
    }

    if (!$isFree) {
        $id++;
    }
}

for ($i = count($blocks) - 1; $i >= 0; $i--) {
    if ($blocks[$i] != ".") {
        $id = $blocks[$i];
        $size = $fileBlocks[$id]["size"];
        $freeBlockIndex = findFreeBlock($size, $i);
        if ($freeBlockIndex === false) {
            $i -= $size - 1;
            continue;
        }

        $pos = $freeBlocks[$freeBlockIndex]["pos"];

        for ($j = 0; $j < $size; $j++) {
            $blocks[$pos + $j] = $id;
            $blocks[$i - $j] = ".";
        }
        $freeBlocks[$freeBlockIndex]["size"] -= $size;
        $freeBlocks[$freeBlockIndex]["pos"] += $size;

        $i -= $size - 1;
    }
}

$sum = 0;
foreach ($blocks as $key => $value) {
    $sum += $value == "." ? 0 : $value * $key;
}
echo "Part 2: $sum\n";

function findFreeBlock($size, $index)
{
    global $freeBlocks;
    foreach ($freeBlocks as $key => $fb) {
        if ($fb["size"] >= $size && $fb["pos"] < $index) {
            return $key;
        }
    }
    return false;
}
