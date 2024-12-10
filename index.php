<?php
for ($aocDay = 0; $aocDay <= 9; $aocDay++) {
    $start = microtime(true);
    echo "<h1>Day $aocDay</h1>";
    flush();
    if (file_exists("day$aocDay.php")) {
        include "day$aocDay.php";
    }
    echo "<p>Time: " . round((microtime(true) - $start) * 1000, 2) . " ms";
    flush();
}
