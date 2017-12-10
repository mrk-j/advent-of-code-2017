<?php

$input = file_get_contents('input.txt');

$lengths = array_map('intval', explode(',', $input));
$list = range(0, 255);
$listLength = count($list);

$pos = 0;
$skip = 0;

foreach ($lengths as $length) {
    if ($listLength < $length) {
        continue;
    }

    if ($pos + $length < $listLength) {
        $replaceWith = array_reverse(array_slice($list, $pos, $length));

        array_splice($list, $pos, $length, $replaceWith);
    } else {
        $replaceWithStart = array_slice($list, $pos, ($listLength - $pos));
        $replaceWithEnd = array_slice($list, 0, ($length - ($listLength - $pos)));

        $replaceWith = array_reverse(array_merge($replaceWithStart, $replaceWithEnd));

        $replaceWithStart = array_splice($replaceWith, 0, ($listLength - $pos));
        $replaceWithEnd = $replaceWith;

        array_splice($list, $pos, ($listLength - $pos), $replaceWithStart);
        array_splice($list, 0, ($length - ($listLength - $pos)), $replaceWithEnd);
    }

    $pos = ($pos + $length + $skip) % $listLength;
    $skip++;
}

echo 'The result for part 1 is ' . ($list[0] * $list[1]) . PHP_EOL;

// Part 2
$lengths = array_merge(array_map('ord', array_filter(str_split($input), function ($i) {
    return $i !== '';
})), [17, 31, 73, 47, 23]);
$list = range(0, 255);
$listLength = count($list);

$pos = 0;
$skip = 0;

foreach (range(1, 64) as $round) {
    foreach ($lengths as $length) {
        if ($listLength < $length) {
            continue;
        }

        if ($pos + $length < $listLength) {
            $replaceWith = array_reverse(array_slice($list, $pos, $length));

            array_splice($list, $pos, $length, $replaceWith);
        } else {
            $replaceWithStart = array_slice($list, $pos, ($listLength - $pos));
            $replaceWithEnd = array_slice($list, 0, ($length - ($listLength - $pos)));

            $replaceWith = array_reverse(array_merge($replaceWithStart, $replaceWithEnd));

            $replaceWithStart = array_splice($replaceWith, 0, ($listLength - $pos));
            $replaceWithEnd = $replaceWith;

            array_splice($list, $pos, ($listLength - $pos), $replaceWithStart);
            array_splice($list, 0, ($length - ($listLength - $pos)), $replaceWithEnd);
        }

        $pos = ($pos + $length + $skip) % $listLength;
        $skip++;
    }
}

$parts = array_map(function ($a) {
    $result = $a[0];

    foreach (range(1, count($a) - 1) as $i) {
        $result = $result ^ $a[$i];
    }

    return $result;
}, array_chunk($list, 16));

$hash = implode('', array_map(function ($part) {
    $hex = dechex($part);

    if (strlen($hex) == 1) {
        $hex = '0' . $hex;
    }

    return $hex;
}, $parts));

echo 'The hash is ' . $hash . PHP_EOL;