<?php
$input = file_get_contents('inputs/day1.txt');
$input = explode("\n", $input);
$leftList = [];
$rightList = [];
foreach ($input as $line) {
    $numbers = preg_split('/\s+/', $line);
    $leftList[] = $numbers[0];
    $rightList[] = $numbers[1];
}

sort($leftList);
sort($rightList);

$sumPart1 = 0;
$sumPart2 = 0;
$rightListCounter = array_count_values($rightList);
foreach($leftList as $keyInput => $value) {
    $sumPart1 += abs($value - $rightList[$keyInput]);
    if (array_key_exists($value, $rightListCounter)) {
        $sumPart2 +=  $value * $rightListCounter[$value];
    }
}

echo "<p>The answer for part 1 is: $sumPart1";
echo "<p>The answer for part 2 is: $sumPart2";