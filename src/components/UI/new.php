<br>

<?php
include DIR . "ajax_mod/model.php";

if (!empty($_POST)) {

    $id = saveModel([
        "name" => $_POST["name"],
        "init_state" => "{}",
        "connection_matrix" => gzcompress(json_encode([]), 9)
    ]);
    if ($id) {
        $url = "/model/$id";
        // clear out the output buffer
        while (ob_get_status()) {
            ob_end_clean();
        }
        // no redirect
        header("Location: $url");
        exit();
    } else {
        /*
        Adatbázis hiba
        */
    }
}
?>

<form class="row g-3" action="" method="POST">

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Új modell</h5>
        </div>
        <div class="modal-body">

            <div class="mb-3">
                <label class="form-label" for="tmp_name">Model név:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= @$model["model_name"] ?>"
                    required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                <button type="submit" class="btn btn-primary">Mentés</button>
            </div>
        </div>
        </from>