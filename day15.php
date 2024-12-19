<?php
$input = file_get_contents('inputs/day15.txt');
[$map, $moves] = array_map( 'trim',explode(PHP_EOL.PHP_EOL, $input));
$map = array_map( 'str_split',explode(PHP_EOL, $map));
$moves = str_replace(PHP_EOL, '', $moves);
$moves = str_split($moves);

class Day15 {
    public array $map;
    public array $part2Map;
    public array $mapYStrings;
    public array $mapXStrings;
    public int $x;
    public int $y;
    public int $startpt2X;
    public int $startpt2Y;
    public int $coordinateSum = 0;
    public int $coordinateSum2 = 0;
    private array $directions = [
        "^" => ['x' => 0, 'y' => -1],
        ">" => ['x' => 1, 'y' => 0],
        "v" => ['x' => 0, 'y' => 1],
        "<" => ['x' => -1, 'y' => 0]
    ];
    public function __construct(array $map) {
        $this->map = $map;
        foreach ($map as $y => $row) {
            $this->part2Map[$y] = [];
            foreach ($row as $x => $cell) {
                if ($cell === '@') {
                    $this->x = $x;
                    $this->y = $y;
                }
                switch ($cell) {
                    case '@':
                        $this->startpt2Y = $y;
                        $this->startpt2X = count($this->part2Map[$y]);
                        $this->part2Map[$y][] = '@';
                        $this->part2Map[$y][] = '.';
                        break;
                    case 'O':
                        $this->part2Map[$y][] = '[';
                        $this->part2Map[$y][] = ']';
                        break;
                    case '.':
                        $this->part2Map[$y][] = '.';
                        $this->part2Map[$y][] = '.';
                        break;
                    case '#':
                        $this->part2Map[$y][] = '#';
                        $this->part2Map[$y][] = '#';
                        break;
                }

            }
        }
    }
    public function resetStart(): void {
        $this->x = $this->startpt2X;
        $this->y = $this->startpt2Y;
    }
    public function findBoxCoordinates(): void {
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell === 'O') {
                    $this->coordinateSum += $x + $y * 100;
                }
            }
        }
    }
    public function findBoxCoordinates2(): void {
        foreach ($this->part2Map as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell === '[') {
                    $this->coordinateSum2 += $x + $y * 100;
                }
            }
        }
    }
    public function printMap(): void {
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $cell) {
                switch ($cell) {
                    case '#':
                        $bg = 'black';
                        $border = 'border-right: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;';
                        break;
                    case '@':
                        $bg = 'green';
                        $border = 'border-radius:10px; border-right: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;';
                        break;
                    case 'O':
                        $bg = 'yellow';

                        $border = 'border-right: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;';
                        break;
                    default:
                        $border = 'border-right: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; border-bottom: 1px solid white;';
                        $bg = 'white';
                }
                echo "<span style='display:inline-block;  color: $bg; $border width: 10px; background-color: $bg;'>&nbsp;</span>";
            }
            echo "<br>";
        }
    }
    public function printMap2(): void {
        foreach ($this->part2Map as $y => $row) {
            foreach ($row as $x => $cell) {
                switch ($cell) {
                    case '#':
                        $border = 'border-right: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;';
                        $bg = 'black';
                        break;
                    case '@':
                        $border = 'border-radius:10px; border-right: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;';
                        $bg = 'green';
                        break;
                    case '[':
                        $bg = 'yellow';
                        $border = 'border-right: 1px solid yellow; border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;';

                        break;
                    case ']':
                        $bg = 'yellow';
                        $border = 'border-right: 1px solid black; border-left: 1px solid yellow; border-top: 1px solid black; border-bottom: 1px solid black;';
                        break;
                    default:
                        $border = 'border-right: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; border-bottom: 1px solid white;';
                        $bg = 'white';
                }
                echo "<span style='display:inline-block;  color: $bg; $border width: 10px; background-color: $bg;'>&nbsp;</span>";
            }
            echo "<br>";
        }
    }
    public function moveBox(int $posY, int $posX, string $direction): bool {
        $newY = $posY + $this->directions[$direction]['y'];
        $newX = $posX + $this->directions[$direction]['x'];
        if ($this->map[$newY][$newX] === '#') {
            return false;
        }
        if ($this->map[$newY][$newX] === 'O' && !$this->moveBox($newY, $newX, $direction)) {
            return false;
        }
        $this->map[$posY][$posX] = '.';
        $this->map[$newY][$newX] = 'O';
        return true;
    }
    public function moveRobot(string $direction): void {
        $newY = $this->y + $this->directions[$direction]['y'];
        $newX = $this->x + $this->directions[$direction]['x'];
        if ($this->map[$newY][$newX] === '#') {
            return;
        }
        if ($this->map[$newY][$newX] === 'O' && !$this->moveBox($newY, $newX, $direction)) {
            return;
        }
        $this->map[$this->y][$this->x] = '.';
        $this->map[$newY][$newX] = '@';
        $this->y = $newY;
        $this->x = $newX;
    }
    public function moveRobot2(string $direction): void {
        $newY = $this->y + $this->directions[$direction]['y'];
        $newX = $this->x + $this->directions[$direction]['x'];
        if ($this->part2Map[$newY][$newX] === '#') {
            return;
        }
        $mapCopy = $this->part2Map;
        if (($this->part2Map[$newY][$newX] === '[' || $this->part2Map[$newY][$newX] === ']') &&
            !$this->moveBox2($newY, $newX, $this->part2Map[$newY][$newX] === '[', $direction)) {
            $this->part2Map = $mapCopy;
            return;
        }
        $this->part2Map[$this->y][$this->x] = '.';
        $this->part2Map[$newY][$newX] = '@';
        $this->y = $newY;
        $this->x = $newX;
    }
    public function moveBox2(int $posY, int $posX, bool $left, string $direction): bool {
        $moved = false;
        $newY = $posY + $this->directions[$direction]['y'];
        if ($left) {
            $newXL = $posX + $this->directions[$direction]['x'];
            $newXR = $newXL + 1;
            $posXL = $posX;
            $posXR = $posX + 1;
        } else {
            $newXR = $posX + $this->directions[$direction]['x'];
            $newXL = $newXR - 1;
            $posXL = $posX - 1;
            $posXR = $posX;
        }
        if ($this->part2Map[$newY][$newXL] === '#' || $this->part2Map[$newY][$newXR] === '#') {
            return false;
        }
        if ($this->part2Map[$newY][$newXL] === '.' && $this->part2Map[$newY][$newXR] === '.') {

            $this->part2Map[$posY][$posXL] = '.';
            $this->part2Map[$posY][$posXR] = '.';
            $this->part2Map[$newY][$newXL] = '[';
            $this->part2Map[$newY][$newXR] = ']';
            return true;
        }
        switch ($direction) {
            case '^':
            case 'v':
                if($this->part2Map[$newY][$newXL] != "." && !$this->moveBox2($newY, $newXL, $this->part2Map[$newY][$newXL] == "[", $direction)) {
                    return false;
                }
                if($this->part2Map[$newY][$newXR] != "." && !$this->moveBox2($newY, $newXR, $this->part2Map[$newY][$newXR] == "[", $direction)) {
                    return false;
                }

                break;
            case '<':
                if ($this->part2Map[$newY][$newXL] === ']') {
                    if(!$this->moveBox2($newY, $newXL, $this->part2Map[$newY][$newXL] === '[', $direction)) {
                        return false;
                    }
                }
                break;
            case '>':
                if ($this->part2Map[$newY][$newXR] === '[') {
                    if(!$this->moveBox2($newY, $newXR, $this->part2Map[$newY][$newXR] === '[', $direction)) {
                        return false;
                    }
                }
                break;

        }

        $this->part2Map[$posY][$posXL] = '.';
        $this->part2Map[$posY][$posXR] = '.';
        $this->part2Map[$newY][$newXL] = '[';
        $this->part2Map[$newY][$newXR] = ']';
        return true;
    }
}
$day15 = new Day15($map);


echo "<br>";

foreach ($moves as $move) {
    $day15->moveRobot($move);
}
$day15->findBoxCoordinates();
echo "Part 1: " . $day15->coordinateSum . "<br>";
$day15->printMap();
$day15->resetStart();
foreach ($moves as $move) {
    $day15->moveRobot2($move);
}
$day15->findBoxCoordinates2();
echo "Part 2: " . $day15->coordinateSum2 . "<br>";
$day15->printMap2();