<?php

$input = file_get_contents('input.txt');

$lines = explode("\n", $input);

$checksum = array_sum(array_map(function ($line) {
    $values = explode("\t", $line);

    return max($values) - min($values);
}, $lines));

echo "The checksum is {$checksum}" . PHP_EOL;

$checksum = array_sum(array_map(function ($line) {
    $result = 0;
    $values = explode("\t", $line);

    foreach ($values as $k1 => $v1) {
        foreach ($values as $k2 => $v2) {
            if ($k1 !== $k2 && $v1 % $v2 === 0) {
                $result += $v1 / $v2;
            }
        }
    }

    return $result;
}, $lines));

echo "The second checksum is {$checksum}" . PHP_EOL;