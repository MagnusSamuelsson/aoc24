<?php
$input = trim(file_get_contents('inputs/day11.txt'));
$input = explode(" ", $input);
$digits = array_count_values($input);

function blink(&$digits) {
    $beforeBlink = $digits;
    $digits = [];

    if(!isset($beforeBlink[0])) {
        $beforeBlink[0] = 0;
    }

    $digits[1] = $beforeBlink[0];
    $beforeBlink[0] = 0;

    foreach($beforeBlink as $key => $value) {
        if ($beforeBlink[$key] == 0) {
            continue;
        }
        if(strlen($key) % 2 == 0) {
            [$l,$r] = str_split($key, strlen($key) / 2);
            $l = intval($l);
            $r = intval($r);
            if (!isset($digits[$l])) {
                $digits[$l] = $beforeBlink[$key];
            } else {
                $digits[$l] += $beforeBlink[$key];
            }
            if (!isset($digits[$r])) {
                $digits[$r] = $beforeBlink[$key];
            } else {
                $digits[$r] += $beforeBlink[$key];
            }
        } else {
            $newValue = intval($key) * 2024;
            if(!isset($digits[$newValue])) {
                $digits[$newValue] = $beforeBlink[$key];
            } else {
                $digits[$newValue] += $beforeBlink[$key];
            }
        }
    }
    foreach($digits as $key => $value) {
        if($value == 0) {
            unset($digits[$key]);
        }
    }
    return $digits;
}


for ($i = 0; $i < 25; $i++) {
    blink($digits);
}

$pt1 = array_sum($digits);

for ($i = 0; $i < 50; $i++) {
    blink($digits);
}

$pt2 = array_sum($digits);

echo "<p>The answer for part 1 is $pt1";
echo "<p>The answer for part 2 is $pt2";