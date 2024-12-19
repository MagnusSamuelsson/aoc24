<?php
$input = file_get_contents('inputs/day14.txt');
$input = preg_split('/\r?\n/', $input);
$robots = [];
foreach($input as $key => $line) {
    preg_match_all('/-?\d+/', $line, $robs);
    $robots = array_merge($robots, $robs);
}
$robotsCopy = $robots;
$width = 101;
$height = 103;
$map = [];
for ($i = 0; $i < $height; $i++) {
    $map[$i] = [];
    for ($j = 0; $j < $width; $j++) {
        $map[$i][$j] = '.';
    }
}
$mapCopy = $map;

function newRobotPosition($robot, $seconds) {
    global $width, $height, $map;
    $x = $robot[0] + $robot[2] * $seconds;
    $y = $robot[1] + $robot[3] * $seconds;

    if (( $y % $height ) < 0) {
        $y = $height + ( $y % $height );
    } else {
        $y = $y % $height;
    }
    if (( $x % $width ) < 0) {
        $x = $width + ( $x % $width );
    } else {
        $x = $x % $width;
    }
    if ($map[$y][$x] === '.') {
        $map[$y][$x] = 1;
    } else {
        $map[$y][$x]++;
    }
    $robot[0] = $x;
    $robot[1] = $y;
    return $robot;
}
foreach ($robots as $key => $robot) {
    $robots[$key] = newRobotPosition($robot, 100);
}

$quad1 = 0;
$quad2 = 0;
$quad3 = 0;
$quad4 = 0;
foreach ($map as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell != '.' && $y < floor($height/2) && $x < floor($width/2)) {

            $quad1 += $cell;
        }
        if ($cell != '.' && $y < floor($height/2) && $x > floor($width/2)) {
            $quad2 += $cell;
        }
        if ($cell != '.' && $y > floor($height/2) && $x < floor($width/2)) {
            $quad3 += $cell;
        }
        if ($cell != '.' && $y > floor($height/2) && $x > floor($width/2)) {
            $quad4 += $cell;
        }
    }
}

$robots = $robotsCopy;
for ($seconds = 1; $seconds < 100000; $seconds++) {
    $map = $mapCopy;
    foreach ($robots as $key => $robot) {
        $robots[$key] = newRobotPosition($robot, 1);
    }

    foreach ($map as $y => $row) {
        if (preg_match('/(?<!1)111111111111111(?!1)/', implode('', $row))) {
            break 2;
        }
    }
}

echo "<p>The answer for part 1 is: " . $quad1 * $quad2 * $quad3 * $quad4;
echo "<p>The answer for part 2 is: " . $seconds;
