<?php
include "head.php";
?>

<?php include "header.php";
?>
<div class="container">

    <?php

    // first, exploding the URI, getting its parts
    $uri = explode('/', $_SERVER['REQUEST_URI']);

    // now we have [ '',  'login', 'id', 36' ]
    $page = $uri[1]; // 'login' here

    // now, finding the parameters
    for ($i = 2; $i < count($uri) - 2; $i += 2) $_GET[$uri[$i]] = $uri[$i + 1];

    // This will get you: [ 'id' => 36 ]
    # load site by uri


    switch ($page) {

        case 'table2':
            include "components/table2.php";
            break;

        case 'table3':
            include "components/table3.php";
            break;
        case 'table4':
            include "components/table4.php";
            break;

        case 'python':
            include "components/python/index.php";
            break;

        default:
            include "components/table.php";
            break;
    }

    ?>
</div>