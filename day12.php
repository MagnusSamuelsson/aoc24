<?php
$input = file_get_contents('inputs/day12.txt');
$map = preg_split('/\r?\n/', $input);
foreach ($map as $key => $line) {
    $map[$key] = str_split($line);
}

$directions = [
    ['x' => 0, 'y' => -1],
    ['x' => 1, 'y' => 0],
    ['x' => 0, 'y' => 1],
    ['x' => -1, 'y' => 0]
];

function findGroup($y, $x): array
{
    global $visited, $directions, $map, $sides, $sidecounter;
    if (isset($visited["$y,$x"])) {
        return [0, 0];
    }
    $visited["$y,$x"] = true;
    $plantsInGroup = 0;
    $fences = 0;
    foreach ($directions as $direction) {
        $newfence = false;
        $newX = $x + $direction['x'];
        $newY = $y + $direction['y'];
        if ($newX >= 0 && $newX < count($map[0]) && $newY >= 0 && $newY < count($map)) {
            if ($map[$newY][$newX] === $map[$y][$x]) {
                [$o, $p] = findGroup($newY, $newX);
                $plantsInGroup += $o;
                $fences += $p;
            } else {
                $fences++;
                $sides[$newY][$newX][] = $direction;
                $newfence = true;
            }
        } else {
            $fences++;
            $newfence = true;
            $sides[$newY][$newX][] = $direction;
        }

        if ($newfence) {
            $newside = 0;
            if (!isset($sides[$newY - 1][$newX]) || !in_array($direction, $sides[$newY - 1][$newX])) {
                $newside++;
            }
            if (!isset($sides[$newY + 1][$newX]) || !in_array($direction, $sides[$newY + 1][$newX])) {
                $newside++;
            }
            if (!isset($sides[$newY][$newX - 1]) || !in_array($direction, $sides[$newY][$newX - 1])) {
                $newside++;
            }
            if (!isset($sides[$newY][$newX + 1]) || !in_array($direction, $sides[$newY][$newX + 1])) {
                $newside++;
            }
            if ($newside == 2) {
                $sidecounter--;
            }
            if ($newside == 4) {
                $sidecounter++;
            }
        }
    }
    return [1 + $plantsInGroup, $fences];
}

$visited = [];
$pt1 = 0;
$pt2 = 0;
foreach ($map as $y => $line) {
    foreach ($line as $x => $cell) {
        if (isset($visited["$y,$x"])) {
            continue;
        }
        $sides = [];
        $sidecounter = 0;
        [$plants, $fence] = findGroup($y, $x);
        $pt2 += $sidecounter * $plants;
        $pt1 += $plants * $fence;
    }
}
echo "<p>The answer for part 1 is: $pt1";
echo "<p>The answer for part 2 is: $pt2";