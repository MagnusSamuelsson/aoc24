<?php
$input = file_get_contents('inputs/day22.txt');
$input = explode("\n", $input);
function calculateNextSecretNumber($secretNumber) {
    $secretNumber = ($secretNumber << 6) ^ $secretNumber;
    $secretNumber %= 16777216;
    $secretNumber = ($secretNumber >> 5) ^ $secretNumber;
    $secretNumber %= 16777216;
    $secretNumber = ($secretNumber << 11) ^ $secretNumber;
    $secretNumber %= 16777216;
    return $secretNumber;
}

function calculateSecretNumberIn2000Steps($secretNumber) {
    global $sequences;
    $bananas = [];
    $deltas = [];
    $bananaSequence = [];
    for ($i = 0; $i < 2000; $i++) {
        $secretNumber = calculateNextSecretNumber($secretNumber);
        $secretString = strval($secretNumber);
        $banana = $secretString[-1];
        $bananas[] = $banana;
        if (!isset($bananas[$i - 1])) {
            $bananas[$i - 1] = $banana;
        }
        array_push($deltas, $banana - $bananas[$i - 1]);
        if (count($deltas) == 5) {
            array_shift($deltas);
            $sequence = implode(",", $deltas);
            if (!isset($bananaSequence[$sequence])) {
                $bananaSequence[$sequence] = true;
                if (isset($sequences[$sequence])) {
                    $sequences[$sequence] += $banana;
                } else {
                    $sequences[$sequence] = $banana;
                }
            }
        }




    }
    return [$secretNumber, $bananas];
}


$sequences = [];
$secretNumbers = [];
$bananas = [];
foreach ($input as $keyInput => $initialNumber) {
    [$secretNumber, $ban] = calculateSecretNumberIn2000Steps($initialNumber);
    $secretNumbers[] = $secretNumber;
    $bananas[$keyInput] = $ban;
}

echo "<p>The answer for part 1 is: ". array_sum($secretNumbers);
echo "<p>The answer for part 2 is: ". max($sequences);