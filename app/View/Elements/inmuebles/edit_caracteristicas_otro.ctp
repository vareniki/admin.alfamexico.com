<?php echo $this->App->horizontalInput('Otro.area_total', 'Área total (m&sup2;):', array('min' => 0, 'maxlength' => 5, 'labelClass' => 'obligat'));
echo $this->App->horizontalInput('Otro.anio_construccion', 'A&ntilde;o de construcci&oacute;n (ej. 2015):', ['min' => 1000, 'max' => '2999', 'maxlength' => 4, 'datalist' => 'aniosList', 'type' => 'text', 'labelClass' => 'obligat']);
