<?php

use Shuchkin\SimpleXLSX;

include  "SimpleXLSX.php";
var_dump("OK");
echo memory_get_usage() . "\n"; // 36640

ini_set("memory_limit", "-1");

if ($xlsx = SimpleXLSX::parse('components/out7.xlsx')) {
  $x = $xlsx->rows();
  echo memory_get_usage() . "\n"; // 36640

  foreach ($x[0] as $key => $value) {
    $init_state[$value] = 0;
  }
  echo memory_get_usage() . "\n"; // 36640

  // remove first empty cell
  array_shift($init_state);

  array_shift($x);
  //$connection_matrix = $x;

  $earray = array_fill(0, count($x[0]) - 1, 0);

  foreach ($x as $key => $value) {
    $conncept_name = $value[0];
    $connection_matrix[$conncept_name] = array_replace($earray, array_filter(array_slice($value, 1)));
    //print_r($connection_matrix);
    //die();
  }
  var_dump("OK2");
} else {
  echo SimpleXLSX::parseError();

  $init_state = ['C1' => 0.2, 'C2' => 0.15, 'C3' => 0.1, 'C4' => 0.1];
  $connection_matrix = [
    'C1' => [0, 1, 1, 0],
    'C2' => [0, 0, 0, 1],
    'C3' => [0, 0, 0, 1],
    'C4' => [0, 0, 0, 0]
  ];
}

$matix_w = 10;
$matix_h = 10;

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


<table class="table table-striped table-bordered" id="init">
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
      <th scope="row">Kezdeti érték</th>
      <?php
      if (count($init_state) < 10) {
        foreach ($init_state as $key => $value) {
      ?>
          <td><?= $value ?></td>
      <?php
        }
      } ?>
      <td>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
          <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
        </svg>
      </td>
    </tr>
  </tbody>
</table>

<hr>

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
      if (count($init_state) < 10) {
        foreach ($connection_matrix as $key => $value) {
          echo '<th scope="col">' . $key . '</th>';
        }
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    if (count($init_state) < 10) {
      foreach ($connection_matrix as $key => $value) {
    ?>
        <tr>
          <th scope="row"><?= $key ?></th>
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
      }
    } ?>
  </tbody>
</table>

<hr>
Vizualizáció <br>
Jobb klikk -> hozzáadás <br>
A node-ok mozgathatóak

<div id="connmx">
  <?php /* echo json_encode($connection_matrix) */  ?>;
</div>



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
  function showNodeEditModal() {
    console.log("showNodeEditModal()");
  }
  const randomHexColorCode = () => {
    let n = (Math.random() * 0xfffff * 1000000).toString(16);
    return '#' + n.slice(0, 6);
  };

  function uuid() {
    function s4() {
      return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
      s4() + '-' + s4() + s4() + s4();
  }

  const container = document.getElementById("sigma-container");
  const graph = new graphology.Graph();
  var nodeCount = <?= count($init_state) ?>;

  var init_state = <?= json_encode($init_state) ?>;
  var connection_matrix = <?= json_encode($connection_matrix) ?>;

  function addConncept(name, value) {
    const addConnceptArrayElements = (element, index /*, array */ ) => {
      connection_matrix[index].push(0)
    };
    connection_matrix.forEach(addConnceptArrayElements);
  }


  function addNode(k) {
    graph.addNode(k, {
      x: 0,
      y: 0,
      size: 10,
      label: k,
      color: randomHexColorCode()
    });
  }

  function addEdge(k, value) {
    if (typeof value === 'string' || value instanceof String) {
      v = value;
      graph.addEdge(k, v, {
        type: "arrow",
        label: v,
        size: 4
      });
    } else {
      value.forEach((v) => {
        graph.addEdge(k, v, {
          type: "arrow",
          label: v,
          color: graph.getNodeAttributes(k)["color"],
          size: 4
        });
      })
    }

  }

  $(document).ready(function() {
    <?php
    $ncount = [];
    $a = [];
    foreach ($connection_matrix as $key => $value) {
      foreach ($value as $k => $v) {
        if ($v != 0) {
          $neighbor = array_slice($init_state, $k, 1, true);
          $neighbor_key = key($neighbor);
          $neighbor_value = $v;
          $ncount[$neighbor_key] += 1;
          if ($v != 0 && $key != "") {
            $a["$key"][] = $neighbor_key;
          }
        }
      }
    }
    $data = array_filter($ncount, fn ($var) => $var > 1);
    ?>
    <?= json_encode(array_keys($init_state)) ?>.forEach(addNode);

    Object.entries(<?= json_encode($a) ?>).forEach(([key, value]) => addEdge(key, value));

    /* graph.addEdge("C1", "C2", { type: "line", label: "works with", size: 5 });
    graph.addEdge("C2", "C3", { type: "arrow", label: "works with 5", size: 5 }); */
    let draggedNode = null;
    let isDragging = false;
    // console.log(graph.edges());

    graph.nodes().forEach((node, i) => {

      let szor = 100;
      if (node.startsWith('U')) {
        szor = 10;
      } else if (node.startsWith('V')) {
        szor = 200;
      } else if (node.startsWith('K')) {
        szor = 500;

      }



      const angle = (i * 2 * Math.PI) / graph.order;
      graph.setNodeAttribute(node, "x", Math.cos(angle) * szor);
      graph.setNodeAttribute(node, "y", Math.sin(angle) * szor);
    });

    const renderer = new Sigma.Sigma(graph, container, {
      renderEdgeLabels: true,
      enableEdgeClickEvents: true,
      enableEdgeWheelEvents: true,
      enableEdgeHoverEvents: "debounce",

    });

    renderer.on("doubleClickStage", (event) => event.preventSigmaDefault());


    function contextmenuListener(event) {
      event.preventDefault();
    };
    renderer.container.addEventListener(
      'contextmenu',
      contextmenuListener
    );

    // When clicking on the stage, we add a new node and connect it to the closest node
    renderer.on("rightClickStage", (event) => {


      // Sigma (ie. graph) and screen (viewport) coordinates are not the same.
      // So we need to translate the screen x & y coordinates to the graph one by calling the sigma helper `viewportToGraph`
      event.event.preventSigmaDefault();
      const coordForGraph = renderer.viewportToGraph({
        x: event.event.x,
        y: event.event.y,

      });

      // We create a new node
      const node = {
        ...coordForGraph,
        label: "C" + nodeCount,
        size: 10,
        color: randomHexColorCode(),
      };
      nodeCount++;
      // Searching the two closest nodes to auto-create an edge to it
      const closestNodes = graph
        .nodes()
        .map((nodeId) => {
          const attrs = graph.getNodeAttributes(nodeId);
          const distance = Math.pow(node.x - attrs.x, 2) + Math.pow(node.y - attrs.y, 2);
          return {
            nodeId,
            distance
          };
        })
        .sort((a, b) => a.distance - b.distance)
        .slice(0, 2);

      // We register the new node into graphology instance
      const id = uuid();
      graph.addNode(id, node);

      // We create the edges
      closestNodes.forEach((e) => graph.addEdge(id, e.nodeId, {
        type: "arrow",
        label: "neighbor_value",
        size: 4
      }));
    });

    renderer.on("downNode", (e) => {
      isDragging = true;
      draggedNode = e.node;
      graph.setNodeAttribute(draggedNode, "highlighted", true);
    });

    // On mouse move, if the drag mode is enabled, we change the position of the draggedNode
    renderer.getMouseCaptor().on("mousemovebody", (e) => {
      if (!isDragging || !draggedNode) return;

      // Get new position of node
      const pos = renderer.viewportToGraph(e);

      graph.setNodeAttribute(draggedNode, "x", pos.x);
      graph.setNodeAttribute(draggedNode, "y", pos.y);

      // Prevent sigma to move camera:
      e.preventSigmaDefault();
      e.original.preventDefault();
      e.original.stopPropagation();
    });

    // On mouse up, we reset the autoscale and the dragging mode
    renderer.getMouseCaptor().on("mouseup", () => {
      if (draggedNode) {
        graph.removeNodeAttribute(draggedNode, "highlighted");
      }
      isDragging = false;
      draggedNode = null;
    });

    // Disable the autoscale at the first down interaction
    renderer.getMouseCaptor().on("mousedown", () => {
      if (!renderer.getCustomBBox()) renderer.setCustomBBox(renderer.getBBox());
    });

    <?php

    /*     foreach ($ncount as $key => $value) {
    ?>
      graph.setNodeAttribute('<?= $key ?>', "x", graph.getNodeAttributes('<?= $key ?>')['x'] * <?=$value?>*10);
      graph.setNodeAttribute('<?= $key ?>', "y", graph.getNodeAttributes('<?= $key ?>')['y'] * <?=$value?>*10);
    <?php
    } */
    ?>


    // Bind the events:
    /* const nodeEvents = [
      "enterNode",
      "leaveNode",
      "downNode",
      "clickNode",
      "rightClickNode",
      "doubleClickNode",
      "wheelNode",
    ] as
    
    const edgeEvents = ["downEdge", "clickEdge", "rightClickEdge", "doubleClickEdge", "wheelEdge"] as
    
    const stageEvents = ["downStage", "clickStage", "doubleClickStage", "wheelStage"] 
    */



    // add node by right click
    // ON NODE
    renderer.on('doubleClickNode', function(e) {
      console.log("Double", e);
      //if (fcm.mode==edit)
      if (true) {
        showNodeEditModal();
      }

      e.preventSigmaDefault();

    });
    // connect by clicking on node
    renderer.on('clickNode', function(e) {
      //if (fcm.mode==edit)
      // link edge
      console.log("clickNode", e);
      //Change color

      e.preventSigmaDefault();
    });
    // remove node by left click (pop alert before doing it, DUH)
    renderer.on('rightClickNode', function(e) {
      console.log("rightClickNode", e);
    });
    // on scroll change color (and type)
    renderer.on('wheelNode', function(e) {
      console.log("Wheel", e);
    });
    // ON EDGE TODO
    // click on edge to edit it
    renderer.on('clickEdge', function(e) {
      console.log("clickEdge", e);
      e.preventSigmaDefault();

    });
    // right click to remove edge (pop alert before doing it, DUH)
    renderer.on('rightClickEdge', function(e) {
      console.log("rightClickEdge", e);
    });


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
      <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">fcmbook demo</button>
      <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Python FCM</button>
      <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      <?php include "fcmbookdemo.php" ?>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

      <?php /* include "python2.php"  */ ?>


    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
      <p><strong>This is some placeholder content the Contact tab's associated content.</strong> Clicking another tab
        will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content
        visibility and styling. You can use it with tabs, pills, and any other <code>.nav</code>-powered navigation.</p>
    </div>
  </div>
</div>
<br>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<!-- <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script> -->

<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<!--table-->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css"> -->
<script>
  $(document).ready(function() {

    /*     new DataTable('#view', {
          language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/hu.json',
          },
          dom: 'Bfrtip',
          buttons: [
            'csv', 'excel'
          ],
          ordering: false,
          paging: false
        }); */
  });
</script>

<style>
  .dt-search {
    padding: 8px;
  }

  .dt-container {
    text-align: center;
  }
</style>