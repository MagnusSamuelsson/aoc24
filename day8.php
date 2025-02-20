<?php
$input = file_get_contents('inputs/day8.txt');
$pattern = '/[^a-zA-Z0-9]/';
$replacement = '';
$antennas = preg_replace($pattern, $replacement, $input);
$antennas = str_split($antennas, 1);
$frequenciesCount = array_count_values($antennas);
$antennas = array_unique($antennas);
$map = explode("\n", $input);
$map = array_map(function($line) {
    return str_split(trim($line), 1);
}, $map);

$mapCopy = $map;

function findAntennaLocation($map, $antennas): array {
    $AntennaLocations = [];
    foreach ($map as $y => $line) {
        foreach ($line as $x => $char) {
            if (in_array($char, $antennas)) {
                $AntennaLocations[$char][] = ['y' => $y, 'x' => $x];
            }
        }
    }
    return $AntennaLocations;
}
function findAntinodeLocation($location1, $location2) {
    $yDiff = $location2['y'] - $location1['y'];
    $xDiff = $location2['x'] - $location1['x'];
    $nextY = $location2['y'] + $yDiff;
    $nextX = $location2['x'] + $xDiff;
    return ['y' => $nextY, 'x' => $nextX];
}

function reverseString($string): string {
    return strrev($string);
}

$AntennaLocations = findAntennaLocation($map, $antennas);

$pt1Antinodes = 0;
foreach ($AntennaLocations as $frequency => $locations) {
    foreach ($locations as $keyInput => $location) {
        foreach ($locations as $key2 => $location2) {
            if ($location === $location2) {
                continue;
            }
            $antinode = findAntinodeLocation($location, $location2);
            if (isset($map[$antinode['y']][$antinode['x']]) && $map[$antinode['y']][$antinode['x']] != '#') {
                if ($mapCopy[$antinode['y']][$antinode['x']] == $frequency) {
                    echo "<p>Yes";
                }
                $map[$antinode['y']][$antinode['x']] = "#";
                $pt1Antinodes += 1;
            }

        }
    }
}
$pt2antinodes = 0;
$map = $mapCopy;
foreach ($AntennaLocations as $frequency => $locations) {
    foreach ($locations as $keyInput => $location) {
        if ($map[$location['y']][$location['x']] != "#") {
            $map[$location['y']][$location['x']] = "#";
            $pt2antinodes += 1;
        }
        foreach ($locations as $key2 => $location2) {
            if ($location === $location2) {
                continue;
            }
            $antinode = findAntinodeLocation($location, $location2);
            $previousAntinode = $location2;
            while (isset($map[$antinode['y']][$antinode['x']])) {
                if ($map[$antinode['y']][$antinode['x']] != "#") {
                    $map[$antinode['y']][$antinode['x']] = "#";
                    $pt2antinodes += 1;
                }
                $antinodeCopy = $antinode;
                $antinode = findAntinodeLocation($previousAntinode,$antinode);
                $previousAntinode = $antinodeCopy;
            }

        }
    }
}

echo "<p>The answer for part 1 is $pt1Antinodes";
echo "<p>The answer for part 2 is $pt2antinodes";