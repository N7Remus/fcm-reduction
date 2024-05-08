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


<button type="button" class="btn btn-primary" onclick="setSigMode(0)">Mozgatás</button>
<button type="button" class="btn btn-secondary" onclick="setSigMode(1)">Reláció felvitel fogalmak között</button>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Paraméterek megadása és mentés
</button>
</br>
<br>

<p>A csúcsokat egér jobb gombjával való kattintással lehet hozzáadni, a mozgatás módban(alapértelmezett).</p>
<p>A csúcsokat mozgatni rájuk kattintva lehet, az egérgomb felengedésével a mozgatás az aktuális kordinátnál marad.</p>
<p>Az éleket felvitel módban lehet rögzíteni. Egy csúcsra az egér jobb gombjával kattintva a reláció kiinduló pontja jelölhető ki, majd a ball egér gombbal egy másik csúcsra kattintva választható ki a célja.</p>
<p>A csúcsok érétke duplakattintással, az élek értékét (az élre) sima kattintással lehet megváltoztatni. </p>
<p>A modell elkészítése után paraméterezhető és menthető, vagy az aktuális állapottal futtatható rajta szimuláció és redukció.</p>



<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="row g-3" id="param_form" action="" method="POST">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Paraméterek megadása</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label" for="tmp_name">Model név:</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="<?= @$model["model_name"] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Maximális szimulációs iterációk száma
                            (10)</label>
                        <input type="number" class="form-control" id="itter" name="itter" value="<?= @$model_params["itter"] ?>">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Lambda (1)</label>
                        <input type="number" class="form-control" id="lambda" name="lambda" value="<?= @$model_params["lambda"] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">treshold - (0.0001)</label>
                        <input type="number" class="form-control" id="treshold" name="treshold" value="<?= @$model_params["treshold"] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="disabledSelect" class="form-label">Aktivációs függvény (Módosított
                            Kosko)</label>
                        <select id="disabledSelect" class="form-select" id="" name="interf" value="<?= @$model_params["interf"] ?>">
                            <option>Módosított Kosko</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="disabledSelect" class="form-label">Transzfer metódus (Szigmafüggvény)</label>
                        <select id="disabledSelect" class="form-select" id="" name="transzfer"
                            value="<?= @$model_params["transzfer"] ?>">
                            <option>Szigmafüggvény</option>
                        </select>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Redukciós paraméter - E (0.0001)</label>
                        <input type="number" class="form-control" id="E" name="E" value="<?= @$model_params["E"] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                        <button type="button" onclick="submitParamForm()" class="btn btn-primary">Mentés</button>
                    </div>
                </div>
                </from>

            </div>
    </div>
</div>


<div class="modal fade" id="editNModal" tabindex="-1" aria-labelledby="editNModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNModalLabel">Csúcs szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="node-custId" value="">

            <div class="modal-body">
                <div class="mb-3">
                    <label for="" class="form-label">Csúcs kezdeti értéke</label>
                    <input type="number" class="form-control" id="nodevalue">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <button type="button" class="btn btn-primary" onclick="sigUpdateNode();">Mentés</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Él szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="edge-custId" value="">

            <div class="modal-body">
                <div class="mb-3">
                    <label for="" class="form-label">Él értéke</label>
                    <input type="number" class="form-control" id="edgevalue">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <button type="button" class="btn btn-primary" onclick="sigUpdateEdge();">Mentés</button>
            </div>
        </div>
    </div>
</div>
<!-- <button type="button" class="btn btn-success" onclick="setSigMode(2)">Érték szerkesztése</button> -->
<table class='table table-striped table-bordered' id="fcmtable">

</table>

<div class="sigma" id="sigma-container"></div>
<script>

    function submitParamForm(){
        
        fcm2.saveDataToAPI();
        
        $("#param_form").submit();

        
    }

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
    include "fcm.js";
    ?>

    function showEditModal(edge) {
        console.log("showEditModal()");
        $('#editModal').modal('show');
        $('#edge-custId').val(edge);
        $('#edgevalue').val(graph.getEdgeAttributes(edge).label);
    }
    function showNodeEditModal(node) {
        console.log("showNodeEditModal()");
        $('#editNModal').modal('show');
        $('#node-custId').val(node);
        $('#nodevalue').val(fcm2.getNodeVal(node));
    }
    const randomHexColorCode = () => {
        let n = (Math.random() * 0xfffff * 1000000).toString(16);
        return '#' + n.slice(0, 6);
    };
    function sigUpdateEdge() {
        fcm2.updateEdge();
        fcm2.genFCMTable();
    }

    function sigUpdateNode() {
        fcm2.updateNode();
        fcm2.genFCMTable();
    }

    function uuid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    }

    let draggedNode = null;
    let isDragging = false;


    $(document).ready(function () {

        function base64ToArrayBuffer(base64) {
            var binaryString = atob(base64);
            var bytes = new Uint8Array(binaryString.length);
            for (var i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes.buffer;
        }
        var jqxhr = $.post("<?= $fcm_sigma_ajax_url ?>", {
            model_id: "<?= $model_id ?>"
        }, function () {
            //alert("success");
        })
            .done(function (data) {
                var dat = jQuery.parseJSON(data).data

                var model_init_state = jQuery.parseJSON(dat.model_init_state);

                // Custom sorting function for alphanumeric keys
                function customSort(a, b) {
                    // Extracting numeric parts of the keys
                    const numA = parseInt(a.slice(1));
                    const numB = parseInt(b.slice(1));

                    // Compare the numeric parts
                    if (numA < numB) return -1;
                    if (numA > numB) return 1;
                    // If numeric parts are equal, compare the whole keys
                    return a.localeCompare(b);
                }

                // Convert object to array of key-value pairs, sort, and then convert back to object
                const sortedObject = Object.fromEntries(
                    Object.entries(model_init_state)
                        .sort((a, b) => customSort(a[0], b[0]))
                );

                // base64 decode
                var cm_compressed = base64ToArrayBuffer(dat.model_conn_matrix);

                cm = jQuery.parseJSON(pako.ungzip(cm_compressed, {
                    to: 'string'
                }));

                var nodeCount = Object.keys(model_init_state).length;

                console.log("node", model_init_state);


                if (nodeCount < 50000) {
                    console.log("FCMsimulate");
                    //fcmSimulate(Object.values(model_init_state),cm);
                }

                const container = document.getElementById("sigma-container");

                graph = new graphology.Graph({
                    multi: true
                });

                fcm2 = new FCM(sortedObject, cm);

                graph.nodes().forEach((node, i) => {
                    let szor = 100;
                    if (node.startsWith('A')) {
                        szor = 10;
                    } else if (node.startsWith('B')) {
                        szor = 200;
                    } else if (node.startsWith('C')) {
                        szor = 500;
                    }

                    const angle = (i * 2 * Math.PI) / graph.order;
                    graph.setNodeAttribute(node, "x", Math.cos(angle) * szor);
                    graph.setNodeAttribute(node, "y", Math.sin(angle) * szor);
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
                showNodeEditModal(e.node);
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
            showEditModal(e.edge);
            e.preventSigmaDefault();

        });
        // right click to remove edge (pop alert before doing it, DUH)
        renderer.on('rightClickEdge', function(e) {
            console.log("rightClickEdge", e);
        });


            },
                'json')
            .fail(function () {
                alert("error");
            })

    });
</script>