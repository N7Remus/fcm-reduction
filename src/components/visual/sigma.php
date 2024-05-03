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
    var oReq = new XMLHttpRequest();
    oReq.open("GET", "https://fcm.remai.hu/components/ajax.php", true);
    oReq.responseType = "arraybuffer";

    oReq.onload = function(oEvent) {
        var arrayBuffer = oReq.response;

        // if you want to access the bytes:
        var byteArray = new Uint8Array(arrayBuffer);
        // ...
        var originalInput = pako.ungzip(byteArray, {
            to: 'string'
        });

        var obj = jQuery.parseJSON(originalInput);
        console.log(obj.memory);
    };

    oReq.send();




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
    const graph = new graphology.Graph({
        multi: true
    });
    var nodeCount = <?= count($init_state) ?>;

    var init_state = <?= json_encode($init_state) ?>;
    var connection_matrix = <?= json_encode($connection_matrix) ?>;

    class FCM {
        constructor(init_state, connection_matrix) {
            this.init_state = init_state;
            this.connection_matrix = connection_matrix;
        }

        getFCM() {
            console.log(this.init_state);
            console.log(this.connection_matrix);
        }

        addConncept(name, value = 0) {
            this.init_state[name] = value;
            for (const [key, value] of Object.entries(this.connection_matrix)) {
                this.connection_matrix[key].push(0);
            }
            console.log(this.init_state.len);
            this.connection_matrix[name] = new Array(Object.keys(this.init_state).length).fill(0);
        }
        addEdge(from, to, value) {

        }
    }



    var fcm2 = new FCM(init_state, connection_matrix);




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
            fcm2.addConncept(label);
            const node = {
                ...coordForGraph,
                label: label,
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