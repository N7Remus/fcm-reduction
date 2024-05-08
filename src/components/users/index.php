<?php

# TODO
include DIR . "ajax_mod/user.php";

if (!empty($_POST)) {
    if (!empty($_POST["new_username"]) && !empty($_POST["new_password"])) {
        
        // echo "Create" ;
        createUser($_POST["new_username"],$_POST["new_password"]);


    } else if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
        
        // echo "Change" ;
        if (changePasswordByUserId($_POST["userid"],$_POST["password"])){
            echo "Sikeres jelszóváltás";
        }else{
            echo "Sikertelen jelszóváltás";
        }
    }else{
        echo "Érvénytelen paraméterek";
    }
    
}

$models = getUsers();

?>
<style>
    .dt-search {
        padding: 8px;
    }

    .dt-length {
        padding: 8px;
    }

    .dt-container {
        text-align: center;
    }
</style>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Felhasználók kezelése</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/" class="btn btn-secondary">Vissza</a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Új felhasználó hozzáadása
            </button>


        </div>
    </div>
</div>

<table class="table table-striped table-bordered" id="view">
    <thead>
        <tr>
            <th scope="col">Név</th>
            <th scope="col">#</th>

        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($models as $key => $value) {
            ?>
            <tr>
                <th scope="row">
                    <?= $value["username"] ?>
                </th>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" onclick="setPasswordModal(<?= $value['userid'] ?>)" class="btn btn-primary">Jelszóváltás</button>
                        <button type="button" class="btn btn-danger">Törlés</button>
                    </div>
                </td>
            </tr>
            <?php
        } ?>
    </tbody>
</table>
<script>
    function setPasswordModal(uid) {
        $("#exampleModal2").modal('show');
        $("#pwuid").val(uid);
        $("#pwpw").val("");
        $("#new_username").val("");
        $("#new_password").val("");



    }
</script>

<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
        <form class="row g-3" name="change" action="" method="POST">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2Label">Felhasználó jelszóváltás</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pwuid" name="userid" >
                    <div class="mb-3">
                        <label class="form-label">Új jelszó</label>
                        <input type="password" class="form-control" id="pwpw" name="password" >

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </div>
            </from>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="row g-3" action="" method="POST">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Felhasználó létrehozása</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Email input -->
                    <div class="mb-3">
                        <label class="form-label">Felhasználó név</label>
                        <input type="text" class="form-control" id="new_username" name="new_username" >

                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jelszó</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" >

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </div>
            </from>

    </div>
</div>

<script>
    $(document).ready(function () {
        new DataTable('#view', {
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Összes"]
            ],

            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/hu.json',
            },
            dom: 'Blfrtip',
            buttons: [
                // 'csv', 'excel'
            ],
            ordering: true,
            paging: true
        });
    });
</script>