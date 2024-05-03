<?php

use Shuchkin\SimpleXLSX;

include  DIR . "include/SimpleXLSX.php";
include  DIR . "ajax_mod/model.php";
ini_set("memory_limit", "-1");
?>

<header class="py-3 mb-3 border-bottom">
    <div class="container-fluid d-grid gap-3 align-items-center" style="grid-template-columns: 1fr;">

        <form id="main" class="" action="" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="">
                        Modell feltöltés 
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="submit" class="btn btn-sm  btn-outline-primary">Feltöltés</button>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tmp_name">Model név:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tmp_name">Válassz modellt:</label>
                    <input type="file" name="files[]" id="file" accept=".xlsx, text/plain" class="form-control">
                </div>
            </div>
        </form>


    </div>
</header>

<?php


if (!empty($_FILES)) {
    if (!empty($_FILES["files"]) && !empty($_FILES["files"]["tmp_name"]) && !empty($_FILES["files"]["tmp_name"][0])) {

        if ($xlsx = SimpleXLSX::parse($_FILES["files"]["tmp_name"][0])) {
            echo "OK";
            $x = $xlsx->rows();
            foreach ($x[0] as $key => $value) {
                $init_state[$value] = 0;
            }

            // remove first empty cell
            array_shift($init_state);

            array_shift($x);
            //$connection_matrix = $x;

            $earray = array_fill(0, count($x[0]) - 1, 0);

            foreach ($x as $key => $value) {
                //$conncept_name = $value[0];
                $connection_matrix[] = array_replace($earray, array_filter(array_slice($value, 1)));
                //print_r($connection_matrix);
                //die();
            }

            $params = [
                "name" => $_POST["name"],
                "init_state" => json_encode($init_state),
                "connection_matrix" => gzcompress(json_encode($connection_matrix), 9),
            ];

            $id = saveModel($params);
            if ($id) {
                $url = "/model/$id";
                // clear out the output buffer
                while (ob_get_status()) {
                    ob_end_clean();
                }
                // no redirect
                header("Location: $url");
                exit();
            } else {
                /*
                Adatbázis hiba
                */
            }
        } else {
            // echo SimpleXLSX::parseError();
            echo "Cant open file";
        }
    }
}
?>