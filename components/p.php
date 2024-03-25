<?php
$initial_conncept = [
    0.2, // 'C1' => 
    0.15, // 'C2' => 
    0.1, // 'C3' => 
    0.1 // 'C4' => 
];

$n = count($initial_conncept); // ?

$W = [
    [0, 0.5, 0, 0], // 'C1'
    [-1, 0, -1, 1], // 'C2'
    [0, 1, 0, 0], // 'C3'
    [0, -1, 0, 0]  // 'C4' 
];

function hasNextElement(array $_array)
{
    return next($_array) !== false ?: key($_array) !== null;
}
/*
Function isNear A is called several times in buildCluster to decide whether
the current concept Ci can become a member of cluster K or not.
*/
function isNearA($j, $i, $E)
{
    global $n, $W;
    for ($k = 0; $k < $n; $k++) {
        if ($k != $i && $k != $j) {
            echo $i . "." . $j . PHP_EOL;
            if (
                ($W[$i][$j] - $W[$j][$k]) / 2 >= $E ||
                ($W[$k][$i] - $W[$k][$j]) / 2 >= $E
            ) {
                return false;
            }
        }
    }
    return true;
}

function isNearC($i, $j, $E)
{
    $sum = 0;
    global $n, $W;
    for ($k = 0; $k < $n; $k++) {
        if ($k != $i && $k != $j) {
            $sum = $sum + pow($W[$i][$k] - $W[$j][$k], 2);
            $sum = $sum + pow($W[$k][$i] - $W[$k][$j], 2);
        }
    }
    //var_dump($sum / (($n - 2) * 8));
    if ($sum / (($n - 2) * 8) < $E) {
        return true;
    } else {
        return false;
    }
}



function buildCluster($initial_conncept, $E)
{
    // $initial concept - index
    // value of $E is between 0 and 1
    $K = [$initial_conncept];
    global $n;
    for ($i = 0; $i < $n; $i++) {

        if ($i != $initial_conncept) {
            $member = true;
            do {
                $j = next($K);
                $member = isNearC($j, $i, $E);
                if ($member) {
                    $K[] = $i;
                }
            }
            while ($member && hasNextElement($K));
        }
    }

    return $K;
}


for ($i = 0; $i < 4; $i++) {
    echo "Start $i";
    $b = buildCluster($i, 0.2);
    if (count($b) > 2) {
        echo PHP_EOL . "K" . ($i + 1) . ":";

        array_shift($b);
        foreach ($b as $key => $value) {
            echo "C" . ($value + 1) . ",";
        }
    }
    echo PHP_EOL;

}
