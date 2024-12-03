<?php
if (isset($_GET['reset']) && $_GET['reset'] == 1 && function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache has been cleared.";
}
for ($i = 1; $i <= 3; $i++) {
    $start = microtime(true);
    echo "<h1>Day $i</h1>";
    if (file_exists("day$i.php")) {
        include "day$i.php";
    }
    echo "<p>Time: " . round((microtime(true) - $start) * 1000, 2) . " ms";
}
