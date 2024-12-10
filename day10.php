<?php
$input = trim(file_get_contents('inputs/day10.txt'));
$map = array_map( 'trim',explode("\n", $input));
$map = [...array_map( 'str_split',$map)];
$startPositions = [];
foreach ($map as $y => $line) {
    $map[$y]= array_map('intval', $line);
    $startPositions[$y] = array_keys($map[$y], '0');
}
function move($y, $x) {
    global $map, $visited, $nines;
    if (in_array("$y,$x", $visited)) {
        return;
    }
    $visited[] = "$y,$x";
    if ($map[$y][$x] === 0 ) {
        return;
    }
    if ($map[$y][$x] === 9) {
        $nines++;
        return;
    }
    $moveOptions = moveOptions($y, $x);
    foreach ($moveOptions as $option) {
        move($option['y'], $option['x']);
    }
}
function move2($y, $x) {
    global $map, $visited, $nines;
    if ($map[$y][$x] === 0 ) {
        return;
    }
    if ($map[$y][$x] === 9) {
        $nines++;
        return;
    }
    $moveOptions = moveOptions($y, $x);
    foreach ($moveOptions as $option) {
        move2($option['y'], $option['x']);
    }
}
function moveOptions($y, $x) {
    global $map, $visited;
    $y = intval($y);
    $x = intval($x);
    $currentHeight = intval($map[$y][$x]);
    $options = [];
    if (isset($map[$y - 1][$x]) && !in_array(($y - 1).','.$x, haystack: $visited) && $map[$y - 1][$x] != 'S' && $map[$y - 1][$x] - $currentHeight === 1) {
        $options[] = ['y' => $y - 1, 'x' => $x];
    }
    if (isset($map[$y + 1][$x]) && !in_array(($y + 1).','.$x, haystack: $visited) && $map[$y + 1][$x] != 'S' && $map[$y + 1][$x] - $currentHeight === 1) {
        $options[] = ['y' => $y + 1, 'x' => $x];
    }
    if (isset($map[$y][$x - 1]) && !in_array($y.','.($x - 1), haystack: $visited) && $map[$y][$x - 1] != 'S' && $map[$y][$x - 1] - $currentHeight === 1) {
        $options[] = ['y' => $y, 'x' => $x - 1];
    }
    if (isset($map[$y][$x + 1]) && !in_array($y.','.($x + 1), haystack: $visited) && $map[$y][$x + 1] != 'S' && $map[$y][$x + 1] - $currentHeight === 1) {
        $options[] = ['y' => $y, 'x' => $x + 1];
    }
    return $options;
}

$nines = 0;
foreach ($startPositions as $y => $row) {
    foreach ($row as $line => $x) {
        $map[$y][$x] = 'S';
        $visited = [];
        move($y, $x);
        $map[$y][$x] = 0;
        $visited = [];
    }
}
$ninesPt1 = $nines;
$nines = 0;
foreach ($startPositions as $y => $row) {
    foreach ($row as $line => $x) {
        $map[$y][$x] = 'S';
        $visited = [];
        move2($y, $x);
        $map[$y][$x] = 0;
        $visited = [];
    }
}
echo "<p>The answer for part 1 is $ninesPt1";
echo "<p>The answer for part 2 is $nines";
