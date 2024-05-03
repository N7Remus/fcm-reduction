<?php

// returns all models
function getReductions()
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `reductions`";
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

function getReduction($id)
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `reductions` WHERE rid=?";
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
function saveReduction($params)
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
function updateReduction()
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
