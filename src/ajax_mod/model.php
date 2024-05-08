<?php

// returns all models
function getModels()
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `model` WHERE uid=?";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [$_SESSION["userId"]];

    if ($sel->execute($p)) {
        $ret = $sel->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }

    return $ret;
}
// returns specific model by id 
function getModelBin($id)
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `model` WHERE mid=? and uid=?";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [$id,$_SESSION["userId"]];

    if ($sel->execute($p)) {
        $ret = $sel->fetch(PDO::FETCH_ASSOC)["model_conn_matrix"];
    } else {
        return false;
    }

    return $ret;
}

function getModel($id)
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `model` WHERE mid=? and uid=?";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [$id,$_SESSION["userId"]];

    if ($sel->execute($p)) {
        $ret = $sel->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }

    return $ret;
}


// save a new model
function saveModel($params)
{
    global $fcm_pdo;

    $sel = $fcm_pdo->prepare("INSERT INTO model (model_name, model_init_state,model_conn_matrix,uid) VALUES (?,?,?,?)");
    $p = [
        $params["name"],
        $params["init_state"],
        $params["connection_matrix"],
        $_SESSION["userId"]
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
function updateModel($model_id,$init_state,$connection_matrix)
{
    global $fcm_pdo;

    $sel = $fcm_pdo->prepare("UPDATE model SET model_init_state = ?,model_conn_matrix = ? WHERE mid=? AND uid=?;");
    $p = [
        $init_state,
        $connection_matrix,
        $model_id,
        $_SESSION["userId"]
    ];
    $res = $sel->execute($p);

    return $res;
}
function updateModelParams($model_id,$model_name,$params_json)
{
    global $fcm_pdo;

    $sel = $fcm_pdo->prepare("UPDATE model SET model_options = ?,model_name=? WHERE mid=? and uid=?;");
    $p = [
        $params_json,
        $model_name,
        $model_id,
        $_SESSION["userId"]
    ];
    $res = $sel->execute($p);

    return $res;
}