<?php

function knot_hash($input)
{
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

                array_splice($list, $pos, ($listLength - $pos), $replaceWithStart);
                array_splice($list, 0, ($length - ($listLength - $pos)), $replaceWith);
            }

            $pos = ($pos + $length + $skip) % $listLength;
            $skip++;
        }
    }

    $parts = array_map(function ($a) {
        $result = $a[0];

        foreach (range(1, count($a) - 1) as $i) {
            $result ^= $a[$i];
        }

        return $result;
    }, array_chunk($list, 16));

    $hash = implode('', array_map(function ($part) {
        return str_pad(dechex($part), 2, '0', STR_PAD_LEFT);
    }, $parts));

    return $hash;
}

function binary($hash)
{
    $characters = str_split($hash);
    $binary = '';

    foreach ($characters as $character) {
        $binary .= str_pad(decbin(hexdec($character)), 4, '0', STR_PAD_LEFT);
    }

    return $binary;
}

function find_all_connected_squares(&$grid, $group, $row, $column)
{
    $grid[$row][$column] = $group;

    if (isset($grid[$row - 1][$column]) && $grid[$row - 1][$column] === '#') {
        find_all_connected_squares($grid, $group, $row - 1, $column);
    }

    if (isset($grid[$row][$column - 1]) && $grid[$row][$column - 1] === '#') {
        find_all_connected_squares($grid, $group, $row, $column - 1);
    }

    if (isset($grid[$row + 1][$column]) && $grid[$row + 1][$column] === '#') {
        find_all_connected_squares($grid, $group, $row + 1, $column);
    }

    if (isset($grid[$row][$column + 1]) && $grid[$row][$column + 1] === '#') {
        find_all_connected_squares($grid, $group, $row, $column + 1);
    }
}