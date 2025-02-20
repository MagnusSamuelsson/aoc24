<?php
$input = file_get_contents('inputs/day2.txt');
$input = preg_split('/\r?\n/', $input);
$inputArray = [];
foreach ($input as $line) {
    $inputArray[] = preg_split('/\s+/', trim($line));
}
function checkChange($array) {
    $increasing     = true;
    $decreasing     = true;

    for ( $i = 1; $i < count( $array ); $i ++ ) {
        $diff = $array[ $i ] - $array[ $i - 1 ];
        if ( abs( $diff ) < 1 || abs( $diff ) > 3 ) {
            return false;
        }
        if ( $diff > 0 ) {
            $decreasing = false;
        } elseif ( $diff < 0 ) {
            $increasing = false;
        }
        if (!( $increasing || $decreasing )) {
            return false;
        }
    }
    return true;
}
function checkChange2($array) {
    global $part1Counter;
    $increasing     = true;
    $decreasing     = true;

    for ( $i = 1; $i < count( $array ); $i ++ ) {
        $diff = $array[ $i ] - $array[ $i - 1 ];
        if ( abs( $diff ) < 1 || abs( $diff ) > 3 ) {
            return tryRemoveOneIndex($array, $i);
        }
        if ( $diff > 0 ) {
            $decreasing = false;
        } elseif ( $diff < 0 ) {
            $increasing = false;
        }
        if (!( $increasing || $decreasing )) {
            return tryRemoveOneIndex($array, $i);
        }
    }
    $part1Counter++;
    return true;
}
function tryRemoveOneIndex($array, $index) {
    global $part2Counter;
    $levelsCopy = $array;
    unset( $levelsCopy[ $index ] );
    if ( checkChange( array_values( $levelsCopy ) ) ) {
        $part2Counter++;
        return true;
    }
    $levelsCopy = $array;
    unset( $levelsCopy[ $index - 1 ] );
    if ( checkChange( array_values( $levelsCopy ) ) ) {
        $part2Counter++;
        return true;
    }
    $levelsCopy = $array;
    unset( $levelsCopy[ 0 ] );
    if ( checkChange( array_values( $levelsCopy ) ) ) {
        $part2Counter++;
        return true;
    }
    return false;
}
$part1Counter = 0;
$part2Counter = 0;
foreach ($inputArray as $keyInput => $value) {
    checkChange2($value);
}
echo "<p>The answer for part 1 is: $part1Counter";
echo "<p>The answer for part 2 is: ".$part1Counter+$part2Counter;