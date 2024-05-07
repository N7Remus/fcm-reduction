<?php
include DIR . "ajax_mod/model.php";

// var_dump(getModel($uri[2]));
$model = getModel($uri[2]);

/* var_dump($_POST, $uri[2]); */

if (!empty($_POST)) {
    if (!empty($_POST["i"]) &&!empty($_POST["i"])) {

        // update matrix and state values in db
        echo "update matrix and state values in db";
        updateModel($uri[2],$_POST["i"],gzcompress($_POST["w"]), 9);
        exit();


    }

    if (!empty($_POST["name"])) {
        // modell paraméterek mentése
        echo "modell paraméterek mentése";
        if (empty($_POST["itter"])) {
            $_POST["itter"] = 10;
        }
        if (empty($_POST["lambda"])) {
            $_POST["lambda"] = 1;
        }
        if (empty($_POST["interf"])) {
            $_POST["interf"] = 1;
        }
        if (empty($_POST["transzfer"])) {
            $_POST["transzfer"] = 1;
        }
        if (empty($_POST["E"])) {
            $_POST["E"] = 0.001;
        }

        if (updateModelParams($uri[2],$_POST["name"], json_encode($_POST))){
            echo "Sikeres mentés";
        }else{
            echo "Sikertelen mentés";
        }

    }
}



$init_state = json_decode($model["model_init_state"], true);
$model_params = @json_decode($model["model_options"], true);
// Custom sorting function

function customSort($init_state)
{
    $keys = array_keys($init_state);
    @usort($keys, function ($a, $b) {
        // Ignore the first character while comparing keys
        $keyA = substr($a, 1);
        $keyB = substr($b, 1);
        return intval($keyA) > intval($keyB);
    });

    // Reconstruct the array with sorted keys
    $array = array();
    foreach ($keys as $key) {
        $array[$key] = $init_state[$key];
    }
    return $array;
}

$init_state = customSort($init_state);
if (count($init_state) < 2000) {
    $connection_matrix = json_decode(gzuncompress($model["model_conn_matrix"]), true);
}

$fcm_sigma_ajax_url = "/ajax/?a=get_model";
// post model id
$model_id = $model["mid"];
?>

<?php
include DIR . "components/visual/menu.php";

?>


<div class="bd-example">
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                type="button" role="tab" aria-controls="nav-home" aria-selected="true">Vizualizáció</button>

            <button class="nav-link" id="nav-table-tab" data-bs-toggle="tab" data-bs-target="#nav-table" type="button"
                role="tab" aria-controls="nav-table" aria-selected="false">Táblázatos forma</button>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">

        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php include DIR . "components/visual/sigma_apiload.php" ?>
        </div>

        <div class="tab-pane fade" id="nav-table" role="tabpanel" aria-labelledby="nav-table-tab">
            <?php if (count($init_state) < 100) {
                include DIR . "components/visual/table.php";
            } else {
                echo "A táblázat mérete túl nagy a megjelenítéshez";
            }
            ?>
        </div>
    </div>
</div>
<br>