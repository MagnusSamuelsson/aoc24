<?php

class Machine {
    public $buttonAx;
    public $buttonAy;
    public $buttonBx;
    public $buttonBy;
    public $targetX;
    public $targetY;
    public $tokens = 0;
    public function __construct($machine, $part2 = 0) {
        $machine = preg_split('/\D+/', $machine);
        $this->buttonAx = (int)$machine[1];
        $this->buttonAy = (int)$machine[2];
        $this->buttonBx = (int)$machine[3];
        $this->buttonBy = (int)$machine[4];
        $this->targetX = (int)$machine[5] + $part2;
        $this->targetY = (int)$machine[6] + $part2;
        $this->cramersRule();
    }
    public function cramersRule() {
        $det = $this->buttonAx * $this->buttonBy - $this->buttonAy * $this->buttonBx;
        if ($det == 0) {
            return false;
        }
        $a = ($this->targetX * $this->buttonBy - $this->targetY * $this->buttonBx) / $det;
        $b = ($this->buttonAx * $this->targetY - $this->buttonAy * $this->targetX) / $det;
        if (is_int($a) && is_int($b)) {
            $this->tokens = $a * 3 + $b;
        } else {
            $this->tokens = 0;
        }
    }
}

$input = file_get_contents('inputs/day13.txt');
$machineInputs = explode(PHP_EOL.PHP_EOL, $input);
$sumpt1 = 0;
foreach ($machineInputs as $machineInput) {
    $machine = new Machine($machineInput);
    $sumpt1 += $machine->tokens;
}

$sum = 0;
foreach ($machineInputs as $machineInput) {
    $machine = new Machine($machineInput, 10000000000000);
    $sum += $machine->tokens;
}

echo "<p>The answer for part 1 is: $sumpt1";
echo "<p>The answer for part 2 is: $sum";