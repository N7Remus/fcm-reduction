<div id="fcm_container"></div>



<script>

  let connMtx = <?= json_encode(array_values($connection_matrix)) ?>;

  connMtx = connMtx.map((_, colIndex) => connMtx.map(row => row[colIndex]));

  var fmc_container = document.getElementById("fcm_container");

  let fcm = {
    lambda: 1, // steepness of threshold function
    tMax: 500, // max. number of simulation time steps to calculate

    // connection matrix
    connMtx,

    // initial state of the model
    t0: [
      [0.2],
      [0.15],
      [0.1],
      [0.1]
    ],

    // these member variables should be constants
    FP: 0, // fixed-poin attractor
    LC: 1, // limit cycle
    CB: 2, // chaotic behavior

    simulationResult: null, // result of the last simulation
    limitCycle: null, // state vectors of the limit cycle, if it is found

    // matrix multiplication
    mtxMul: function (left, right) {
      let i = left.length; // Rows of left matrix and result matrix
      let j = right[0].length; // Columns of right matrix and res. matrix
      let k = right.length; // Columns of left matrix and rows of right matrix
      let res = [];
      for (let r = 0; r < i; r++) {
        let resRow = [];
        for (let c = 0; c < j; c++) {
          let val = 0;
          for (let item = 0; item < k; item++) {
            val += left[r][item] * right[item][c];
          }
          resRow.push(val);
        }
        res.push(resRow);
      }
      return res;
    },

    // sigmoidal (logistic) threshold function
    threshold: function (vector) {
      for (let key in vector) {
        vector[key][0] = 1 / (1 + Math.exp(-this.lambda * vector[key][0]));
      }
      return vector;
    },

    // executes a simulation. Stops automatically before 'tMax' if 
    // the activation values of all concepts are considered stable.
    simulation: function () {
      this.simulationResult = [this.t0];
      let currentState = this.t0;
      let stable = false;
      this.stability.init(this.connMtx.length);
      for (let t = 0; !stable && t < this.tMax; t++) {
        currentState = this.threshold(this.mtxMul(this.connMtx, currentState));
        this.simulationResult.push(currentState);
        this.stability.refreshWindows(currentState);
        stable = this.stability.isStable();
      }
    },

    // return the last simulation's complete time series data
    getSimulationResult: function () {
      return this.simulationResult;
    },

    // returns the fixed-point attractor, if exists (last state vector of simulation data)
    getFP: function () {
      return this.simulationResult.slice(-1)[0];
    },

    // returns the limit cycle, if exists (call findLC() first to look for it)
    getLC: function () {
      return this.limitCycle;
    },

    // compares two arrays with a predetermined precision
    isEqual: function (a1, a2) {
      let n = Math.min(a1.length, a2.length);
      let equal = true;
      for (let i = 0; equal && i < n; i++) {
        if (Math.abs(a1[i] - a2[i]) > 1e-3) {
          equal = false;
        }
      }
      return equal;
    },

    // searches for a limit cycle and stores it in the 'limitCycle' member if it is found
    findLC: function () {
      let lastState = this.simulationResult.slice(-1)[0][0];
      let equalFound = false;
      let seqEnd;
      let lastPossible = Math.floor((this.simulationResult.length - 1) / 2);
      for (seqEnd = this.simulationResult.length - 2; seqEnd >= lastPossible && !equalFound;) {
        if (this.isEqual(lastState, this.simulationResult[seqEnd][0])) {
          equalFound = true;
        } else {
          seqEnd--;
        }
      }
      if (equalFound) {
        if (seqEnd == this.simulationResult.length - 2) { // the last states are equal; it is a FP
          this.limitCycle = null;
        } else {
          let cycle = true;
          let i, j;
          for (i = seqEnd - 1, j = this.simulationResult.length - 2; cycle && j > seqEnd; i--, j--) {
            cycle = isEqual(this.simulationResult[i][0], this.simulationResult[j][0]);
          }
          if (cycle) {
            this.limitCycle = this.simulationResult.slice(-(this.simulationResult.length - seqEnd - 1));
          } else { // even if there are two identical state vectors, other vectors between them are different
            this.limitCycle = null;
          }
        }
      } else { // all investigated state vectors are unique
        this.limitCycle = null;
      }
    },

    // tells simulation outcome, and returns a value according to FP, LC or CB
    getOutcome: function () {
      if (this.simulationResult.length < this.tMax + 1) {
        this.findLC();
        if (this.limitCycle != null) {
          return this.LC;
        } else {
          return this.FP;
        }
      } else {
        return this.CB;
      }
    },

    // detects the stability of concept states
    stability: {
      concepts: 0, // number of concepts
      windowSize: 5, // number of stored, consecutive concept states
      windows: null, // array of concept state objects; all concepts have a specific object to record it's state and speed-up the calculation of the std. dev. of the last states
      stabilityLimit: 1e-13, // upper limit on concept states' standard deviation

      // initializes the 'windows'
      init: function (concepts) {
        this.concepts = concepts;
        this.windows = [];
        for (let i = 0; i < this.concepts; i++) {
          this.windows.push({
            sumSqr: 0, // square of stored state values
            sum: 0,    // sum of stored state values
            states: [] // stored state values
          });
        }
      },

      // refreshes the content of 'windows'
      // stores the last concept state, updates the sum and sum of squares
      // erases the oldest state if the number of stored states exceeds 'windowSize'
      refreshWindows: function (mtx) {
        mtx.forEach((value, key) => {
          let window = this.windows[key];
          window.states.push(value[0]);
          window.sumSqr += value[0] * value[0];
          window.sum += value[0];
          if (window.states.length > this.windowSize) {
            let old = window.states.shift();
            window.sumSqr -= old * old;
            window.sum -= old;
          }
        });
      },

      // detect the stability of the fcm model
      // The model is considered stable if all of its concepts are stable
      // A concept is considered stable if the std. dev. of the last 'windowSize' states is not greater than 'stabilityLimit'
      isStable: function () {
        if (this.windows[0].states.length < this.windowSize) {
          return false;
        } else {
          let allStable = true;
          for (let i = 0; allStable && i < this.windows.length; i++) {
            let window = this.windows[i];
            let stdDev = (window.sumSqr - window.sum * window.sum / this.windowSize) / this.windowSize;
            if (stdDev > this.stabilityLimit) {
              allStable = false;
            }
          }
          return allStable;
        }
      }
    }
  };

  let gui = {
    // creates the DOM <table> node of a connection matrix
    createConnMtx: function (mtx, title) {
      let table = document.createElement("table");
      table.className += "table table-striped table-bordered";
      let caption = document.createElement("caption");
      caption.textContent = title;
      table.appendChild(caption);
      let row = document.createElement("tr");
      let data = document.createElement("th");
      row.appendChild(data);
      mtx[0].forEach(function (value, key) {
        data = document.createElement("th");
        data.textContent = "C" + (key + 1);
        row.appendChild(data);
      });
      table.appendChild(row);
      mtx.forEach((value, key) => {
        row = document.createElement("tr");
        data = document.createElement("th");
        data.textContent = "C" + (key + 1);
        row.appendChild(data);
        value.forEach((v, k) => {
          data = document.createElement("td");
          data.textContent = (+v).toFixed(6);
          row.appendChild(data);
        });
        table.appendChild(row);
      });
      return table;
    },

    // creates the DOM <table> node of an initial state
    createInitialState: function (mtx, title) {
      let table = document.createElement("table");
      table.className += "table table-striped table-bordered";

      let caption = document.createElement("caption");
      caption.textContent = title;
      table.appendChild(caption);
      let row1 = document.createElement("tr");
      let row2 = document.createElement("tr");
      mtx.forEach((value, key) => {
        let head = document.createElement("th");
        head.textContent = "C" + (key + 1);
        row1.appendChild(head);
        let data = document.createElement("td");
        data.textContent = (+value[0]).toFixed(6);
        row2.appendChild(data);
      });
      table.appendChild(row1);
      table.appendChild(row2);
      return table;
    },

    // creates the DOM <table> node of simulation results (activation vector for all calculated time steps)
    createSimulationResults: function (mtx, title) {
      let table = document.createElement("table");
      table.className += "table table-striped table-bordered";

      let caption = document.createElement("caption");
      caption.textContent = title;
      table.appendChild(caption);
      let row = document.createElement("tr");
      let data = document.createElement("th");
      data.textContent = "t";
      row.appendChild(data);
      mtx[0].forEach(function (value, key) {
        data = document.createElement("th");
        data.textContent = "C" + (key + 1);
        row.appendChild(data);
      });
      table.appendChild(row);
      mtx.forEach((vr, kr) => {
        row = document.createElement("tr");
        data = document.createElement("th");
        data.textContent = kr;
        row.appendChild(data);
        vr.forEach((vc, kc) => {
          data = document.createElement("td");
          data.textContent = (+vc).toFixed(6);
          row.appendChild(data);
        });
        table.appendChild(row);
      });
      return table;
    },

    // creates a DOM <p> node to display some text
    createParagraph: function (text) {
      let p = document.createElement("p");
      p.textContent = text;
      return p;
    },

    // creates a <details> node and includes something in it.
    // 'summary': short description
    // 'details': the content to include
    // 'open': the controller should be opened or closed
    createSummary: function (summary, details, open) {
      let d = document.createElement("details");
      if (open) {
        d.setAttribute("open", "open");
      }
      let s = document.createElement("summary");
      s.textContent = summary;
      d.appendChild(s);
      d.appendChild(details);
      return d;
    }

  };





  window.addEventListener("load", function () {
    fcm.simulation();
    let outcome = fcm.getOutcome();
    if (outcome == fcm.FP) {
      fmc_container.appendChild(
        gui.createParagraph("The simulation led to a fixed-point attractor.")
      );
      fmc_container.appendChild(
        gui.createInitialState(fcm.getFP(), "Fixed-point attractor")
      );
    } else if (outcome == fcm.LC) {
      fmc_container.appendChild(
        gui.createParagraph("The simulation led to a limit cycle.")
      );
      fmc_container.appendChild(
        gui.createSimulationResults(fcm.getLC(), "State vectors of the limit cycle")
      );
    } else {
      fmc_container.appendChild(
        gui.createParagraph("The model behaved chaotically.")
      );
    }
    fmc_container.appendChild(
      gui.createSummary("You can see all time steps of the simulation by clicking here if you're interested in",
        gui.createSimulationResults(fcm.getSimulationResult(), "Simulation results"))
    );
  }, false);




</script>