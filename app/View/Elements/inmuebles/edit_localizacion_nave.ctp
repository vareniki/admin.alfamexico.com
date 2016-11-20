<?php
echo $this->App->horizontalInput('Nave.bloque', 'Bloque / Puerta:');
echo $this->App->horizontalSelect('Nave.piso', 'Planta:', $plantasNave, array('size' => 7));
echo $this->App->horizontalSelect('Nave.puerta', 'Puerta:', $puertasNave, array('size' => 9));
echo $this->App->horizontalInput('Nave.urbanizacion', 'Urbanización:');
