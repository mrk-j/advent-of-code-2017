<?php

ini_set('memory_limit', '4G');

$input = 349;

$memory = [0];
$pos = 0;

foreach (range(1, 2017) as $i) {
    $insertAt = (($pos + $input) % count($memory)) + 1;

    array_splice($memory, $insertAt, 0, [$i]);

    $pos = $insertAt;
}

$nextValue = $memory[array_search(2017, $memory) + 1];

echo "The next value is {$nextValue}" . PHP_EOL;

$totalLength = 1;
$valueAfterZero = 0;
$pos = 0;

foreach (range(1, 50000001) as $i) {
    $insertAt = (($pos + $input) % $totalLength) + 1;

    if($insertAt === 1) {
        $valueAfterZero = $i;
    }

    $pos = $insertAt;
    $totalLength++;

    echo "Currently at: $i\r";
}

echo "The value after 0 is {$valueAfterZero}". PHP_EOL;