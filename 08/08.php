<?php

$input = file_get_contents('input.txt');

$lines = explode("\n", $input);

$registry = [];

$highest = [];

foreach ($lines as $line) {
    $parts = explode(' ', $line);

    list($registryToModify, $action, $amount, , $conditionRegistry, $conditionOperator, $conditionAmount) = $parts;

    if (!isset($registry[$conditionRegistry])) {
        $registry[$conditionRegistry] = 0;
    }

    if (!isset($registry[$registryToModify])) {
        $registry[$registryToModify] = 0;
    }

    if (
        ($conditionOperator === '>' && $registry[$conditionRegistry] > $conditionAmount) ||
        ($conditionOperator === '<' && $registry[$conditionRegistry] < $conditionAmount) ||
        ($conditionOperator === '>=' && $registry[$conditionRegistry] >= $conditionAmount) ||
        ($conditionOperator === '<=' && $registry[$conditionRegistry] <= $conditionAmount) ||
        ($conditionOperator === '==' && $registry[$conditionRegistry] == $conditionAmount) ||
        ($conditionOperator === '!=' && $registry[$conditionRegistry] != $conditionAmount)
    ) {
        $registry[$registryToModify] += $amount * ($action === 'inc' ? 1 : -1);
    }

    $highest[] = max($registry);
}

echo 'The highest value after all instructions is ' . max($registry) . PHP_EOL;
echo 'The hihest value after any instructions is ' . max($highest) . PHP_EOL;