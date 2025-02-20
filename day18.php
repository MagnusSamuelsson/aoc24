<?php
$input = file_get_contents('inputs/day18.txt');
$coordinates = preg_split('/\r?\n/', $input);
$maxY = 0;
$maxX = 0;
foreach ($coordinates as $keyInput => $line) {
    [$x,$y] = explode(',', $line);
    $coordinates[] = "$y,$x";
    if ($x > $maxX) {
        $maxX = $x;
    }
    if ($y > $maxY) {
        $maxY = $y;
    }
}
$map = [];
for ($y = 0; $y <= $maxY; $y++) {
    $map[$y] = [];
    $map[$y] = array_fill(0, $maxX + 1, ".");
}
foreach($coordinates as $keyInput => $coordinate) {
    [$x,$y] = explode(',', $coordinate);
    $map[$y][$x] = '#';
    unset($coordinates[$keyInput]);
    if ($keyInput === 1023) {
        break;
    }
}


$directions = [
    ['x' => 0, 'y' => -1],
    ['x' => 1, 'y' => 0],
    ['x' => 0, 'y' => 1],
    ['x' => -1, 'y' => 0]
];
$endPosition = "$maxY,$maxX";
$shortestPath = 0;
function findPathDay18($position, $score) {
    global $map, $visited, $frontiers, $directions,$endPosition, $shortestPath;
    [$y, $x] = explode(',', $position);
    $visited[$position] = true;
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
$i = 0;
$frontiers["0,0"] = 0;
$visited = [];
while ($frontiers) {
    asort($frontiers);
    $frontierPos = array_key_first($frontiers);
    $score = array_shift($frontiers);
    if(findPathDay18($frontierPos, $score)) {
        break;
    }
}
echo "<p>The answer for part 1 is $shortestPath</p>";
$mapCopy = $map;
$coordinates = array_values($coordinates);
$numberOfCoordinates = count($coordinates);
$tryMax = $numberOfCoordinates;
$lastTry = 0;
while(true) {
    $map = $mapCopy;
    for ($i = 0; $i < $tryMax; $i++) {
        $coordinate = $coordinates[$i];
        [$x,$y] = explode(',', $coordinate);
        $map[$y][$x] = '#';
    }
    $found = false;
    $frontiers["0,0"] = 0;
    $visited = [];
    while ($frontiers) {
        asort($frontiers);
        $frontierPos = array_key_first($frontiers);
        $score = array_shift($frontiers);
        if(findPathDay18($frontierPos, $score)) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        if ($tryMax > $lastTry) {
            $tryNext = (($tryMax - $lastTry) >> 1);
            $lastTry = $tryMax;
            $tryMax -= $tryNext;
        } else {
            $tryNext = (($lastTry - $tryMax) >> 1);
            $lastTry = $tryMax;
            $tryMax -= $tryNext;
        }
        $pt2Answer = "$x,$y";
    } else {
        if ($tryMax > $lastTry) {
            $tryNext = (($tryMax - $lastTry) >> 1);
            $lastTry = $tryMax;
            $tryMax += $tryNext;
        } else {
            $tryNext = (($lastTry - $tryMax) >> 1);
            $lastTry = $tryMax;
            $tryMax += $tryNext;
        }
    }
    if ($tryMax == $lastTry) {
        break;
    }
}
echo "<p>The answer for part 2 is: $pt2Answer";