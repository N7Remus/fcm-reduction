<?php
//var_dump($_POST);
// Szimulációs adatok lekérdezése 
$W = []; // [[0,1][0,1]]
$STATE = []; // {"C1": 0.85}

if (!empty($_POST["cm"])){
    $W = json_decode($_POST["cm"],true);
}
if (!empty($_POST["i"])){
    $STATE = json_decode($_POST["i"],true);
}


?>

  <link rel="stylesheet" href="https://pyscript.net/releases/2024.1.1/core.css" />
  <!-- <script type="module" src="https://pyscript.net/releases/2024.1.1/core.js"></script> -->
  <script type="module" src="https://pyscript.net/latest/pyscript.js"></script>

<section class="pyscript">
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

init_state_json  = '''<?=json_encode($STATE)?>'''
conn_mx_json = '''<?=json_encode($W)?>'''

<?php 
//include "simulation_dev.py" ;
// include "simulation_dev_2.py" ;
include "simulation.py";
include "plot.py";
?>

    </py-script>
</section>