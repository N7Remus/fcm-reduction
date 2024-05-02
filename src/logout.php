<?php
session_start();

session_destroy();
$url = "login.php";
// clear out the output buffer
while (ob_get_status()) {
    ob_end_clean();
}
header("Location: $url");
exit();
