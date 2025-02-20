<?php
$input = file_get_contents('inputs/day25.txt');
$input = explode(PHP_EOL.PHP_EOL, $input);
$keysInput = [];
$locksInput = [];

foreach ($input as $line) {
    if (str_starts_with($line, '.....')) {
        $keyInput = explode(PHP_EOL, $line);
        $keysInput[] = array_map('str_split', $keyInput);
    } else {
        $lockInput = explode(PHP_EOL, $line);
        $locksInput[] = array_map('str_split', $lockInput);
    }
}
$keys = [];
foreach($keysInput as $keyInput) {
    $key = [];
    foreach($keyInput as $row => $k) {
        foreach($k as $col => $v) {
            if ($v == '.') {
                $key[$col] = 5 - $row;
            }
        }
    }
    $keys[] = $key;
}
foreach ($locksInput as $lockInput) {
    $lock = [];
    foreach($lockInput as $row => $k) {
        foreach($k as $col => $v) {
            if ($v == '#') {
                $lock[$col] =  $row;
            }
        }
    }
    $locks[] = $lock;
}

function keyFitsInLock($key, $lock) {
    for ($i = 0; $i < 5; $i++) {
        $maxKeyHeight = 6 - $lock[$i];
        if ($key[$i] >= $maxKeyHeight) {
            return false;
        }
    }
    return true;
}


$pt1 = 0;
foreach ($keys as $key) {
    foreach ($locks as $lock) {
        if (keyFitsInLock($key, $lock)) {
            $pt1++;
        }
    }
}

echo "<p>The answer for part 1 is: $pt1";