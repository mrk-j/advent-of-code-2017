<?php

ini_set('memory_limit', '4G');

require('helpers.php');

$input = file_get_contents('input.txt');

$lines = explode(PHP_EOL, $input);

$rules = [];

foreach ($lines as $line) {
    $rule = explode(' => ', $line);

    $matchRule = rule2array($rule[0]);
    $matchRuleFlipped = flip($matchRule);

    $rules[array2rule($matchRule)] = $rule[1];
    $rules[array2rule($matchRuleFlipped)] = $rule[1];

    foreach (range(1, 3) as $i) {
        $matchRule = rotate($matchRule);
        $matchRuleFlipped = rotate($matchRuleFlipped);

        $rules[array2rule($matchRule)] = $rule[1];
        $rules[array2rule($matchRuleFlipped)] = $rule[1];
    }
}

$pattern = rule2array('.#./..#/###');

echo 'The number of pixels that are on after 5 iterations is: ' . count_pixels_after_iterations($pattern, $rules, 5) . PHP_EOL;
echo 'The number of pixels that are on after 18 iterations is: ' . count_pixels_after_iterations($pattern, $rules, 18) . PHP_EOL;