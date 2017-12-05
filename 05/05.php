<?php

$input = file_get_contents('input.txt');

$instructions = array_map('intval', explode("\n", $input));

$currentInstruction = 0;

$steps = 0;

while (isset($instructions[$currentInstruction])) {
    $newInstruction = $currentInstruction + $instructions[$currentInstruction];

    $instructions[$currentInstruction]++;

    $currentInstruction = $newInstruction;

    $steps++;
}

echo "The number of steps is {$steps}" . PHP_EOL;

$instructions = array_map('intval', explode("\n", $input));

$currentInstruction = 0;

$steps = 0;

while (isset($instructions[$currentInstruction])) {
    $newInstruction = $currentInstruction + $instructions[$currentInstruction];

    if ($instructions[$currentInstruction] >= 3) {
        $instructions[$currentInstruction]--;
    } else {
        $instructions[$currentInstruction]++;
    }

    $currentInstruction = $newInstruction;

    $steps++;
}

echo "The number of steps is {$steps}" . PHP_EOL;