<?php
$input = file_get_contents('inputs/day6.txt');
$input = explode("\n", $input);
$xStrings = [];
$yStrings = [];
foreach ($input as $y => $line) {
    $yStrings[] = $line;
    $line = trim($line);
    if (strpos($line, "^")) {
        $startpos['x'] = strpos($line, "^");
        $startpos['y'] = $y;
    }
    $x = str_split($line, 1);
    $labMap[]  = $x;
    foreach ($x as $k => $v) {
        if (!isset($xStrings[$k])) {
            $xStrings[$k] = '';
        }
        $xStrings[$k] .= $v;
    }
}
$xStrings = array_map('trim', $xStrings);
$yStrings = array_map('trim', $yStrings);
class Day6 {
    public $labMap;
    public $startpos;
    public $position;
    public $xStrings = [];
    public $yStrings = [];
    public $direction = 0;
    public $loopmap = [];
    public $loop = false;
    public $maxX = 0;
    public $maxY = 0;
    public $visited = [];
    public $turnedAt = [];
    public $directions = [
        ['x' => 0, 'y' => -1],
        ['x' => 1, 'y' => 0],
        ['x' => 0, 'y' => 1],
        ['x' => -1, 'y' => 0]
    ];
    public function __construct(array $map, array $startpos) {
        $this->labMap = $map;
        $this->maxX = count($map[0]);
        $this->maxY = count($map);
        $this->startpos = $startpos;
        $this->position = $startpos;
    }
    public function move(): void {
        $this->position['x'] += $this->directions[$this->direction]['x'];
        $this->position['y'] += $this->directions[$this->direction]['y'];

    }
    public function findNextStop($string, $position, $reverse = true) {
        if ($reverse) {
            $string = strrev($string);
            $offset = strlen($string) - $position;
        } else {
            $offset = $position;
        }

        $pos = strpos($string, '#', $offset) - $offset;
        if (abs($pos) != $pos) {
            return $reverse ? 0 : strlen($string) - 1;
        }
        return $reverse ? $position-$pos : $position+$pos - 1;
    }
    public function moveFast(): void {
        $y = $this->position['y'];
        $x = $this->position['x'];
        switch($this->direction) {
            case 0:
                $this->position['y'] = $this->findNextStop($this->xStrings[$x], $y, true);
                break;
            case 2:
                $this->position['y'] = $this->findNextStop($this->xStrings[$x], $y, false);
                break;
            case 1:
                $this->position['x'] = $this->findNextStop($this->yStrings[$y], $x, false);
                break;
            case 3:
                $this->position['x'] = $this->findNextStop($this->yStrings[$y], $x, true);
                break;
        }
    }
    public function turn() {
        $this->direction = ($this->direction + 1) % 4;
        if (isset($this->labMap[$this->position['y'] + $this->directions[$this->direction]['y']][$this->position['x'] + $this->directions[$this->direction]['x']]) &&
            $this->labMap[$this->position['y'] + $this->directions[$this->direction]['y']][$this->position['x'] + $this->directions[$this->direction]['x']] === "#") {
            $this->turn();
        }
    }
    public function turnedHereBefore(): bool {
        return isset($this->turnedAt[$this->position['y'] . ',' . $this->position['x']]) && in_array($this->direction, $this->turnedAt[$this->position['y'] . ',' . $this->position['x']]);
    }
    public function turnFast(): void {
        $this->turnedAt[$this->position['y'].','.$this->position['x']][] = $this->direction;
        $this->direction = ($this->direction + 1) % 4;
        if (isset($this->labMap[$this->position['y'] + $this->directions[$this->direction]['y']][$this->position['x'] + $this->directions[$this->direction]['x']]) &&
            $this->labMap[$this->position['y'] + $this->directions[$this->direction]['y']][$this->position['x'] + $this->directions[$this->direction]['x']] === "#") {
            $this->turn();
        }
    }
    public function guardExit(): bool {
        if (!isset($this->labMap[$this->position['y'] + $this->directions[$this->direction]['y']][$this->position['x'] + $this->directions[$this->direction]['x']])) {
            return true;
        } else {
            if ($this->labMap[$this->position['y'] + $this->directions[$this->direction]['y']][$this->position['x'] + $this->directions[$this->direction]['x']] === "#") {
                $this->turn();
            }
            return false;
        }
    }
    public function guardExitFast(): bool {
        return $this->position['x'] + $this->directions[$this->direction]['x'] == $this->maxX ||
            $this->position['x'] + $this->directions[$this->direction]['x'] == -1 ||
            $this->position['y'] + $this->directions[$this->direction]['y'] == $this->maxY ||
            $this->position['y'] + $this->directions[$this->direction]['y'] == -1;
    }
    public function releaseTheGuard(): bool {
        $i = 0;

        while (true) {
            $this->visited[$this->position['y'] .",". $this->position['x']][] = $this->direction;
            if ($this->guardExit()) {
                $this->labMap[$this->position['y']][$this->position['x']] = $this->direction;
                return true;
            }
            $this->labMap[$this->position['y']][$this->position['x']] = $this->direction;
            $this->move();
            $i++;
        }
    }
    public function releaseTheGuardFast(): bool {
        $this->moveFast();
        while (true) {
            if ($this->guardExitFast()) {
                return true;
            }
            if ($this->turnedHereBefore()) {
                return false;
            }
            $this->turnFast();
            $this->moveFast();
        }
    }
    public function changeMap($y,$x,$value): void {
        $this->labMap[$y][$x] = $value;
        $this->xStrings[$x][$y] = $value;
        $this->yStrings[$y][$x] = $value;
    }
}
$day6 = new Day6($labMap, $startpos);
$day6->releaseTheGuard();
$day6->xStrings = $xStrings;
$day6->yStrings = $yStrings;
echo "<p>The answer for part 1 is: " . count($day6->visited);
$possibleBlock = 0;

foreach($day6->visited as $yx => $direction) {
    [$y, $x] = explode(",", $yx);
    $day6->position = $startpos;
    $day6->direction = 0;
    $day6->changeMap($y,$x,"#");
    $day6->turnedAt = [];
    $day6->labMap[$y][$x] = "#";
    if (!$day6->releaseTheGuardFast()) {
        $possibleBlock += 1;
    }
    $day6->changeMap($y,$x,".");
}
echo "<p>The answer for part 2 is: " . $possibleBlock;
