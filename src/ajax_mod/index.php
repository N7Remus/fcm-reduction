<?php

// What to call $a 

// get_model

// save_model

// save_job
// update_job_status
// get_job
require_once  DIR . "include/functions.php";
require_once  DIR . "ajax_mod/model.php";
require_once  DIR . "ajax_mod/simulation.php";
require_once  DIR . "ajax_mod/reduction.php";

if (!empty($_GET["a"])) {
    
    // Model
    if ($_GET["a"] == "get_models") {
        if ($data = getModels()) {
            echo json_encode(array("result" => "0", "text" => _("Siker"), "data" => json_encode($data)));
        } else {
            echo json_encode(array("result" => "1", "text" => _("Hiba történt")));
        }
    }
    // Model
    if ($_GET["a"] == "get_model") {
        $id = intval($_POST["model_id"]);
        if ($id == 0) {
            echo json_encode(array("result" => "1", "text" => _("Hiányos paraméter")));
        } else if ($data = getModel($id)) {
            $data["model_conn_matrix"]=base64_encode($data["model_conn_matrix"]);
            echo json_encode(array("result" => "0", "text" => _("Siker"), "data" => $data));
        } else {
            echo json_encode(array("result" => "1", "text" => _("Hiba történt")));
        }
    }

    if ($_GET["a"] == "save_model") {
        $params = [
            "name" => $_POST["model_name"],  // text
            "init_state" => $_POST["init_state"],  // json
            "connection_matrix" => $_POST["connection_matrix"]  // json
        ];
        if ($data = saveModel($params)) {
            // model id 
            echo json_encode(array("result" => "0", "text" => _("Siker"), "data" => $data));
        } else {
            echo json_encode(array("result" => "1", "text" => _("Hiba történt")));
        }
    }
    if ($_GET["a"] == "update_model") {
        $id = intval($_POST["model_id"]);
        if ($id == 0) {
            echo json_encode(array("result" => "1", "text" => _("Hiányos paraméter")));
        } else if ($data = updateModel()) {
            // model id 
            echo json_encode(array("result" => "0", "text" => _("Siker"), "data" => $data));
        } else {
            echo json_encode(array("result" => "1", "text" => _("Hiba történt")));
        }
    }
}
exit();
