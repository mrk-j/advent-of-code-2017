<?php

$input = file_get_contents('input.txt');

$numbers = str_split($input);

$sum = array_sum(array_filter($numbers, function ($value, $key) use ($numbers) {
    $compareKey = $key + 1 < count($numbers) ? $key + 1 : 0;

    return $numbers[$compareKey] === $value;
}, ARRAY_FILTER_USE_BOTH));

echo "The solution to the captcha is {$sum}" . PHP_EOL;

$sum = array_sum(array_filter($numbers, function ($value, $key) use ($numbers) {
    $compareKey = $key + (count($numbers) / 2);

    if ($compareKey >= count($numbers)) {
        $compareKey -= count($numbers);
    }

    return $numbers[$compareKey] === $value;
}, ARRAY_FILTER_USE_BOTH));

echo "The solution to the second captcha is {$sum}" . PHP_EOL;