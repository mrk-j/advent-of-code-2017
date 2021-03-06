<?php

$input = file_get_contents('input.txt');

// strip !'s and next character
$characters = str_split($input);

while (($pos = array_search('!', $characters)) !== false) {
    array_splice($characters, $pos, 2);
}

// strip all garbage
$totalGarbage = 0;

while(($firstOpeningBracket = array_search('<', $characters)) !== false) {
    $firstClosingBracket = array_search('>', $characters);

    $totalGarbage += $firstClosingBracket - ($firstOpeningBracket + 1);

    array_splice($characters, $firstOpeningBracket, (($firstClosingBracket + 1) - $firstOpeningBracket));
}

// calculate score
$level = 1;
$score = 0;

foreach($characters as $character) {
    if($character === '{') {
        $score += $level;
        $level++;
    }

    if($character === '}') {
        $level--;
    }
}

echo "The total score is {$score}".PHP_EOL;
echo "The total garbage is {$totalGarbage}".PHP_EOL;