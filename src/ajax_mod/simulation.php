<?php

// returns all models
function getSimulations()
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `simulations`";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [];

    if ($sel->execute($p)) {
        $ret = $sel->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }

    return $ret;
}

function getSimulation($id)
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `simulations` WHERE sid=?";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [$id];

    if ($sel->execute($p)) {
        $ret = $sel->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }

    return $ret;
}


// save a new model
function saveSimulation($params)
{
    global $fcm_pdo;

    $sel = $fcm_pdo->prepare("INSERT INTO model (model_name, model_init_state,model_conn_matrix) VALUES (?,?,?)");
    $p = [
        $params["name"],
        $params["init_state"],
        $params["connection_matrix"]
    ];
    $res = $sel->execute($p);
    if ($res) {
        $id = $fcm_pdo->lastInsertId();
        return $id;
    } else {
        return false;
    }
}

// override a model by id
function updateSimulation()
{
    global $fcm_pdo;

    $sel = $fcm_pdo->prepare("INSERT INTO model (model_init_state,model_conn_matrix) VALUES (?,?)");
    $p = [
        $_POST["init_state"],
        $_POST["connection_matrix"]
    ];
    $res = $sel->execute($p);

    return $res;
}
