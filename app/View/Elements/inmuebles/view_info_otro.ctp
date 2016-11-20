<ul>
  <?php $this->Model->printIfExists($info, 'area_total', array('label' => 'Área total', 'model' => 'Otro', 'format' => 'area'));

  if (!empty($info['Inmueble']['calidad_precio'])) {
	  echo '<li><span>Calidad / precio</span>' . $calidadPrecio[$info['Inmueble']['calidad_precio']] . '.</li>';
  }
  ?>
</ul>