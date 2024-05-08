<?php
ob_start(); // ensures anything dumped out will be caught

session_start();
require_once "include/config.inc.php";
require_once "include/mysql.php";
require_once "include/functions.php";



if (!isset($_SESSION) || !$_SESSION['isAdmin']) {

    include "login.php";
    exit();
}

$version = "0.1";

// first, exploding the URI, getting its parts
$uri = explode('/', $_SERVER['REQUEST_URI']);

// now we have [ '',  'login', 'id', 36' ]
$page = $uri[1]; // 'login' here

// now, finding the parameters
for ($i = 2; $i < count($uri) - 2; $i += 2) $_GET[$uri[$i]] = $uri[$i + 1];

// This will get you: [ 'id' => 36 ]

if ($page == "ajax") {
    include DIR . "ajax_mod/index.php";
    exit();
}

include "head.php";
?>
<?php include "header.php";
?>
<div class="container">
    <?php
    switch ($page) {

        case 'new':
            include "components/UI/new.php";
            break;
        case 'ui':
            include "components/UI/index.php";
            break;
        case 'model':
            include "components/UI/model.php";
            break;
        case 'models':
            include "components/UI/models.php";
            break;

        case 'leaflet':
            include "components/leaflet/index.php";
            break;
            /* case 'ajax':
            include "components/ajax.php";
            break; */
        case 'main':
            include "components/table_6.php";
            break;
        case 'table2':
            include "components/table2.php";
            break;

        case 'table3':
            include "components/table3.php";
            break;
        case 'table4':
            include "components/table4.php";
            break;
        case 'table5':
            include "components/table_5.php";
            break;

        case 'python':
            include "components/python/index.php";
            break;

        case 'login':
            include "login.php";
            break;

        case 'logout':
            include "logout.php";
            break;
        case 'users':
            include "components/users/index.php";
            break;
        case 'simulations':
            include "components/UI/simulations.php";
            break;
        case 'reductions':
            include "components/UI/reductions.php";
            break;

        case 'simulation':
            include "components/simulation/index.php";
            break;
        case 'reduction':
            include "components/reduction/index.php";
            break;


        default:
            include "components/homepage/index.php";
            break;
    }

    ?>
</div>