<?php
$input = file_get_contents('inputs/day4.txt');
$input = preg_split('/\r?\n/', $input);
foreach ($input as $keyInput => $line) {
    $input[$keyInput] = str_split($line);
}
function search($array): int {

    $counter = 0;
    $numRows = count($array);
    $numCols = count($array[0]);
    foreach ($array as $row) {
        $counter += xmasCounter(implode('', $row));
    }

    for ($x = 0; $x < $numCols; $x++) {
        $col = '';
        foreach ($array as $y => $row) {
            $col .= $row[$x];
        }
        $counter += xmasCounter($col);
    }

    for ($i = 0; $i < $numRows; $i++) {
        $diag = '';
        $row = $i;
        $col = 0;
        while ($row < $numRows && $col < $numCols) {
            $diag .= $array[$row][$col];
            $row++;
            $col++;
        }
        $counter += xmasCounter($diag);
    }
    for ($j = 1; $j < $numCols; $j++) {
        $diag = '';
        $row = 0;
        $col = $j;
        while ($row < $numRows && $col < $numCols) {
            $diag .= $array[$row][$col];
            $row++;
            $col++;
        }
        $counter += xmasCounter($diag);
    }
    for ($i = 0; $i < $numRows; $i++) {
        $diag = '';
        $row = $i;
        $col = $numCols - 1;
        while ($row < $numRows && $col >= 0) {
            $diag .= $array[$row][$col];
            $row++;
            $col--;
        }
        $counter += xmasCounter($diag);
    }
    for ($j = $numCols - 2; $j >= 0; $j--) {
        $diag = '';
        $row = 0;
        $col = $j;
        while ($row < $numRows && $col >= 0) {
            $diag .= $array[$row][$col];
            $row++;
            $col--;
        }
        $counter += xmasCounter($diag);
    }

    return $counter;
}
function xmasCounter($string): int {
    return substr_count($string, "XMAS") + substr_count($string, "SAMX");
}
function search2($array): int {
    $counter = 0;
    for ($y = 1; $y < count($array) - 1; $y++) {
        for ($x = 1; $x < count($array[$y]) - 1; $x++) {
            if ($array[$y][$x] != "A") {
                continue;
            }
            $word1 = $array[$y - 1][$x - 1] . $array[$y + 1][$x + 1];
            if ($word1 != "MS" && $word1 != "SM") {
                continue;
            }
            $word2 = $array[$y + 1][$x - 1] . $array[$y - 1][$x + 1];
            if ($word2 != "MS" && $word2 != "SM") {
                continue;
            }
            $counter++;
        }
    }
    return $counter;
}

echo "<p> The answer for part 1 is: " . search($input);
echo "<p> The answer for part 2 is: " . search2($input);