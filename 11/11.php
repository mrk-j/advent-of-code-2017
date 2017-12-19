<?php
$input = file_get_contents('input.txt');

$steps = array_filter(explode(',', $input));

# https://www.redblobgames.com/grids/hexagons/#coordinates-cube

$pos = [
    'x' => 0,
    'y' => 0,
    'z' => 0,
];

$max = 0;

foreach ($steps as $step) {
    if ($step === 'n') {
        $pos['y']++;
        $pos['z']--;
    } elseif ($step === 'ne') {
        $pos['x']++;
        $pos['z']--;
    } elseif ($step === 'se') {
        $pos['x']++;
        $pos['y']--;
    } elseif ($step === 's') {
        $pos['y']--;
        $pos['z']++;
    } elseif ($step === 'sw') {
        $pos['x']--;
        $pos['z']++;
    } elseif ($step === 'nw') {
        $pos['x']--;
        $pos['y']++;
    }

    if((array_sum(array_map('abs', $pos)) / 2) > $max) {
        $max = (array_sum(array_map('abs', $pos)) / 2);
    }
}

# https://www.redblobgames.com/grids/hexagons/#distances

echo 'The number of steps is: ' . (array_sum(array_map('abs', $pos)) / 2) . PHP_EOL;
echo 'The maximum number of steps is: ' . $max . PHP_EOL;