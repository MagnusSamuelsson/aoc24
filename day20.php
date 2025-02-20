<?php
$input = file_get_contents('inputs/day20.txt');
$input = preg_split('/\r?\n/', $input);

$map = [];
foreach ($input as $keyInput => $line) {
    $map[] = str_split($line);
}

foreach ($map as $y => $line) {
    foreach ($line as $x => $cell) {
        if ($cell == 'S') {
           $startPosition = "$y,$x";
        }
        if ($cell == 'E') {
            $endPosition = "$y,$x";
        }
    }
}
$directions = [
    ['x' => 0, 'y' => -1],
    ['x' => 1, 'y' => 0],
    ['x' => 0, 'y' => 1],
    ['x' => -1, 'y' => 0]
];
$shortestPath = 0;
function findPathDay20($position, $score) {
    global $map, $visited, $frontiers, $directions,$endPosition, $shortestPath;
    [$y, $x] = explode(',', $position);
    $visited[$position] = $score;
    if ($endPosition == "$y,$x") {
        $shortestPath = $score;
        return true;
    }
    foreach ($directions as $direction) {
        $newX = $x + $direction['x'];
        $newY = $y + $direction['y'];
        $newPosition = "$newY,$newX";
        if (!isset($map[$newY][$newX])) {
            continue;
        }
        if ($map[$newY][$newX] == '#') {
            continue;
        }
        if (!isset($map[$newY][$newX]) || $map[$newY][$newX] == '#' || isset($visited[$newPosition])) {
            continue;
        }
        $frontiers[$newPosition] = $score + 1;
    }
}

$frontiers[$startPosition] = 0;
$visited = [];
while ($frontiers) {
    asort($frontiers);
    $frontierPos = array_key_first($frontiers);
    $score = array_shift($frontiers);
    if(findPathDay20($frontierPos, $score)) {
        break;
    }
}
$cheatStartEnd = [];

$shortcuts = [];
foreach ($visited as $keyInput => $value) {
    [$y, $x] = explode(',', $keyInput);
    foreach ($directions as $direction) {
        $newX = $x + ($direction['x'] << 1);
        $newY = $y + ($direction['y'] << 1);
        $newPosition = "$newY,$newX";
        if (!isset($visited[$newPosition])) {
            continue;
        }
        if ($value + 2 + 99 < $visited[$newPosition]) {
            $shortcuts[] = $visited[$newPosition] - $value - 2;
        }
    }
}
echo "<p>The answer for part 1 is: ". count($shortcuts);

function findCoordinatesWithinManhattanDistance($y_center, $x_center) {
    global $visited;
    $max_distance = 20;

    $coordinates = [];

    for ($y = $y_center - $max_distance; $y <= $y_center + $max_distance; $y++) {
        for ($x = $x_center - $max_distance; $x <= $x_center + $max_distance; $x++) {
            if (abs($y_center - $y) + abs($x_center - $x) <= $max_distance && isset($visited["$y,$x"])) {
                $coordinates[] = [$y, $x];
            }
        }
    }
    return $coordinates;
}

$shortcuts = [];
foreach ($visited as $keyInput => $value) {
    [$y, $x] = explode(',', $keyInput);

    for ($ym = $y - 20; $ym <= $y + 20; $ym++) {
        for ($xm = $x - 20; $xm <= $x + 20; $xm++) {
            if (!isset($visited["$ym,$xm"])) {
                continue;
            }
            $manhattanDistance = abs($y - $ym) + abs($x - $xm);
            if ($manhattanDistance <= 20 && $value + $manhattanDistance < $visited["$ym,$xm"] && $visited["$ym,$xm"] - $value - $manhattanDistance > 99) {
                $shortcuts[] = $visited["$ym,$xm"] - $value - $manhattanDistance;
            }
        }
    }
}

echo "<p>The answer for part 2 is: ". count($shortcuts);