class FCM {
    constructor(init_state, connection_matrix) {
        this.init_state = init_state;
        this.connection_matrix = connection_matrix;
        this.init_state_keys = Object.keys(init_state);

        // add nodes to sigma 
        this.init_state_keys.forEach(this.addNode);
        // add edges to sigma
        Object.entries(this.connection_matrix).forEach(([key, value]) => this.addEdge(this.init_state_keys[key], value));

        console.log("FCM-started");
    }

    getFCM() {
        console.log(this.init_state);
        console.log(this.connection_matrix);
    }
    getFCMConnectionMatrix() {
        console.log(this.connection_matrix);
        return this.connection_matrix;
    }
    getFCMState() {
        console.log(this.init_state);
        return this.init_state;
    }



    saveDataToAPI() {
        console.log(this.init_state);
        console.log(this.connection_matrix);
        var jqxhr = $.post(window.location.href, {
            i: JSON.stringify(this.init_state),
            w: JSON.stringify(this.connection_matrix)
        }, function () {
            //alert("success");
        })

    }
    saveDataToAPIWithCoordinates() {

    }


    genFCMTable() {

        let str = "<tr><td>kezdeti értékek</td>";

        for (const [key, value] of Object.entries(this.init_state)) {
            str += "<td>" + key + "=" + value + "</td>"
        }
        str += "</tr>";
        str += "<tr><td>#</td>";
        Object.keys(this.init_state).forEach(key => {
            str += "<td>" + key + "</td>"
        });
        str += "</tr>";

        // console.log("O",Object.keys(this.init_state)[index]);
        let i = 0;
        this.connection_matrix.forEach(element => {
            str += "<tr><td>" + Object.keys(this.init_state)[i] + "</td>";
            i += 1;


            element.forEach(e => {
                str += "<td>";
                str += e;
                str += "</td>";
            })

            str += "<tr>";
        });


        $('#fcmtable').html(str);

    }



    addConncept(name, value = 0.5, coordForGraph = null) {
        this.init_state[name] = value;
        for (const [key, value] of Object.entries(this.connection_matrix)) {
            this.connection_matrix[key].push(0);
        }
        this.connection_matrix.push(new Array(Object.keys(this.init_state).length).fill(0));
        this.addNode(name, coordForGraph);
    }

    addNode(name, coordForGraph) {
        if (coordForGraph !== null) {
            graph.addNode(name, {
                ...coordForGraph,
                size: 10,
                label: name,
                color: randomHexColorCode()
            });
        } else {
            graph.addNode(name, {
                x: 0,
                y: 0,
                size: 10,
                label: name,
                color: randomHexColorCode()
            });
        }
    }
    getNodeVal(key) {
        return this.init_state[key];
    }

    getEdge(from) {
        return graph.getNodeAttributes(from);
    }
    updateEdge() {
        //            this.runSimulation();
        let e = $('#edge-custId').val().split("->");
        let from = e[0];
        let to = e[1];
        // set internal value
        this.connection_matrix[Object.keys(this.init_state).indexOf(from)][Object.keys(this.init_state).indexOf(to)] = parseFloat($('#edgevalue').val());
        // set edge label
        graph.setEdgeAttribute($('#edge-custId').val(), 'label', ($('#edgevalue').val()));
    }
    updateNode() {
        let e = $('#nodevalue').val();
        // set internal value
        this.init_state[$('#node-custId').val()] = e;

    }

    hasEdge(from, to) {
        return graph.hasEdge(from, to);
    }


    addEdge(from, to, value = 1) {
        //console.log("initstat", [from]);
        //console.log("initstat", to);

        this.connection_matrix[Object.keys(this.init_state).indexOf(from)][Object.keys(this.init_state).indexOf(to)] = value;
        if (typeof to === 'string' || to instanceof String) {

            graph.addEdgeWithKey(from + "->" + to, from, to, {
                type: "arrow",
                label: value,
                color: graph.getNodeAttributes(from)["color"],
                size: 4
            });
        } else {
            let t = this.init_state_keys;
            to.forEach(function (v, i) {
                if (v != 0) {
                    graph.addEdgeWithKey(from + "->" + t[i], from, t[i], {
                        type: "arrow",
                        label: value,
                        color: graph.getNodeAttributes(from)["color"],
                        size: 4
                    });
                }
            });

        }

    }
}