<?php
$input = file_get_contents('inputs/day21.txt');
$lines = preg_split('/\r?\n/', $input);
$codes = array_map("str_split", $lines);
function numpad($code) {
    $numpad = [
        7,8,9,
        4,5,6,
        1,2,3,
        '',0,'A'
    ];
    $numpadMap = [
        7 => [0,0],
        8 => [1,0],
        9 => [2,0],
        4 => [0,1],
        5 => [1,1],
        6 => [2,1],
        1 => [0,2],
        2 => [1,2],
        3 => [2,2],
        0 => [1,3],
        'A' => [2,3]
    ];
    $pointer = [2,3];
    $instruction = "";
    foreach ($code as $button) {
        $targetCoord = $numpadMap[$button];
        $mvX = $targetCoord[0] - $pointer[0];
        $mvY = $targetCoord[1] - $pointer[1];
        $yFirst = ($mvX > 0) ? true : false;

        if ($pointer[1] == 3 && $targetCoord[0] == 0) {
            $yFirst = true;
        }
        if ($pointer[0] == 0 && $targetCoord[1] == 3) {
            $yFirst = false;
        }

        $updown = $mvY > 0 ? "v" : "^";
        $leftright = $mvX > 0 ? ">" : "<";
        if ($yFirst) {
            $instruction .= str_repeat($updown,abs($mvY));
            $instruction .= str_repeat($leftright,abs($mvX));
        } else {
            $instruction .= str_repeat($leftright,abs($mvX));
            $instruction .= str_repeat($updown,abs($mvY));
        }
        $instruction .= "A";
        $pointer = $targetCoord;
    }
    return $instruction;
}

function dirpad($code) {
    $numpad = [
        '','^','A',
        '<','v','>',
    ];
    $numpadMap = [
        'A' => [2,0],
        '^' => [1,0],
        '<' => [0,1],
        'v' => [1,1],
        '>' => [2,1]
    ];
    $pointer = [2,0];
    $instruction = "";
    for ($i = 0; $i < strlen($code); $i++) {
        $button = $code[$i];
        $targetCoord = $numpadMap[$button];
        $mvX = $targetCoord[0] - $pointer[0];
        $mvY = $targetCoord[1] - $pointer[1];

        $updown = $mvY > 0 ? "v" : "^";
        $leftright = $mvX > 0 ? ">" : "<";
        $yFirst = ($mvX > 0) ? true : false;

        if ($pointer[1] == 0 && $targetCoord[0] == 0) {
            $yFirst = true;
        }
        if ($pointer[0] == 0 && $targetCoord[1] == 0) {
            $yFirst = false;
        }
        $yFirst = rand(0,1) == 1 ? true : false;
        if ($yFirst) {
            $instruction .= str_repeat($updown,abs($mvY));
            $instruction .= str_repeat($leftright,abs($mvX));
        } else {
            $instruction .= str_repeat($leftright,abs($mvX));
            $instruction .= str_repeat($updown,abs($mvY));
        }
        $instruction .= "A";
        $pointer = $targetCoord;
    }
    return $instruction;
}
function dirpadFast($code) {
    $numpad = [
        '','^','A',
        '<','v','>',
    ];
    $numpadMap = [
        'A' => [2,0],
        '^' => [1,0],
        '<' => [0,1],
        'v' => [1,1],
        '>' => [2,1]
    ];
    $fromTo = [
        'A,<' => 'v<<',
        'A,>' => 'v',
        'A,^' => '<',
        'A,v' => '<v',

        '<,A' => '>>^',
        '<,>' => '>>',
        '<,^' => '>^',
        '<,v' => '>',

        '>,A' => '^',
        '>,<' => '<<',
        '>,^' => '<^',
        '>,v' => '<',

        '^,A' => '>',
        '^,<' => 'v<',
        '^,>' => 'v>',
        '^,v' => 'v',

        'v,A' => '^>',
        'v,<' => '<',
        'v,>' => '>',
        'v,^' => '^'

    ];
    $pointer = "A";
    $instruction = "";
    for ($i = 0; $i < strlen($code); $i++) {
        $button = $code[$i];
        if ($button == $pointer) {
            $instruction .= "A";
            continue;
        }
        $instruction .= $fromTo[$pointer . "," . $button]."A";
        $pointer = $button;
    }
    return $instruction;
}
$pt1 = 0;
foreach ($codes as $code) {
    $codeNum = intval(str_replace("A","",implode($code)));
    $presses = numpad($code);
    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $length = strlen($presses);
    $pt1 += $length * $codeNum;
}
echo "\nThe answer for part 1 is: $pt1";


$pt1 = 0;
foreach ($codes as $code) {
    $codeNum = intval(str_replace("A","",implode($code)));
    $presses = numpad($code);


    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $presses = dirpad($presses);
    $pt1 += $length * $codeNum;
}
echo "\nThe answer for part 1 is: $pt1";