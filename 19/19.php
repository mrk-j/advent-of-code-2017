<?php

$input = file_get_contents('input.txt');

$grid = array_map(function ($row) {
    return str_split($row);
}, explode("\n", $input));

$pos = [
    array_search('|', $grid[0]),
    0,
];

$direction = 'D';

$endReached = false;

$letters = [];

$steps = 0;

while (!$endReached) {
    $steps++;

    if (ctype_alpha($grid[$pos[1]][$pos[0]])) {
        $letters[] = $grid[$pos[1]][$pos[0]];
    }

    $checkDirections = [];

    if ($direction === 'D') {
        $checkDirections = [$direction, 'L', 'R'];
    } elseif ($direction === 'R') {
        $checkDirections = [$direction, 'U', 'D'];
    } elseif ($direction === 'U') {
        $checkDirections = [$direction, 'L', 'R'];
    } elseif ($direction === 'L') {
        $checkDirections = [$direction, 'U', 'D'];
    }

    $nextDirection = false;

    foreach ($checkDirections as $checkDirection) {
        if ($checkDirection === 'D') {
            if (isset($grid[$pos[1] + 1][$pos[0]]) && $grid[$pos[1] + 1][$pos[0]] !== ' ') {
                $pos[1]++;

                $nextDirection = 'D';

                break;
            }
        } elseif ($checkDirection === 'R') {
            if (isset($grid[$pos[1]][$pos[0] + 1]) && $grid[$pos[1]][$pos[0] + 1] !== ' ') {
                $pos[0]++;

                $nextDirection = 'R';

                break;
            }
        } elseif ($checkDirection === 'U') {
            if (isset($grid[$pos[1] - 1][$pos[0]]) && $grid[$pos[1] - 1][$pos[0]] !== ' ') {
                $pos[1]--;

                $nextDirection = 'U';

                break;
            }
        } elseif ($checkDirection === 'L') {
            if (isset($grid[$pos[1]][$pos[0] - 1]) && $grid[$pos[1]][$pos[0] - 1] !== ' ') {
                $pos[0]--;

                $nextDirection = 'L';

                break;
            }
        }
    }

    if (!$nextDirection) {
        $endReached = true;
    } else {
        $direction = $nextDirection;
    }
}

echo 'The letters found are: ' . implode('', $letters) . PHP_EOL;

echo 'The number of steps is: ' . $steps . PHP_EOL;