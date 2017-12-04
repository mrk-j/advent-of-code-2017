<?php

$input = file_get_contents('input.txt');

$lines = explode("\n", $input);

$numberOfValidPassphrases = count(array_filter($lines, function ($passphrase) {
    $words = explode(' ', $passphrase);

    if (count($words) > 1) {
        return count($words) === count(array_unique($words));
    }

    return false;
}));

echo "The number of valid passphrases is {$numberOfValidPassphrases}" . PHP_EOL;

$numberOfValidPassphrases = count(array_filter($lines, function ($passphrase) {
    $words = explode(' ', $passphrase);

    if (count($words) > 1) {
        return count($words) === count(array_unique(array_map(function ($word) {
            $array = str_split($word);

            asort($array);

            return implode('', $array);
        }, $words)));
    }

    return false;
}));

echo "The number of valid passphrases is {$numberOfValidPassphrases}" . PHP_EOL;