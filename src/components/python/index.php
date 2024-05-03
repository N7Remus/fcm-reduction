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
include "reduction.py" ; 
?>

    </py-script>
</section>