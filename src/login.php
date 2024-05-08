<?php
require_once "include/config.inc.php";
require_once "include/mysql.php";
require_once "include/functions.php";


if (empty($_SESSION) && session_status() === PHP_SESSION_NONE) {
    session_start();
}


//global $fmc_pdo;
//var_dump($fmc_pdo);
///R00TMoos6eiv
//var_dump(safeEncrypt("R00TMoos6eiv"));
if (!empty($_POST)){
    if (!empty($_POST["password"]) && !empty($_POST["username"])){
        $userId = verifyAdministratorAccount($_POST["username"],safeEncrypt($_POST["password"]));
        if ($userId!=false){
            $_SESSION['isAdmin'] = true;
            $_SESSION['userId'] = $userId;
            //echo "success";
        }else{
            // hibás felhasználónév / jelszó
            echo "hibás felhasználónév / jelszó";
        }
    }
    else{
        // üres jelszó / felhasználónév
    }
}


if (!empty($_SESSION) && $_SESSION['isAdmin']){
    header('Location: index.php');
    exit();

}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= _("Bejelentkezés") ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>

</head>

<body class="text-center">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <main class="form-signin">
        <form method="post" action="login.php">
            <img class="mb-4" src="/duck.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal"><?= _("Bejelentkezés") ?></h1>
            <div class="form-floating">
                <input type="text" class="form-control" name="username"  id="" placeholder="Felhasználónév" required>
                <label >Felhasználónév</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control"  name="password" placeholder="<?= _("Jelszó") ?>" required>
                <label for=""><?= _("Jelszó") ?></label>
            </div>
            <!-- <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember" id="remember_me"> <?= _("Jegyezzen meg") ?>
                </label>
            </div> -->
            <input type="hidden" name="target" value="" disabled="">
            <input class="w-100 btn btn-lg btn-primary" type="submit" name="submit" value="<?= _("Bejelentkezés") ?>">
            <p class="mt-5 mb-3 text-muted">&copy; 2021-</p>
        </form>
    </main>

</body>

</html>