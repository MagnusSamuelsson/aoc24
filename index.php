<?php
ob_implicit_flush(true);
ob_end_flush();

for ($aocDay = 1; $aocDay <= 25; $aocDay++) {
    $start = microtime(true);
    echo "<h1>Day $aocDay</h1>";
    flush();
    if (file_exists("day$aocDay.php")) {
        include "day$aocDay.php";
    }
    echo "<p>Time: " . round((microtime(true) - $start) * 1000, 2) . " ms";
    flush();
}
