<?php

class Day24 {
    public array $wires = [];
    public array $unknownWires = [];
    public array $functions = [];
    public array $backTracer = [];
    public array $wrongOutputs = [];
    public function __construct($input) {
        [$presetWires, $functions] = explode(PHP_EOL.PHP_EOL, $input);
        foreach (explode(PHP_EOL, $presetWires) as $wire) {
            [$name, $value] = explode(': ', $wire);
            $this->wires[$name] = (int)$value;
        }
        foreach (explode(PHP_EOL, $functions) as $function) {
            $function = str_replace(' -> ', ' ', $function);
            [$wire1, $operator, $wire2, $output] = explode(' ', $function);

            if (!isset($this->wires[$wire1])) {
                $this->unknownWires[$wire1] = true;
            }
            if (!isset($this->wires[$wire2])) {
                $this->unknownWires[$wire2] = true;
            }
            if (!isset($this->wires[$output])) {
                $this->unknownWires[$output] = true;
            }
            $this->functions[] = [$wire1, $operator, $wire2, $output];
        }
    }

    public function solveFunctions(): void {
        foreach ($this->functions as $function) {
            [$wire1, $operator, $wire2, $output] = $function;
            if (isset($this->wires[$output])) {
                continue;
            }
            if (!isset($this->wires[$wire1]) || !isset($this->wires[$wire2])) {
                continue;
            }

            switch ($operator) {
                case 'AND':
                    $this->wires[$output] = (bool)$this->wires[$wire1] & (bool)$this->wires[$wire2];
                    unset($this->unknownWires[$output]);
                    break;
                case 'OR':
                    $this->wires[$output] = (bool)$this->wires[$wire1] | (bool)$this->wires[$wire2];
                    unset($this->unknownWires[$output]);
                    break;
                case 'XOR':
                    $this->wires[$output] = (bool)$this->wires[$wire1] ^ (bool)$this->wires[$wire2];
                    unset($this->unknownWires[$output]);
                    break;
                default:
                    break;
            }

            $this->backTracer[$output][] = $wire1;
            $this->backTracer[$output][] = $wire2;

           /*  if (isset($this->backTracer[$wire1])) {
                foreach($this->backTracer[$wire1] as $backTracer) {
                    $this->backTracer[$output][] = $backTracer;
                }
            }
            if (isset($this->backTracer[$wire2])) {
                foreach($this->backTracer[$wire2] as $backTracer) {
                    $this->backTracer[$output][] = $backTracer;
                }
            } */
            if (str_starts_with($output, 'z')) {
                $wireNumber = (int)str_replace('z', '', $output);
                if ($wireNumber > 44) {
                    continue;
                }
                if (strlen($wireNumber) == 1) {
                    $wireNumber = "0$wireNumber";
                }
                $x = $this->wires["x$wireNumber"];
                $y = $this->wires["y$wireNumber"];
                if ((bool) $x & (bool) $y != (bool)$this->wires[$output]) {
                    echo "<p>Problem for wire $output while Calculating $x AND $y = ". $this->wires[$output];
                    $this->wrongOutputs[$output] = true;
                }
            }
        }
    }
}

$input = file_get_contents('inputs/day24.txt');
$day24 = new Day24($input);
while (count($day24->unknownWires) > 0) {
    $day24->solveFunctions();
}


$zWires = [];
foreach($day24->wires as $wire => $value) {
    if (str_starts_with($wire, 'z')) {
        $number = (int)str_replace('z', '', $wire);
        $zWires[$number] = $value;
    }
}
krsort($zWires);
echo "<p>The answer for part 1 is ". bindec(implode('', $zWires));

foreach($day24->wrongOutputs as $wrongOutput => $value) {
    echo "<pre>"; var_dump($day24->backTracer[$wrongOutput]); echo "</pre>";
}
