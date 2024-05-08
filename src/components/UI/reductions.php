<?php

# TODO
include  DIR . "ajax_mod/reduction.php";

$models = getReductions();

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

<table class="table table-striped table-bordered" id="view">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Név</th>

        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($models as $key => $value) {
        ?>
            <tr>
                <th scope="row">
                    <?= $value["mid"] ?>
                </th>
                <td>
                    <a href="/model/<?= $value["mid"] ?>"><?= $value["model_name"] ?></a>
                </td>
            </tr>

        <?php
        } ?>
    </tbody>
</table>


<script>
    $(document).ready(function() {
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