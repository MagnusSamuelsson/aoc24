<?php
$input = file_get_contents('inputs/day3.txt');
$input = str_replace(["\n", "\r"], '', $input);
preg_match_all('/mul\(\d*,\d*\)/', $input,$matches);
$sumPart2 = 0;
foreach ($matches[0] as $match) {
    $args = explode(',', substr($match, 4, -1));
    $sumPart2 += $args[0] * $args[1];
}
echo "<p>The answer for part 1 is: $sumPart2";

$instructions = [];
preg_match("/(.*?)don't\(\)/s", $input, $matches);
$instructions[] = $matches[1];
preg_match("/.*do\(\)(.*)$/s", $input, $matches);
$instructions[] = $matches[1];
preg_match_all("/do\(\)(.*?)don't\(\)/s", $input, $matches);
$instructions = implode('',array_merge($instructions, $matches[1]));
preg_match_all('/mul\(\d*,\d*\)/', $instructions,$matches);
$sumPart2 = 0;
foreach ($matches[0] as $match) {
    $args = explode(',', substr($match, 4, -1));
    $sumPart2 += $args[0] * $args[1];
}
echo "<p>The answer for part 2 is: $sumPart2";