<ul>
  <?php
  $this->Model->printIfExists($info, 'anio_construccion', array('label' => 'Año', 'model' => 'Oficina'));
  $this->Model->printIfExists($info, 'altura_edificio', array('label' => 'Altura edificio', 'model' => 'Oficina', 'format' => 'number'));
  $this->Model->printIfExists($info, 'area_total_construida', array('label' => 'M2 construidos', 'model' => 'Oficina', 'format' => 'area'));
  $this->Model->printIfExists($info, 'area_total_util', array('label' => 'M2 &uacute;tiles', 'model' => 'Oficina', 'format' => 'area'));
  $this->Model->printIfExists($info, 'area_salon', array('label' => 'Área salón', 'model' => 'Oficina', 'format' => 'area'));
  $this->Model->printIfExists($info, '["Oficina"]["PlantaPiso"]["description"]', array('label' => 'Piso', 'model' => 'expression'));
  ?>
</ul>
<ul>
  <?php
  $this->Model->printIfExists($info, 'plantas_edificio', array('label' => 'Plantas edificio', 'model' => 'Oficina'));
  $this->Model->printIfExists($info, 'numero_ascensores', array('label' => 'Ascensores', 'model' => 'Oficina'));
  $this->Model->printIfExists($info, 'numero_banos', array('label' => 'Ba&ntilde;os', 'model' => 'Oficina'));
  $this->Model->printIfExists($info, 'numero_aseos', array('label' => 'Medios ba&ntilde;os', 'model' => 'Oficina'));
  $this->Model->printIfExists($info, 'numero_armarios', array('label' => 'Armarios', 'model' => 'Oficina'));

  $this->Model->printIfExists($info, 'numero_habitaciones', array('label' => 'Habitaciones', 'model' => 'Oficina'));
  $this->Model->printIfExists($info, 'plazas_parking', array('label' => 'Plazas de parking', 'model' => 'Oficina'));
  ?> 
</ul>

<ul>
  <?php
  $this->Model->printBooleans($info, array(
    'con_instalaciones_deportivas' => 'instalaciones deportivas',
    'con_zona_carga_descarga' => 'zona de carga y descarga',
    'con_almacen' => 'almacén'), array('model' => 'Oficina', 'label' => 'Instalaciones'));

  $this->Model->printBooleans($info, array(
    'con_puerta_seguridad' => 'puerta de seguridad',
    'con_camaras_seguridad' => 'cámaras de seguridad',
    'con_alarma' => 'alarma',
    'con_vigilancia_24h' => 'vigilancia 24h'), array('model' => 'Oficina', 'label' => 'Seguridad'));
  ?>
</ul>
<ul>
  <?php
  $this->Model->printBooleans($info, array(
    '["Oficina"]["InteriorExterior"]["description"]' => '',
    '["Oficina"]["TipoOrientacion"]["description"]' => 'orientación',
    '["Oficina"]["EstadoConservacion"]["description"]' => ''), array('model' => 'expression', 'label' => 'Características'));

  $this->Model->printIfExists($info, '["Oficina"]["TipoSuelo"]', array('label' => 'Suelos', 'model' => 'expression'));
  $this->Model->printIfExists($info, '["Oficina"]["TipoPuerta"]', array('label' => 'Puertas', 'model' => 'expression'));
  $this->Model->printIfExists($info, '["Oficina"]["TipoVentana"]', array('label' => 'Ventanas', 'model' => 'expression'));

  $this->Model->printBooleans($info, array(
    '["Oficina"]["TipoCalefaccion"]["description"]' => 'calefacción',
    '["Oficina"]["TipoAA"]["description"]' => 'aire acondicionado ',
    '["Oficina"]["TipoAguaCaliente"]["description"]' => 'agua caliente',
    '["Oficina"]["TipoCableado"]["description"]' => 'cableado'), array('model' => 'expression', 'label' => 'Equipamiento'));
  
  $this->Model->printIfExists($info, 'subtipo_calefaccion', array('label' => 'Calefacción individual', 'model' => 'Oficina'));
  ?>
</ul>
<ul>
  <?php
  $this->Model->printIfExists($info, 'gastos_comunidad', array('label' => 'Gastos de comunidad', 'model' => 'Oficina', 'format' => 'currency', 'places' => 2));

  if (!empty($info['Inmueble']['calidad_precio'])) {
	  echo '<li><span>Calidad / precio</span>' . $calidadPrecio[$info['Inmueble']['calidad_precio']] . '.</li>';
  }
  ?>
</ul>