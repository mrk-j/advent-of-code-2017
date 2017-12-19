<?php

$input = file_get_contents('input.txt');

$instructions = explode("\n", $input);

$register = [];
$lastPlayedSound = 0;

function getValue($register, $variable)
{
    if (is_numeric($variable)) {
        return (int)$variable;
    }

    if (isset($register[$variable])) {
        return (int)$register[$variable];
    }

    return 0;
}

$currentInstruction = 0;

while (isset($instructions[$currentInstruction])) {
    $instruction = explode(' ', $instructions[$currentInstruction]);

    if ($instruction[0] === 'snd' && isset($register[$instruction[1]])) {
        $lastPlayedSound = getValue($register, $instruction[1]);
    } elseif ($instruction[0] === 'set') {
        $register[$instruction[1]] = getValue($register, $instruction[2]);
    } elseif ($instruction[0] === 'add') {
        $register[$instruction[1]] = getValue($register, $instruction[1]) + getValue($register, $instruction[2]);
    } elseif ($instruction[0] === 'mul') {
        $register[$instruction[1]] = getValue($register, $instruction[1]) * getValue($register, $instruction[2]);
    } elseif ($instruction[0] === 'mod') {
        $register[$instruction[1]] = getValue($register, $instruction[1]) % getValue($register, $instruction[2]);
    } elseif ($instruction[0] === 'rcv' && getValue($register, $instruction[1]) !== 0) {
        $register[$instruction[1]] = (int)$lastPlayedSound;

        echo 'Recover operation to: ' . $lastPlayedSound . PHP_EOL;

        break;
    } elseif ($instruction[0] === 'jgz' && getValue($register, $instruction[1]) > 0) {
        $currentInstruction += getValue($register, $instruction[2]) - 1;
    }

    $currentInstruction++;
}

$currentInstruction = [
    0 => 0,
    1 => 0,
];

$registers = [
    0 => ['p' => 0],
    1 => ['p' => 1],
];

$queues = [
    0 => [],
    1 => [],
];

$numberOfSends = [
    0 => 0,
    1 => 0,
];

function isDeadlocked($queues, $instructions, $currentInstruction)
{
    if (!isset($instructions[$currentInstruction[0]]) && !isset($instructions[$currentInstruction[1]])) {
        return true;
    }

    $programIsWaiting = [
        0 => false,
        1 => false,
    ];

    foreach ([0, 1] as $register) {
        if (isset($instructions[$currentInstruction[$register]])) {
            $instruction = explode(' ', $instructions[$currentInstruction[$register]]);

            if ($instruction[0] === 'rcv' && count($queues[$register]) === 0) {
                $programIsWaiting[$register] = true;
            }
        } else {
            $programIsWaiting[$register] = true;
        }
    }

    if ($programIsWaiting[0] === true && $programIsWaiting[1] === true) {
        return true;
    }

    return false;
}

while (!isDeadlocked($queues, $instructions, $currentInstruction)) {
    foreach ([0, 1] as $register) {
        if (!isset($instructions[$currentInstruction[$register]])) {
            continue;
        }

        $instruction = explode(' ', $instructions[$currentInstruction[$register]]);

        if ($instruction[0] === 'snd') {
            $queues[$register == 1 ? 0 : 1][] = getValue($registers[$register], $instruction[1]);

            $numberOfSends[$register]++;
        } elseif ($instruction[0] === 'set') {
            $registers[$register][$instruction[1]] = getValue($registers[$register], $instruction[2]);
        } elseif ($instruction[0] === 'add') {
            $registers[$register][$instruction[1]] = getValue($registers[$register], $instruction[1]) + getValue($registers[$register], $instruction[2]);
        } elseif ($instruction[0] === 'mul') {
            $registers[$register][$instruction[1]] = getValue($registers[$register], $instruction[1]) * getValue($registers[$register], $instruction[2]);
        } elseif ($instruction[0] === 'mod') {
            $registers[$register][$instruction[1]] = getValue($registers[$register], $instruction[1]) % getValue($registers[$register], $instruction[2]);
        } elseif ($instruction[0] === 'rcv') {
            if (count($queues[$register]) > 0) {
                $registers[$register][$instruction[1]] = array_shift($queues[$register]);
            } else {
                $currentInstruction[$register]--;
            }
        } elseif ($instruction[0] === 'jgz') {
            if (getValue($registers[$register], $instruction[1]) > 0) {
                $currentInstruction[$register] += (getValue($registers[$register], $instruction[2]) - 1);
            }
        }

        $currentInstruction[$register]++;
    }
}

echo 'The number of sends is: ' . $numberOfSends[1] . PHP_EOL;