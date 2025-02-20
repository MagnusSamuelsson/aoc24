<?php
$input = file_get_contents('inputs/day5.txt');
$input = preg_split('/\r?\n/', $input);
$rules = [];
$pages = [];
$array = "rules";
foreach ($input as $keyInput => $line) {
    if ($line === "") {
        $array = "pages";
        continue;
    }
    if ($array === "rules") {
        [$a, $b] = array_map('intval', explode("|", $line));
        $rules[$a][] = $b;
    } else {
        $pages[] = array_map('intval', explode(",", $line));;
    }
}
function checkRule($a, $b): int {
    global $rules;
    if (isset($rules[$a]) && in_array($b, $rules[$a])) {
        return -1;
    }
    if (isset($rules[$b]) && in_array($a, $rules[$b])) {
        return 1;
    }
    return 0;
}
function findMiddleKeyValue($array): int {
    return (int)$array[floor(count($array) / 2)];
}

$sum1 = 0;
$sum2 = 0;
function superSort($array): void {
    global $sum1, $sum2;
    $originalPages = $array;
    usort($array, 'checkRule');
    if ($originalPages !== $array) {
        $sum2 += findMiddleKeyValue($array);
    } else {
        $sum1 += findMiddleKeyValue($array);
    }
}
array_map('superSort', $pages);
echo "<p>The answer for part 1 is: $sum1";
echo "<p>The answer for part 2 is: $sum2";
