<?php

$matix_w = 10;
$matix_h = 10;

$init_state = ['C1' => 0.2, 'C2' => 0.15, 'C3' => 0.1, 'C4' => 0.1];
$connection_matrix = [
  'C1' => [0, 1, 1, 0],
  'C2' => [0, 0, 0, 1],
  'C3' => [0, 0, 0, 1],
  'C4' => [0, 0, 0, 0]
];


/*
highlight_string("<?php\n\$init_state =\n" . var_export($init_state, true) . ";\n?>");
highlight_string("<?php\n\$connection_matrix =\n" . var_export($connection_matrix, true) . ";\n?>");
*/

?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1>Kezdeti állapot mátrix</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
      <a href="index.php?q=calendar" class="btn btn-secondary">Vissza</a>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropLiveGen">
        Alkalmazás
      </button>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropLiveChart">
        Szimulálás
      </button>
      <button type="button" class="btn btn-primary" onclick="window.print()">
        Redukció
      </button>

    </div>
  </div>
</div>


<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th scope="col">Megnevezés</th>
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
      <th scope="col">#</th>
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
    graph.addNode("<?= $key ?>", { x: <?= $layout_x ?>, y: <?= $layout_y ?>, size: 5, label: "<?= $key ?>", color: "blue" });
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


<hr>
interkaciós cuccok
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<hr>
Szimuláció(k)

<div class="bd-example">
  <nav>
    <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
      <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button"
        role="tab" aria-controls="nav-home" aria-selected="true">fcmbook demo</button>
      <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
        role="tab" aria-controls="nav-profile" aria-selected="false">Python FCM</button>
      <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button"
        role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      <?php include "fcmbookdemo/index.php"?>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
      



    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
      <p><strong>This is some placeholder content the Contact tab's associated content.</strong> Clicking another tab
        will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content
        visibility and styling. You can use it with tabs, pills, and any other <code>.nav</code>-powered navigation.</p>
    </div>
  </div>
</div>
<br>

