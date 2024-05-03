<?php
require_once "config.inc.php";

try {
  $fcm_pdo = new PDO("mysql:host=".DBSERVER.";dbname=".DBNAME, DBUSER, DBPASS);
  // set the PDO error mode to exception
  $fcm_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>