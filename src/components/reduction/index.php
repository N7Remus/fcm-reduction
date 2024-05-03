<?php
//var_dump($_POST);

// Szimulációs adatok lekérdezése 
$W = []; // [[0,1][0,1]]

if (!empty($_POST["cm"])){
    $W = json_decode($_POST["cm"], true);
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

W = json.loads('''<?=json_encode($W)?>''')

<?php 
include "reduction.py" ; 
?>

    </py-script>
</section>