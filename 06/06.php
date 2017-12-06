<?php

$input = file_get_contents('input.txt');

$banks = array_map('intval', explode("\t", $input));

$configurations = [];
$configurationsInLoop = [];
$isInLoop = false;

$cycles = 0;
$cyclesInLoop = 0;

do {
    if ($isInLoop) {
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

    if (!$isInLoop) {
        $cycles++;
    } else {
        $cyclesInLoop++;
    }

    $configuration = implode('', $banks);

    if (count($configurations) % 1000 === 0) {
        echo count($configurations) . PHP_EOL;
    }

    if (!$isInLoop && in_array($configuration, $configurations)) {
        echo "Same configuration after {$cycles} cycles" . PHP_EOL;

        $isInLoop = true;
    }
} while (
    (!$isInLoop && !in_array($configuration, $configurations)) ||
    ($isInLoop && !in_array($configuration, $configurationsInLoop))
);

echo "Same configuration after {$cyclesInLoop} cycles in loop" . PHP_EOL;