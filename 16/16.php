<?php

$input = file_get_contents('input.txt');

$moves = explode(',', $input);

$programs = range('a', 'p');

$originalPrograms = $programs;
$inOriginalOrderAt = false;
$iteration = 0;
$positions = [];

while (!$inOriginalOrderAt) {
    foreach ($moves as $move) {
        $instruction = substr($move, 0, 1);
        $instructionData = substr($move, 1);

        if ($instruction === 's') { // spin
            $spinNumber = count($programs) - ($instructionData % count($programs));

            array_splice($programs, 0, 0, array_splice($programs, $spinNumber));
        } elseif ($instruction === 'x') { // swap by index
            $indexes = explode('/', $instructionData);

            $tmpValue = $programs[$indexes[0]];
            $programs[$indexes[0]] = $programs[$indexes[1]];
            $programs[$indexes[1]] = $tmpValue;
        } elseif ($instruction === 'p') { // swap by name
            $names = explode('/', $instructionData);
            $indexes = [
                array_search($names[0], $programs),
                array_search($names[1], $programs),
            ];

            $tmpValue = $programs[$indexes[0]];
            $programs[$indexes[0]] = $programs[$indexes[1]];
            $programs[$indexes[1]] = $tmpValue;
        }
    }

    $iteration++;

    if ($programs === $originalPrograms) {
        $inOriginalOrderAt = $iteration;
    }

    if ($iteration === 1) {
        echo 'The order after the dance (' . $iteration . ') is ' . implode('', $programs) . PHP_EOL;
    }

    $positions[] = $programs;
}

$endState = 1000000000 % $inOriginalOrderAt;

echo 'The order after the dance (' . $iteration . ') is ' . implode('', $positions[$endState - 1]) . PHP_EOL;
