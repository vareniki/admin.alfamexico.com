<?php
echo $this->App->horizontalSelect('Inmueble.calidad_precio', 'Calidad / precio:', array('' => '') + $calidadPrecio, array('size' => '3'));

echo $this->App->horizontalInput('Inmueble.descripcion', 'Descripci&oacute;n completa:', array('rows' => 6,
	'placeholder' => 'recuerda utilizar palabras clave como: zona, colegios, guarderías, metro, centros de salud, servicios comunes, autobuses,...', 'labelClass' => 'obligat'));
echo $this->App->horizontalInput('Inmueble.descripcion_abreviada', 'Descripci&oacute;n abreviada:<br><span>(max. 250 caracteres)</span>', array('rows' => 4, 'maxlength' => 250));
echo $this->App->horizontalTextarea('Inmueble.video', 'Vídeos:', array('rows' => 3, 'placeholder' => 'pegue la URL o URLs separadas por salto de línea'));
