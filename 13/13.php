<?php

$input = file_get_contents('input.txt');

$layers = explode("\n", $input);

$firewall = [];
$firewallLayers = [];

foreach ($layers as $layer) {
    $parts = explode(': ', $layer);

    $firewallLayers[$parts[0]] = $parts[1];
}

foreach (range(min(array_keys($firewallLayers)), max(array_keys($firewallLayers))) as $key) {
    if (isset($firewallLayers[$key])) {
        $firewall[$key] = [
            'scanner' => true,
            'depth' => (int) $firewallLayers[$key],
            'position' => 0,
            'direction' => 'D',
        ];
    } else {
        $firewall[$key] = [
            'scanner' => false,
        ];
    }
}

function testFirewall($firewall, $position = 0)
{
    $position--;

    $sum = false;

    foreach (range($position + 1, max(array_keys($firewall))) as $picosecond) {
        $position++;

        if ($position >= 0 && $firewall[$position]['scanner'] !== false) {
            $scannerAt = $firewall[$position]['position'];

            if ($scannerAt === 0) {
                if ($sum === false) {
                    $sum = 0;
                }

                $sum += $position * $firewall[$position]['depth'];
            }
        }

        foreach ($firewall as $key => &$layer) {
            if($layer['scanner'] === false) {
                continue;
            }

            $scannerAt = $layer['position'];

            if ($layer['direction'] === 'D' && $scannerAt + 1 >= $layer['depth']) {
                $layer['direction'] = 'U';
            }

            if ($layer['direction'] === 'U' && $scannerAt === 0) {
                $layer['direction'] = 'D';
            }

            if ($layer['direction'] === 'U') {
                $layer['position']--;
            } elseif ($layer['direction'] === 'D') {
                $layer['position']++;
            }
        }
    }

    return $sum;
}

echo 'The sum is ' . testFirewall($firewall) . PHP_EOL;

//$startLevel = 0;
//
//do {
//    $sum = testFirewall($firewall, $startLevel);
//
//    echo "The progress is {$startLevel}\r";
//
//    if ($sum === false) {
//        echo 'You won\'t get caught after waiting ' . ($startLevel * -1) . ' picoseconds' . PHP_EOL;
//    }
//
//    $startLevel--;
//} while ($sum !== false);

function testFirewall2($firewall)
{
    $rounds = 0;

    $lastRounds = [];

    $numberOfLayers = count($firewall);

    $go = true;

    while ($go) {
        foreach ($firewall as $key => &$layer) {
            if($layer['scanner'] === false) {
                continue;
            }

            $scannerAt = $layer['position'];

            if ($layer['direction'] === 'D' && $scannerAt + 1 >= $layer['depth']) {
                $layer['direction'] = 'U';
            }

            if ($layer['direction'] === 'U' && $scannerAt === 0) {
                $layer['direction'] = 'D';
            }

            if ($layer['direction'] === 'U') {
                $layer['position']--;
            } elseif ($layer['direction'] === 'D') {
                $layer['position']++;
            }
        }

        $lastRounds[] = $firewall;

        $historyCount = count($lastRounds);

        if($historyCount > $numberOfLayers) {
            array_shift($lastRounds);
        }

        if($historyCount - 1 === $numberOfLayers) {
            foreach(range(1, $numberOfLayers) as $position) {
                $thisLayer = $lastRounds[$position - 1][$position - 1];

                if($thisLayer['scanner'] && $thisLayer['position'] === 0) {
                    break;
                }

                if($position === $numberOfLayers) {
                    $go = false;
                }
            }
        }

        $rounds++;
    }

    $rounds -= $numberOfLayers - 1;

    echo "Rounds: {$rounds}" . PHP_EOL;
}

testFirewall2($firewall);
