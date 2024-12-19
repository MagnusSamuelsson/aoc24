<?php
$input = trim(file_get_contents('inputs/day16.txt'));
$input = str_replace(' ','',$input);
$map = array_map( 'trim',explode("\n", $input));
$map = [...array_map( 'str_split',$map)];
foreach ($map as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell === 'S') {
            $startPosition = "$y,$x";
        }
        if ($cell === 'E') {
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
$visited = [];
$goalscore = 0;
$goaltracks = [];
$frontiers[$startPosition.',1'] = [];
$frontierScores[$startPosition.',1'] = 0;
$positionScores = [];
$mergeLater = [];
function findPath($position, $track, $score) {
    global $map, $directions, $goalscore, $goaltracks, $visited, $frontiers, $endPosition, $frontierScores, $mergeLater,$positionScores;
    [$y, $x, $d] = explode(',', $position);
    $track[] = $position;
    $visited[$position] = true;
    if ($score > $goalscore && $goalscore != 0) {
        return;
    }
    if ($endPosition == "$y,$x") {
        $goalscore = $score;
        foreach($track as $t) {
            [$y, $x, $d] = explode(',', $t);
            $map[$y][$x] = '0';
        }
        return true;
    }

    $right = ($d + 1) % 4;
    $left = ($d + 3) % 4;
    foreach ([$left, $d, $right] as $newD) {
        $newX = $x + $directions[$newD]['x'];
        $newY = $y + $directions[$newD]['y'];

        $newPosition = "$newY,$newX,$newD";
        $newScore = $score + ($newD == $d ? 1 : 1001);
        if ($map[$newY][$newX] == '#' || isset($visited[$newPosition])) {
            continue;
        }
        if(isset($frontiers[$newPosition])) {
            if ($newScore < $frontierScores[$newPosition]) {
                $frontierScores[$newPosition] = $newScore;
                $frontiers[$newPosition] = $track;
            } elseif ($newScore == $frontierScores[$newPosition]) {
                $frontiers[$newPosition] = array_merge($frontiers[$newPosition], $track);
            }
            continue;
        }
        $frontiers[$newPosition] = $track;
        $frontierScores[$newPosition] = $newScore;
    }
}

while ($frontiers) {
    asort($frontierScores);
    $frontierKey = array_key_first($frontierScores);
    $score = array_shift($frontierScores);
    [$x, $y, $d] = explode(',', $frontierKey);
    $positionScores["$y,$x"] = $score;
    $frontierTrack = $frontiers[$frontierKey];
    unset($frontiers[$frontierKey]);
    if(findPath($frontierKey,$frontierTrack, $score)) {
        break;
    }
}
echo "<br>";
echo "<p>The answer for part 1 is $goalscore</p>";
$pt2 = 0;
$pt2 = count_chars(join("", array_map('join', $map)), 1)[ord('0')];
echo "<p>Part 2: $pt2 </p>";
foreach ($map as $y => $row) {
    foreach ($row as $x => $cell) {
        switch ($cell) {
            case '#':
                $bg = 'black';
                break;
            case 'S':
                $bg = 'green';
                break;
            case 'E':
                $bg = 'yellow';
                break;
            case '0':
                $bg = 'green';
                break;
            default:
                $bg = 'white';
        }
        echo "<span style='display:inline-block; color: $bg; width: 10px; background-color: $bg;'>&nbsp;</span>";
    }
    echo "<br>";
}