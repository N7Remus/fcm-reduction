<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1>Kezdeti állapot mátrix</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
      <a href="/models" class="btn btn-secondary">Vissza</a>

      <button type="button" class="btn btn-primary" onclick="runMySimulation()">Szimulálás</button>
      <button type="button" class="btn btn-primary" onclick="runMyReduction()">Redukció</button>

      <!-- <button type="button" class="btn btn-primary" onclick="runServerSimulation()">Szerver oldali szimulálás</button>
      <button type="button" class="btn btn-primary" onclick="runServerReduction()">Szerver oldali redukció</button> -->
    </div>
  </div>
</div>

<div id="res">
</div>

<script>
  function runMyReduction() {
    let e = parseFloat($("#E").val());
    $.post("/reduction", {
      cm: JSON.stringify(fcm2.getFCMConnectionMatrix()),
      E: e
    }, function (data) {

      const winUrl = URL.createObjectURL(
        new Blob([data], {
          type: "text/html"
        })
      );

      const win = window.open(
        winUrl,
        "win",
        `width=800,height=400,screenX=200,screenY=200`
      );
    });
  }

  function runMySimulation() {
    $.post("/simulation", {
      i: JSON.stringify(fcm2.getFCMState()),
      cm: JSON.stringify(fcm2.getFCMConnectionMatrix()),
      itter:$("#itter").val(),
      lambda:$("#lambda").val(),
      treshold:$("#treshold").val()

    }, function (data) {

      const winUrl = URL.createObjectURL(
        new Blob([data], {
          type: "text/html"
        })
      );

      const win = window.open(
        winUrl,
        "win",
        `width=800,height=400,screenX=200,screenY=200`
      );
    });
  }
</script>