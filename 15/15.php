<?php

$valueA = 873;
$valueB = 583;

$factorA = 16807;
$factorB = 48271;

$i = 1;
$sum = 0;

while ($i <= 40000000) {
    $valueA = $valueA * $factorA % 2147483647;
    $valueB = $valueB * $factorB % 2147483647;

    $binaryA = str_pad(substr(decbin($valueA), -16), 16, 0, STR_PAD_LEFT);
    $binaryB = str_pad(substr(decbin($valueB), -16), 16, 0, STR_PAD_LEFT);

    if ($binaryA === $binaryB) {
        $sum++;
    }

    $i++;
}

echo "After 40 million pairs, the judges final count is {$sum}" . PHP_EOL;

$pairs = 0;

$sum = 0;

$valueA = 873;
$valueB = 583;

$binaryA = false;
$binaryB = false;

while($pairs < 5000000) {
    if($binaryA === false) {
        $valueA = $valueA * $factorA % 2147483647;

        if($valueA % 4 === 0) {
            $binaryA = str_pad(substr(decbin($valueA), -16), 16, 0, STR_PAD_LEFT);
        }
    } elseif($binaryB === false) {
        $valueB = $valueB * $factorB % 2147483647;

        if ($valueB % 8 === 0) {
            $binaryB = str_pad(substr(decbin($valueB), -16), 16, 0, STR_PAD_LEFT);
        }
    }

    if($binaryA !== false && $binaryB !== false) {
        $pairs++;

        if($binaryA === $binaryB) {
            $sum++;
        }

        $binaryA = false;
        $binaryB = false;
    }
}

echo "After 5 million pairs, the judges final count is {$sum}" . PHP_EOL;