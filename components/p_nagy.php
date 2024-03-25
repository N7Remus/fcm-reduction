<?php



use Shuchkin\SimpleXLSX;

include "SimpleXLSX.php";
var_dump("OK");
echo memory_get_usage() . "\n"; // 36640

ini_set("memory_limit", "-1");

if ($xlsx = SimpleXLSX::parse('out6.xlsx')) {
    echo memory_get_usage() . "\n"; // 36640
    $x = $xlsx->rows();
    /*foreach ($x[0] as $key => $value) {
        $initial_conncept[$value] = 0;
    }*/
    $initial_conncept = array_fill(0, count($x[0]) - 1, 0);
    // remove first empty cell
    //array_shift($init_state);
    array_shift($x);
    //$connection_matrix = $x;
    $earray = array_fill(0, count($x[0]) - 1, 0);
    foreach ($x as $key => $value) {
        $W[$key] = array_replace($earray, array_filter(array_slice($value, 1)));
    }
    echo memory_get_usage() . "\n"; // 36640

} else {
    echo SimpleXLSX::parseError();


    $initial_conncept = [
        0.2, // 'C1' => 
        0.15, // 'C2' => 
        0.1, // 'C3' => 
        0.1 // 'C4' => 
    ];
    $W = [
        [0, 0.5, 0, 0], // 'C1'
        [-1, 0, -1, 1], // 'C2'
        [0, 1, 0, 0], // 'C3'
        [0, -1, 0, 0]  // 'C4' 
    ];
}

$n = count($initial_conncept); // ?

var_dump($n);
//die ();

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
    if ($sum / (($n - 2) * 8) < $E) {
        var_dump($sum / (($n - 2) * 8));

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


for ($i = 0; $i < 1; $i++) {
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
echo memory_get_usage() . "\n"; // 36640
