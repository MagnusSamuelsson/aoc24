<?php
class Day17 {
    public int $regA = 0;
    public int $regB = 0;
    public int $regC = 0;
    public array $output = [];
    public array $program = [];
    public int $instructionPointer = 0;
    public function operand0(): int {
        return  0;
    }
    public function operand1(): int {
        return  1;
    }
    public function operand2(): int {
        return 2;
    }
    public function operand3(): int {
        return 3;
    }

    public function operand4(): int {
        return $this->regA;
    }

    public function operand5(): int {
        return $this->regB;
    }
    public function operand6(): int {
        return $this->regC;
    }
    public function operand7(): int {
        return 0;
    }
    public function opcode0($operand) {
        $numerator = $this->regA;
        $denominator = $this->{"operand$operand"}();
        $this->regA = intval($numerator / pow(2, $denominator));
        $this->instructionPointer += 2;
        return true;
    }
    public function opcode1($operand) {
        $this->regB = $this->regB ^ $operand;
        $this->instructionPointer += 2;
        return true;
    }
    public function opcode2($operand) {
        $combo = $this->{"operand$operand"}();
        $this->regB = $combo % 8;
        $this->instructionPointer += 2;
        return true;
    }
    public function opcode3($operand) {
        if ($this->regA === 0) {
            $this->instructionPointer += 2;
            return true;
        }
        $this->instructionPointer = $operand;
        return true;
    }
    public function opcode4($operand) {
        $this->regB = $this->regB ^ $this->regC;
        $this->instructionPointer += 2;
        return true;
    }
    public function opcode5($operand) {
        $this->output[] = $this->{"operand$operand"}($operand) % 8;
        $this->instructionPointer += 2;
        return true;
    }
    public function opcode6($operand) {
        $numerator = $this->regA;
        $denominator = $this->{"operand$operand"}();
        $this->regB = intval($numerator / pow(2, $denominator));
        $this->instructionPointer += 2;
        return true;
    }
    public function opcode7($operand) {
        $numerator = $this->regA;
        $denominator = $this->{"operand$operand"}();
        $this->regC = intval($numerator / pow(2, $denominator));
        $this->instructionPointer += 2;
        return true;
    }
    public function reset($a = 0) {
        $this->regA = $a;
        $this->regB = 0;
        $this->regC = 0;
        $this->output = [];
        $this->instructionPointer = 0;
    }
    public function run() {
        for($i = 0; $this->instructionPointer < count($this->program); $i++) {
            $opcode = $this->program[$this->instructionPointer];
            $operand = $this->program[$this->instructionPointer + 1];
            $this->{"opcode$opcode"}($operand);
        }
        return $this->output;
    }
}
$input = file_get_contents('inputs/day17.txt');
$input = explode("\n", $input);
$initialA = intval(explode(' A: ', $input[0])[1]);
$program = explode(',', str_replace('Program: ','',$input[4]));

$day17 = new Day17();
$day17->program = $program;

$day17->reset($initialA);
$day17->run();
echo "<p> The answer for part 1 is: " . implode(',', $day17->output);

$aOctString = "";
for ($programKey = count($day17->program) - 1; $programKey >= 0; $programKey--) {
    for ($oct = 0; $oct < 8; $oct++) {
        $a = octdec($aOctString . $oct);
        $day17->reset($a);
        $day17->run();
        if ($day17->output[0] == $program[$programKey]) {
            $aOctString .= $oct;
            break;
        }
    }
}

echo "<p> The answer for part 2 is: $a";
