<?php

// returns all models
function getUsers()
{
    global $fcm_pdo;

    $sql = "SELECT * FROM `user`";
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
function createUser($username,$password)
{
    global $fcm_pdo;

    $sql = "Insert into `user` (username, password)
    VALUES (?,?);";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [$username,safeEncrypt($password)];

    if ($sel->execute($p)) {
        return true;
    } else {
        return false;
    }

}
function changePasswordByUserId($userid,$password)
{
    global $fcm_pdo;

    $sql = "UPDATE user SET password = ? WHERE userid=?;";
    $sel = $fcm_pdo->prepare($sql);
    // params
    $p = [safeEncrypt($password),$userid];

    if ($sel->execute($p)) {
        return true;
    } else {
        return false;
    }

}