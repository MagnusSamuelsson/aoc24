<?php
$input = file_get_contents('inputs/day23.txt');

class Day23 {
    public array $connections = [];
    public array $threeConnections = [];
    public string $biggestLan;
    public int $biggestLanSize = 0;
    public array $tried;
    public function __construct(string $input) {
        foreach (explode("\n", $input) as $line) {
            [$l, $r] = explode('-', trim($line));
            $this->connections[$l][] = $r;
            $this->connections[$r][] = $l;
        }
    }

    public function findThreeConnections(): void {
        $this->tried = [];
        foreach ($this->connections as $key => $computer) {
            $tried[$key] = true;
            foreach ($computer as $c) {
                if (isset($tried[$c])) {
                    continue;
                }
                foreach($computer as $c2) {
                    if (isset($tried[$c2])) {
                        continue;
                    }
                    if (in_array($c2, $this->connections[$c])) {
                        if (str_starts_with($key, 't') || str_starts_with($c, 't') || str_starts_with($c2, 't')) {
                            $three = [$key, $c, $c2];
                            sort($three);
                            $this->threeConnections[implode('-', $three)] = true;
                        }
                    }
                }
            }

        }
    }
    public function findAllConnections($computer): void {
        $allIntersections = [];
        foreach ($this->connections[$computer] as $c) {
            $tempIntersections = array_intersect($this->connections[$computer], $this->connections[$c]);
            $tempIntersections[] = $c;
            $tempIntersections[] = $computer;
            sort($tempIntersections);
            if (isset($allIntersections[implode('-', $tempIntersections)])) {
                $allIntersections[implode('-', $tempIntersections)]++;
                continue;
            }
            $allIntersections[implode('-', $tempIntersections)] = 1;
        }
        if (max($allIntersections) < $this->biggestLanSize) {
            return;
        }
        arsort($allIntersections);
        foreach ($allIntersections as $key => $value) {
            if ($value < $this->biggestLanSize) {
                continue;
            }
            if ($value == count(explode('-', $key)) - 1) {
                $this->biggestLanSize = $value;
                $this->biggestLan = $key;
            }
        }
    }
    public function findBiggestLan(): void {
        foreach ($this->connections as $key => $computer) {
            $this->findAllConnections($key);
        }
        $this->biggestLan = str_replace('-', ',', $this->biggestLan);
    }
}

$day23 = new Day23($input);
$day23->findThreeConnections();
echo "<p>The answer for part 1 is: " . count($day23->threeConnections) . "</p>";
$day23->findBiggestLan();
echo "<p>The answer for part 2 is: " . $day23->biggestLan . "</p>";