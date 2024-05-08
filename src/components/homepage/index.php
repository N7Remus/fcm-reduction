<?php
if (!@isset($_SESSION) && !@$_SESSION['isAdmin'] ) {
    @ob_end_clean();
    header("Location: /login.php");
    exit();
} ?>
<h1>DEMO</h1>
A fuzzy kognitív térkép (Fuzzy Cognitive Map, FCM) egy olyan kognitív térkép, amelyen belül csúcsok (súly) elemei
(fogalmak, események, projekt erőforrások) közötti irányított élek (kapcsolatok) felhasználhatók a rendszer
összefüggéseinek és működésének vizsgálatára. <br>
Az csúcsokhoz kezdeti értékeket rendelünk, az éleket pedig egy kapcsolati mátrixban tartjuk nyilván.<br>
<hr>
<p>
    Fuzzy jellegzetességei, hogy minden csúcs rendelkezik egy kezdeti értékkel (többnyire 0 és 1 között), illetve minden
    él értéke többnyire -1 és 1 között található.<br>
    A modellek önmagukban egy rendszer összefüggéseit tudják reprezentálni, viszont ahhoz,hogy ezekről az
    összefüggésekről tudjunk következtetéseket levonni, számolni is kell.<br>
    A fuzzy kognitív térkép (FCM) szimulálása magába foglalja a fogalmak és az ok-okozati összefüggések rendszeren
    belüli ábrázolását, az aktiválások terjesztését a térképen, valamint a koncepció állapotok frissítését a hatások
    erőssége és iránya alapján.<br>
</p>
<hr>
Az alkalmazás célja a felhasználók segítése abbon hogy ezekkel a modellekkel tudjanak szimulációkat és redukciókat
végezni.
<hr>
A modellel végzett szimulációval, a modell vislkedéséről következtetéseket tudunk levonni:
<link rel="stylesheet" href="https://pyscript.net/releases/2024.1.1/core.css" />
<!-- <script type="module" src="https://pyscript.net/releases/2024.1.1/core.js"></script> -->
<script type="module" src="https://pyscript.net/latest/pyscript.js"></script>
<style>
    .py-terminal {
        text-align: center;
    }
</style>

<?php

$matix_w = 10;
$matix_h = 10;

$init_state = [
    "C1" => 0.85,
    "C2" => 0.7,
    "C3" => 0.5,
    "C4" => 0.2,
    "C5" => 0.0,
    "C6" => 0,
    "C7" => 0
]
;
$connection_matrix = [
    "C1" => [
        0,
        0,
        0.1,
        0,
        0,
        0,
        0
    ],
    "C2" => [
        0,
        0,
        0,
        0.1,
        0,
        0,
        0
    ],
    "C3" => [
        -0.2,
        0,
        0,
        0,
        0.2,
        0,
        0
    ],
    "C4" => [
        0,
        -0.2,
        0,
        0,
        0.2,
        0,
        0
    ],
    "C5" => [
        0,
        0,
        0,
        0,
        0,
        1,
        0
    ],
    "C6" => [
        0,
        0,
        0,
        0,
        0,
        0,
        1
    ],
    "C7" => [
        0,
        0,
        0,
        0,
        -0.4,
        -0.4,
        0
    ]
];


/*
highlight_string("<?php\n\$init_state =\n" . var_export($init_state, true) . ";\n?>");
highlight_string("<?php\n\$connection_matrix =\n" . var_export($connection_matrix, true) . ";\n?>");
*/

?>
<hr>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">C</th>
            <?php
            foreach ($init_state as $key => $value) {
                echo '<th scope="col">' . $key . '</th>';
            }
            ?>

            <th>

            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">
                Kezdeti érték
            </th>
            <?php
            foreach ($init_state as $key => $value) {
                ?>

                <td>
                    <?= $value ?>
                </td>


                <?php
            } ?>
            <td>
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                </svg>
            </td>
        </tr>
    </tbody>
</table>



<style>
    table {
        overflow: hidden;
    }

    td,
    th {
        padding: 10px;
        position: relative;
        outline: 0;
    }

    body:not(.nohover) tbody tr:hover {
        background-color: #ffa;
    }

    td:hover::after,
    thead th:not(:empty):hover::after,
    td:focus::after,
    thead th:not(:empty):focus::after {
        content: '';
        height: 10000px;
        left: 0;
        position: absolute;
        top: -5000px;
        width: 100%;
        z-index: -1;
    }

    td:hover::after,
    th:hover::after {
        background-color: #ffa;
    }

    td:focus::after,
    th:focus::after {
        background-color: lightblue;
    }

    /* Focus stuff for mobile */
    td:focus::before,
    tbody th:focus::before {
        background-color: lightblue;
        content: '';
        height: 100%;
        top: 0;
        left: -5000px;
        position: absolute;
        width: 10000px;
        z-index: -1;
    }
</style>
<table class="table table-striped table-bordered" id="view">
    <thead>
        <tr>
            <th scope="col">W</th>
            <?php
            foreach ($connection_matrix as $key => $value) {
                echo '<th scope="col">' . $key . '</th>';
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($connection_matrix as $key => $value) {
            ?>
            <tr>
                <th scope="row">
                    <?= $key ?>
                </th>
                <?php
                foreach ($connection_matrix[$key] as $k => $v) {
                    ?>
                    <td>
                        <?= $v ?>
                    </td>
                    <?php
                } ?>
            </tr>
            <?php
        } ?>
    </tbody>
</table>

<hr>
Vizualizáció


<script src="https://cdnjs.cloudflare.com/ajax/libs/graphology/0.25.4/graphology.umd.min.js"
    integrity="sha512-tjMBhL9fLMcqoccPOwpRiIQIOAyUh18lWUlUvE10zvG1UNMfxUC4qSERmUq+VF30iavIyqs/q6fSP2o475FAUw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/sigma.js"></script>
<style>
    #sigma-container {
        width: 100%;
        height: 600px;
        inset: 0;
        margin: 0;
        padding: 0;
        border-style: solid;
        border-color: coral;
    }
</style>
<div class="sigma" id="sigma-container"></div>

<script>
    const container = document.getElementById("sigma-container");
    const graph = new graphology.Graph();

    <?php
    $layout_cycle = 0;
    $layout_x = 0;
    $layout_y = 0;
    $layout_switch = count($init_state);
    foreach ($init_state as $key => $value) {



        ?>
        graph.addNode("<?= $key ?>", { x: <?= $layout_x ?>, y: <?= $layout_y ?>, size: 20, label: "<?= $key ?>", color: "blue" });
        <?php
        /*  $layout++;
         if ($layout % 2 == 0) {
           $layout_x += 1;
           $layout_y = 0;
         } else {
           $layout_y += 1;
         } */
    }
    ?>

    <?php
    foreach ($connection_matrix as $key => $value) {
        foreach ($value as $k => $v) {
            if ($v != 0) {
                $neighbor = array_slice($init_state, $k, 1, true);
                $neighbor_key = key($neighbor);
                $neighbor_value = $v;
                ?>
                graph.addEdge("<?= $key ?>", "<?= $neighbor_key ?>", { type: "arrow", label: "<?= $neighbor_value ?>", size: 10 });
                <?php
            }
        }

    }

    ?>



    /* graph.addEdge("C1", "C2", { type: "line", label: "works with", size: 5 });
    graph.addEdge("C2", "C3", { type: "arrow", label: "works with 5", size: 5 }); */


    graph.nodes().forEach((node, i) => {
        const angle = (i * 2 * Math.PI) / graph.order;
        graph.setNodeAttribute(node, "x", 100 * Math.cos(angle));
        graph.setNodeAttribute(node, "y", 100 * Math.sin(angle));
    });

    const renderer = new Sigma.Sigma(graph, container, {
        renderEdgeLabels: true,
    });

</script>


<br>

<section style="text-align: center;" class="pyscript">
    <div id="mpl"></div>

    <!-- <py-config>
        packages = ["matplotlib", "pandas","networkx","matplotlib","requests"]
    </py-config> -->
    <py-config>
        packages = ["numpy", "matplotlib"]

    </py-config>
    <py-script>
        from pyscript import display

        import json
        import matplotlib.pyplot as plt

        import numpy as np
        init_state_json = '''{
        "C1": 0.85,
        "C2": 0.7,
        "C3": 0.5,
        "C4": 0.2,
        "C5": 0.0,
        "C6": 0,
        "C7": 0
        }'''

        conn_mx_json = '''[
        [
        0,
        0,
        0.1,
        0,
        0,
        0,
        0
        ],
        [
        0,
        0,
        0,
        0.1,
        0,
        0,
        0
        ],
        [
        -0.2,
        0,
        0,
        0,
        0.2,
        0,
        0
        ],
        [
        0,
        -0.2,
        0,
        0,
        0.2,
        0,
        0
        ],
        [
        0,
        0,
        0,
        0,
        0,
        1,
        0
        ],
        [
        0,
        0,
        0,
        0,
        0,
        0,
        1
        ],
        [
        0,
        0,
        0,
        0,
        -0.4,
        -0.4,
        0
        ]
        ]'''

        <?php
        //include "simulation_dev.py" ;
// include "simulation_dev_2.py" ;
        include "simulation.py";
        include "plot.py";
        // include "reduction.py" ; 
        ?>

    </py-script>
</section>