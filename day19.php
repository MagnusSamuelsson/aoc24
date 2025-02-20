<?php
$input = file_get_contents('inputs/day19.txt');
$input = explode(PHP_EOL.PHP_EOL, $input);
$towelPatterns = explode(", ", $input[0]);
$towels = explode(PHP_EOL, $input[1]);
$towelPatterns = array_map('trim', $towelPatterns);

function checkTowelValid2(string $towel, array $towelPatterns, array &$cache): int {

    if (empty($towel)) {
        return 1;
    }
    if (isset($cache[$towel])) {
        return $cache[$towel];
    }

    $possibilities = 0;

    foreach ($towelPatterns as $pattern) {
        if (str_starts_with($towel, $pattern)) {
            $possibilities += checkTowelValid2(substr($towel, strlen($pattern)), $towelPatterns, $cache);
        }
    }

    $cache[$towel] = $possibilities;
    return $possibilities;
}
$pt1 = 0;
$pt2 = 0;
foreach ($towels as $towel) {
    $cache = [];
    $inc = checkTowelValid2($towel, $towelPatterns, $cache);
    $pt2 += $inc;
    if ($inc > 0) {
        $pt1++;
    }
}

echo "<p>The answer for part 1 is: $pt1";
echo "<p>The answer for part 1 is: $pt2";