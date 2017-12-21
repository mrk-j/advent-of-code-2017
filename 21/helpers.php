<?php

function rotate($rule)
{
    $return = [];

    for ($i = 0; $i < count($rule[0]); $i++) {
        for ($j = 0; $j < count($rule[0]); $j++) {
            $value = $rule[count($rule[0]) - $j - 1][$i];

            if (isset($return[$i])) {
                $return[$i][] = $value;
            } else {
                $return[$i] = [$value];
            }
        }
    }

    return $return;
}

function flip($rule)
{
    return array_reverse($rule);
}

function display($rule)
{
    foreach($rule as $row) {
        echo implode('', $row) . PHP_EOL;
    }

    echo PHP_EOL;
}

function count_pixels($pattern, $count = '#')
{
    $sum = 0;

    foreach($pattern as $row) {
        foreach($row as $value) {
            if($value === $count) {
                $sum++;
            }
        }
    }

    return $sum;
}

function count_pixels_after_iterations($pattern, $rules, $iterations)
{
    foreach (range(1, $iterations) as $loop) {
        $matchSize = count($pattern) % 2 == 0 ? 2 : 3;

        $pos = [
            'x' => 0,
            'y' => 0,
        ];

        $rowOffset = 0;

        $newPattern = [];

        while (isset($pattern[$pos['y']][$pos['x']])) {
            $block = [];

            foreach (range($pos['y'], $pos['y'] + $matchSize - 1) as $i) {
                $row = [];

                foreach (range($pos['x'], $pos['x'] + $matchSize - 1) as $j) {
                    $row[] = $pattern[$i][$j];
                }

                $block[] = $row;
            }

            $block = rule2array($rules[array2rule($block)]);

            foreach ($block as $i => $row) {
                if (isset($newPattern[$i + $rowOffset])) {
                    $newPattern[$i + $rowOffset] = array_merge($newPattern[$i + $rowOffset], $row);
                } else {
                    $newPattern[$i + $rowOffset] = $row;
                }
            }

            $pos['x'] += $matchSize;

            if (!isset($pattern[$pos['y']][$pos['x']])) {
                $pos['x'] = 0;
                $pos['y'] += $matchSize;
                $rowOffset += $matchSize + 1;
            }
        }

        $pattern = $newPattern;
    }

    return count_pixels($pattern);
}

function rule2array($rule)
{
    return array_map(function($part) {
        return str_split($part);
    }, explode('/', $rule));
}

function array2rule($array)
{
    return implode('/', array_map(function($parts) {
        return implode('', $parts);
    }, $array));
}