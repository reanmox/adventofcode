<?php

enum HandType: Int
{
    case HighCard = 0;
    case Pair = 1;
    case TwoPair = 2;
    case ThreeOfAKind = 3;
    case fullHouse = 4;
    case fourOfAKind = 5;
    case fiveOfAKind = 6;
}

$highCardValues = [
    "A" => 14,
    "K" => 13,
    "Q" => 12,
    "J" => 11,
    "T" => 10,
    "9" => 9,
    "8" => 8,
    "7" => 7,
    "6" => 6,
    "5" => 5,
    "4" => 4,
    "3" => 3,
    "2" => 2,
];

$filepath = "input/7.txt";
$hands = [];
$useJoker = true;

if ($useJoker) {
    $highCardValues["J"] = 1;
}

// read input data
$contents = file_get_contents($filepath);

// split content on new line
$contents = explode("\n", $contents);

foreach ($contents as $index => $line) {
    $split = explode(" ", $line);
    $hands[] = [
        "cards" => $split[0],
        "bid" => $split[1],
        "type" => getHandType($split[0], $useJoker),
    ];
}

// sort hands
usort($hands, function ($a, $b) {
    if ($a["type"]->value === $b["type"]->value) {
        return compareHighCard($a["cards"], $b["cards"]);
    }
    return $a["type"]->value <=> $b["type"]->value;
});

echo print_r($hands, true);

$sum = 0;
foreach ($hands as $index => $hand) {
    $sum += $hand["bid"] * ($index + 1);
}
echo "Task " . ($useJoker ? 2 : 1) . ": " . $sum . "\n";

// room for helper functions
function getHandType($hand, $useJoker = false): HandType
{
    $cards = str_split($hand);

    if ($useJoker && in_array("J", $cards) && $hand !== "JJJJJ") {
        $counts = array_count_values(str_split(str_replace("J", "", $hand)));
        $mostCommon = array_search(max($counts), $counts);
        $cards = str_split(str_replace("J", $mostCommon, $hand));
    }

    $unique = array_unique($cards);
    $counts = array_count_values($cards);

    if (count($unique) === 1) {
        return HandType::fiveOfAKind;
    }

    if (count($unique) === 2) {
        if (in_array(4, $counts)) {
            return HandType::fourOfAKind;
        }
        return HandType::fullHouse;
    }

    if (count($unique) === 3) {
        if (in_array(3, $counts)) {
            return HandType::ThreeOfAKind;
        }
        return HandType::TwoPair;
    }

    if (count($unique) === 4) {
        return HandType::Pair;
    }

    return HandType::HighCard;
}

function compareHighCard($a, $b)
{
    global $highCardValues;
    $a = str_split($a);
    $b = str_split($b);

    for ($i = 0; $i < count($a); $i++) {
        if ($a[$i] !== $b[$i]) {
            return $highCardValues[$a[$i]] <=> $highCardValues[$b[$i]];
        }
    }

    return 0;
}
