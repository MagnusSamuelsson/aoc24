<?php
$input = file_get_contents('inputs/day7.txt');
$input = explode("\n", $input);
$calibrations = [];
foreach ($input as $y => $line) {
    [$sum,$numbers] = explode(': ',$line);
    $numbers = explode(' ', $numbers);
    $calibrations[] = [$sum, array_map('intval',$numbers)];
}

$pt1Valids = 0;
$pt2Valids = 0;
function checkCalibration($calibration) {
    global $pt1Valids, $pt2Valids;
    [$sum,$numbers] = $calibration;
    $numCount = count($numbers) -1;
    $sums = [];
    $sumsConcat = [];
    for ($i = 0; $i < $numCount; $i++) {
        if (!isset($sums[$i]) && $i === 0) {
            $sums[$i][] = $numbers[$i];
            $sumsConcat[$i][] = $numbers[$i];
        }
        foreach($sums[$i] as $sum2) {
            $sums[$i+1][] = $sum2 + $numbers[$i + 1];
            $sums[$i+1][] = $sum2 * $numbers[$i + 1];
        }
        if (min($sumsConcat[$i]) <= $sum) {
            foreach($sumsConcat[$i] as $sum2) {
                $sumsConcat[$i+1][] = $sum2 + $numbers[$i + 1];
                $sumsConcat[$i+1][] = $sum2 * $numbers[$i + 1];
                if (intval($sum2 ."".$numbers[$i + 1]) <= $sum) {
                    $sumsConcat[$i+1][] = intval($sum2 ."".$numbers[$i + 1]);
                }
            }

            unset($sumsConcat[$i]);
        }
        unset($sums[$i]);
        unset($sumsConcat[$i]);
    }
    if (in_array($sum, $sums[$numCount])) {
        $pt1Valids += $sum;
    }
    if (isset($sumsConcat[$numCount]) && in_array($sum, $sumsConcat[$numCount])) {
        $pt2Valids += $sum;
    }
}
foreach ($calibrations as $calibration) {
    checkCalibration($calibration);
}
echo "<p>The answer for part 1 is: $pt1Valids";
echo "<p>The answer for part 2 is: $pt2Valids";