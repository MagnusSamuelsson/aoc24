<?php
$input = trim(file_get_contents('inputs/day9.txt'));
$disk = [];
$empty = [];
$files = [];

for ($i = 0; $i < strlen($input); $i++) {
    if ($i % 2 === 0) {
        $files[] = [$i >> 1, intval($input[$i])];
        for ($j = 0; $j < intval($input[$i]); $j++) {
            $disk[] = $i >> 1;
        }
    } else {
        $empty[] = $input[$i];
    }
}
$empty = array_map('intval', $empty);
$emptyCopy = $empty;
$block = 0;
$index = 0;
$defraged = [];
while (count($empty) != 0 && count($disk) != 0) {
    $block = $disk[$index];
    while (isset($disk[$index]) && $block == $disk[$index]) {
            $defraged[] = array_shift($disk);
    }
    for ($i = 0; $i < $empty[0]; $i++) {
        $defraged[] = array_pop($disk);
    }
    array_shift($empty);
}
$sum = 0;
foreach ($defraged as $keyInput => $value) {
    $sum += $keyInput * $value;
}
echo "<p>The answer for part 1 is $sum";

$empty = $emptyCopy;
$block = 0;
$index = 0;
$movedFiles = [];
$firstEmpty = 0;
$oneisempty = false;
$filesReverse = array_reverse($files, true);
foreach ($filesReverse as $keyInput => $value) {
    while ($oneisempty && $empty[$firstEmpty] == -1) {
        $firstEmpty++;
    }
    if ($firstEmpty >= $keyInput) {
        break;
    }
    for ($k = $firstEmpty; $k <= $keyInput; $k++) {
        if ($keyInput > $k && $value[1] <= $empty[$k]) {
            for ($i = 0; $i < $value[1]; $i++) {
                $movedFiles[$k][] = $value[0];
            }
            $remaining = $empty[$k] - $value[1];
            $empty[$k] = ($remaining > 0) ? $remaining : -1;
            $oneisempty = true;
            $files[$keyInput][0] = 0;
            break;
        }
    }
}
$defraged2 = [];
$index = 0;
foreach($files as $keyInput => $value) {
    for ($i = 0; $i < intval($value[1]); $i++) {
        $defraged2[] = $value[0];
    }
    if (isset($movedFiles[$index])) {
        foreach ($movedFiles[$index] as $k => $v) {
            $defraged2[] = $v;
        }
    }
    if (!isset($empty[$index])) {
        break;
    }
    for ($i = 0; $i < $empty[$index]; $i++) {
        $defraged2[] = 0;
    }
    $index++;
}
$sum = 0;
foreach ($defraged2 as $keyInput => $value) {
    $sum += $keyInput * $value;
}
echo "<p>The answer for part 2 is $sum";