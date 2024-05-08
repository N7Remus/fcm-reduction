<br>
<?php
include DIR . "components/visual/menu.php";
?>

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



<button type="button" class="btn btn-primary" onclick="setSigMode(0)">Node hozzáadás és mozgatás</button>
<button type="button" class="btn btn-secondary" onclick="setSigMode(1)">Reláció felvitel</button>
<!-- <button type="button" class="btn btn-success" onclick="setSigMode(2)">Érték szerkesztése</button> -->


<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
    Launch demo modal
</button>
 -->
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="edge-custId" value="">

            <div class="modal-body">
                <div class="mb-3">
                    <label for="" class="form-label">Edge value</label>
                    <input type="number" class="form-control" id="edgevalue">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sigUpdateEdge();">Save changes</button>
            </div>
        </div>
    </div>
</div>


<table class='table table-striped table-bordered' id="fcmtable">

</table>
<div class="sigma" id="sigma-container"></div>
<script>
    var sigmode = 0;
    var sig_start_node = null;

    function setSigMode(val) {

        // 0 default #
        // 1 noderelation add
        // 3 edit values
        sigmode = val;
        graph.nodes().forEach((node, i) => {
            graph.setNodeAttribute(node, "highlighted", false);

        });
    }


    var graph;
    var init_state_keys;
    var cm;
    var fcm2;


    <?php
    include DIR."/components/visual/fcm.js";
    ?>

    function showNodeEditModal(edge) {
        console.log("showNodeEditModal()");
        // set edge into hidden value
        $('#editModal').modal('show');
        $('#edge-custId').val(edge);
        $('#edgevalue').val(graph.getEdgeAttributes(edge).label);
        // console.log();




    }

    function getFcm(){
        fcm2.getFCM();
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

    /*   function addConncept(name, value=0) {
          const addConnceptArrayElements = (element, index  ) => {
              connection_matrix[index].push(value)
          };
          connection_matrix.forEach(addConnceptArrayElements);
      } */
    let draggedNode = null;
    let isDragging = false;
    fcm2 = new FCM({}, []);

    function sigUpdateEdge() {
        fcm2.updateEdge();
    }
    $(document).ready(function() {

        var model_init_state = {};
        cm = [];
        var nodeCount = 0;
        const container = document.getElementById("sigma-container");

        graph = new graphology.Graph({
            multi: true
        });


        var renderer = new Sigma.Sigma(graph, container, {
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
        renderer.container.addEventListener(
            'oncontextmenu',
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

            let label = "C" + (nodeCount + 1);
            fcm2.addConncept(label, 0, coordForGraph);
            nodeCount++;



        });

        renderer.on("downNode", (e) => {
            isDragging = true;
            draggedNode = e.node;
            graph.setNodeAttribute(draggedNode, "highlighted", true);
        });

        // On mouse move, if the drag mode is enabled, we change the position of the draggedNode
        renderer.getMouseCaptor().on("mousemovebody", (e) => {
            if (sigmode == 0) {
                if (!isDragging || !draggedNode) return;

                // Get new position of node
                const pos = renderer.viewportToGraph(e);

                graph.setNodeAttribute(draggedNode, "x", pos.x);
                graph.setNodeAttribute(draggedNode, "y", pos.y);

                // Prevent sigma to move camera:
                e.preventSigmaDefault();
                e.original.preventDefault();
                e.original.stopPropagation();
            }
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
        console.log(renderer.getMouseCaptor());
        renderer.getMouseCaptor().on("mousedown", (e) => {
            if (!renderer.getCustomBBox()) renderer.setCustomBBox(renderer.getBBox());

            /*                     if (sigmode == 0) {
                                    console.log(e);
                                    console.log(sigmode);
                                    e.preventSigmaDefault();

                                    switch (e.original.which) {
                                        case 1:
                                            console.log('Left Mouse button pressed.');

                                            break;
                                        case 2:
                                            console.log('Middle Mouse button pressed.');
                                            e.preventSigmaDefault();

                                            break;
                                        case 3:
                                            e.preventSigmaDefault();

                                            console.log('Right Mouse button pressed.');

                                            break;
                                        default:
                                            console.log('You have a strange Mouse!');
                                            e.preventSigmaDefault();


                                    }
                                }
             */
        });

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
            if (sigmode == 1) {
                if (sig_start_node) {
                    //reláció ellenörzése #
                    console.log('ads');
                    if (!fcm2.hasEdge(sig_start_node, e.node)) {
                        fcm2.addEdge(sig_start_node, e.node);
                    }

                }
            }
            fcm2.getFCM();
            fcm2.genFCMTable();
            e.preventSigmaDefault();
        });
        // remove node by left click (pop alert before doing it, DUH)
        renderer.on('rightClickNode', function(e) {



            e.event.preventSigmaDefault();
            e.preventSigmaDefault();

            console.log("rightClickNode", e);

            if (sig_start_node) {
                graph.setNodeAttribute(sig_start_node, "highlighted", false);
            }

            sig_start_node = e.node;

            console.log("Set node", sig_start_node);




        });
        // on scroll change color (and type)
        renderer.on('wheelNode', function(e) {
            console.log("Wheel", e);
        });
        // ON EDGE TODO
        // click on edge to edit it
        renderer.on('clickEdge', function(e) {
            console.log("clickEdge", e);
            showNodeEditModal(e.edge);
            e.preventSigmaDefault();

        });
        // right click to remove edge (pop alert before doing it, DUH)
        renderer.on('rightClickEdge', function(e) {
            console.log("rightClickEdge", e);
        });


    });
</script>