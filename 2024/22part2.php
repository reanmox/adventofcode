<?php

$filepath = "input/22.txt";
$numbers = explode("\n", file_get_contents($filepath));

$results = [];

$sum = 0;
foreach ($numbers as $index => $n) {
    $r = [];
    $r[] = [
        $n,
        substr($n, -1),
    ];
    for ($i = 0; $i < 2000; $i++) {
        // Step 1
        $b = $n * 64;
        $n = prune(mix($n, $b));

        // Step 2
        $b = floor($n / 32);
        $n = prune(mix($n, $b));

        // Step 3
        $b = $n * 2048;
        $n = prune(mix($n, $b));

        $r[] = [
            $n,
            substr($n, -1),
            substr($n, -1) - $r[$i][1],
        ];
    }
    $results[$numbers[$index]] = $r;
    echo $numbers[$index] . ": $n" . PHP_EOL;
    $sum += $n;
}

$allSequences = [];
$numberSequences = [];

foreach ($numbers as $index => $n) {
    $sequences = [];
    foreach ($results[$n] as $index => $r) {
        if ($index > 3) {
            $sequence = [
                $results[$n][$index - 3][2],
                $results[$n][$index - 2][2],
                $results[$n][$index - 1][2],
                $r[2]
            ];
            if (!array_key_exists(implode(", ", $sequence), $sequences))
                $sequences[implode(", ", $sequence)] = $r[1];
            if (!array_key_exists(implode(", ", $sequence), $allSequences))
                $allSequences[implode(", ", $sequence)] = 1;
            else
                $allSequences[implode(", ", $sequence)]++;
        }
    }
    $numberSequences[$n] = $sequences;
    // $allSequences = array_merge($allSequences, array_keys($sequences));
}

echo "All sequences: " . count($allSequences) . PHP_EOL;

$max = 0;
$bestSequence = "";
$uniqueSequences = 0;
foreach ($allSequences as $s => $count) {
    if ($count < 10) {
        $uniqueSequences++;
        continue;
    }
    echo "Sequence: " . $s . " : " . $count . PHP_EOL;
    $sum = 0;
    foreach ($numberSequences as $number => $se) {
        if (isset($se[$s])) {
            $sum += $se[$s];
        }
    }
    if ($sum > $max) {
        $max = $sum;
        $bestSequence = $s;
    }
}

echo "Unique sequences: " . $uniqueSequences . PHP_EOL;
echo "Part 2: " . $bestSequence . " : " . $max . PHP_EOL;

function mix($a, $b)
{
    return $a ^ $b;
}

function prune($a)
{
    return $a % 16777216;
}
