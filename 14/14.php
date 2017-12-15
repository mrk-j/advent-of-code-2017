<?php

require('helpers.php');

$input = 'ugkiagan';

$usedSpace = 0;

$grid = [];

foreach (range(0, 127) as $i) {
    $hash = knot_hash($input . '-' . $i);
    $binary = binary($hash);

    $row = str_split($binary);
    $grid[] = array_map(function ($value) {
        return $value == 1 ? '#' : '.';
    }, $row);
    $counts = array_count_values($row);

    if (isset($counts[1])) {
        $usedSpace += $counts[1];
    }
}

echo "Total used is {$usedSpace}" . PHP_EOL;

$group = 1;

foreach (range(0, 127) as $i) {
    foreach (range(0, 127) as $j) {
        if ($grid[$i][$j] !== '#') {
            continue;
        }

        find_all_connected_squares($grid, $group, $i, $j);

        $group++;
    }
}

echo 'The number of regions is ' . ($group - 1) . PHP_EOL;