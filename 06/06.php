<?php

$input = file_get_contents('input.txt');

$banks = array_map('intval', explode("\t", $input));

$configurations = [];
$configurationFound = false;
$configurationsInLoop = [];

$cycles = 0;
$cyclesInLoop = 0;

do {
    if ($configurationFound) {
        $configurationsInLoop[] = implode('', $banks);
    } else {
        $configurations[] = implode('', $banks);
    }

    $highestValue = max($banks);
    $key = array_search($highestValue, $banks);

    $banks[$key] = 0;

    foreach (range(1, $highestValue) as $redistribute) {
        $key++;

        if (!isset($banks[$key])) {
            $key = 0;
        }

        $banks[$key]++;
    }

    if (!$configurationFound) {
        $cycles++;
    } else {
        $cyclesInLoop++;
    }

    $configuration = implode('', $banks);

    if (!$configurationFound && in_array($configuration, $configurations)) {
        echo "Same configuration after {$cycles} cycles" . PHP_EOL;

        $configurationFound = true;
    }
} while (
    !$configurationFound ||
    ($configurationFound && !in_array($configuration, $configurationsInLoop))
);

echo "Same configuration after {$cyclesInLoop} cycles in loop" . PHP_EOL;