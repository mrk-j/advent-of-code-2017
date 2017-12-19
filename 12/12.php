<?php

$input = file_get_contents('input.txt');

$lines = explode("\n", $input);

$groups = [
    0 => [0]
];

$currentGroup = 0;

$programs = [];

foreach ($lines as $key => $line) {
    $parts = explode('<->', $line);

    $program = intval($parts[0]);

    $programs[$program] = array_map('intval', explode(',', $parts[1]));
}

while (count($programs) > 0) {
    $count = 0;

    while (count($groups[$currentGroup]) != $count) {
        $count = count($groups[$currentGroup]);

        foreach ($programs as $program => $connectedPrograms) {
            $connectedPrograms = array_merge([$program], $connectedPrograms);

            foreach ($connectedPrograms as $connectedProgram) {
                if (in_array($connectedProgram, $groups[$currentGroup])) {
                    $groups[$currentGroup] = array_unique(array_merge($groups[$currentGroup], $connectedPrograms));

                    unset($programs[$program]);

                    break;
                }
            }
        }
    }

    $currentGroup = key($programs);

    if ($currentGroup) {
        $groups[$currentGroup] = [$currentGroup];
    }
}

echo 'The number of programs in group 0 is: ' . count($groups[0]) . PHP_EOL;
echo 'The number of groups is: ' . count($groups) . PHP_EOL;