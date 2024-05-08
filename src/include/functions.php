<?php

// var_dump(getcwd());

require_once "config.inc.php";
require_once "mysql.php";

function safeEncrypt($string_to_encrypt)
{
    $encrypted_string = openssl_encrypt($string_to_encrypt, "AES-128-ECB", ENC_DEC_PASS);
    return urlencode(base64_encode($encrypted_string));
}

function verifyAdministratorAccount($username,$password){
    global $fcm_pdo;
    $sel = $fcm_pdo->prepare("SELECT userid from user WHERE username = ? AND password = ? ");
    $sel->execute([$username,$password]);
    if ($sel->rowCount() > 0) {
      // got result 
      return $sel->fetch(PDO::FETCH_ASSOC)["userid"];
    }
    return false;
}

function _($string){
    return $string;
}

function startsWith($string, $startString)
{
  $len = strlen($startString);
  return (substr($string, 0, $len) === $startString);
}
