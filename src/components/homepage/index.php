DEMO
A fuzzy kognitív térkép (Fuzzy Cognitive Map, FCM) egy olyan kognitív térkép, amelyen belül csúcsok (súly) elemei (fogalmak, események, projekt erőforrások) közötti irányított élek (kapcsolatok) felhasználhatók a rendszer összefüggéseinek és működésének vizsgálatára. <br>
Az csúcsokhoz kezdeti értékeket rendelünk, az éleket pedig egy kapcsolati mátrixban tartjuk nyilván.<br>
1. ábra Példa FCM <br>
A csúcsokat (Concept) a leggyakrabban C-vel jelöljük.<br>

A kapcsolati mátrixokat pedig W-vel (Weight Matrix), melynek nagyságát a C halmaz adja meg, ahol n a csúcsok száma, a kapcsolati mátrix pedig egy n*n méretű mátrix. A gráfok élei kapcsolati súlyokkal ellátottak, Ci és Cj súlyok közötti kapcsolati súlyt az alábbiak szerint határozzuk meg:<br>
Fuzzy jellegzetességei, hogy minden csúcs rendelkezik egy kezdeti értékkel (többnyire 0 és 1 között), illetve minden él értéke többnyire -1 és 1 között található.<br>
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
init_state_json  = '''{
    "C1": 0.85,
    "C2": 0.7,
    "C3": 0.5,
    "C4": 0.2,
    "C5": 0.0,
    "C6": 0,
    "C7": 0
}'''

conn_mx_json = '''[
    [
        0,
        0,
        0.1,
        0,
        0,
        0,
        0
    ],
    [
        0,
        0,
        0,
        0.1,
        0,
        0,
        0
    ],
    [
        -0.2,
        0,
        0,
        0,
        0.2,
        0,
        0
    ],
    [
        0,
        -0.2,
        0,
        0,
        0.2,
        0,
        0
    ],
    [
        0,
        0,
        0,
        0,
        0,
        1,
        0
    ],
    [
        0,
        0,
        0,
        0,
        0,
        0,
        1
    ],
    [
        0,
        0,
        0,
        0,
        -0.4,
        -0.4,
        0
    ]
]'''

<?php 
//include "simulation_dev.py" ;
// include "simulation_dev_2.py" ;
include "simulation.py";
include "plot.py";
// include "reduction.py" ; 
?>

    </py-script>
</section>