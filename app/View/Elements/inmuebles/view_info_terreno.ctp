<ul>
  <?php $this->Model->printIfExists($info, 'area_total', array('label' => 'Área total', 'model' => 'Terreno', 'format' => 'area')); ?>
</ul>
<ul>
  <?php
  $this->Model->printIfExists($info, 'numero_parcela', array('label' => 'Número de parcela', 'model' => 'Terreno', 'format' => 'number'));
  $this->Model->printIfExists($info, 'sector', array('label' => 'Sector', 'model' => 'Terreno', 'format' => 'number'));
  ?>
</ul>

<ul>
	<?php
	if (!empty($info['Inmueble']['calidad_precio'])) {
		echo '<li><span>Calidad / precio</span>' . $calidadPrecio[$info['Inmueble']['calidad_precio']] . '.</li>';
	}
	?>
</ul>