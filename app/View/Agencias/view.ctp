<?php
// app/View/Inmuebles/add.ctp
$this->extend('/Common/view2');

$title = 'Agencia ' . $info['Agencia']['numero_agencia'] . ' - ' . $info['Agencia']['nombre_agencia'];
$this->set('title_for_layout', $title);

$this->start('sidebar');
echo $this->element('agencias_left');
$this->end();
$url_64 = $this->data['referer'];
?>
<div class="tabbable ficha">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Informaci&oacute;n</a></li>
  </ul>
  <div class="tab-content">
    <div id="tab1" class="tab-pane active">
      <p class="titulo">Informaci&oacute;n de la agencia:</p>
      <ul>
        <?php
        $this->Model->printIfExists($info, 'numero_agencia', array('label' => 'Número de agencia', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'nombre_agencia', array('label' => 'Nombre de la agencia', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'nombre_fiscal', array('label' => 'Nombre fiscal', 'model' => 'Agencia'));

        $this->Model->printIfExists($info, '["Pais"]["description"]', array('label' => 'País', 'model' => 'expression'));

        $this->Model->printIfExists($info, 'codigo_postal', array('label' => 'C.Postal', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, '["Provincia"]["description"]', array('label' => 'Estado', 'model' => 'expression'));
        $this->Model->printIfExists($info, 'poblacion', array('label' => 'Municipio', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'nombre_calle', array('label' => 'Calle / vía', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'numero_calle', array('label' => 'Número de vía', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'coord_x', array('label' => 'Latitud', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'coord_y', array('label' => 'Longitud', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'nombre_contacto', array('label' => 'Nombre', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'email_contacto', array('label' => 'EMail', 'format' => 'email', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'email_dueno', array('label' => 'EMail due&ntilde;o', 'format' => 'email', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, array('telefono1_contacto', 'telefono2_contacto'), array('label' => 'Teléfonos', 'format' => 'tel', 'model' => 'Agencia'));
        $this->Model->printIfExists($info, 'fax', array('label' => 'Fax', 'model' => 'Agencia'));

        $this->Model->printIfExists($info, 'observaciones', array('label' => 'Observaciones', 'model' => 'Agencia', 'format' => 'links_html'));
        echo '<hr>';
        $this->Model->printBooleans($info,
            ['active' => 'activa','suspended' => 'suspendida'],
            ['model' => 'Agencia', 'label' => 'Estado']);
        ?>
      </ul>
    </div>
  </div>
<hr>
<div class='text-right'>
	<a href="<?php echo base64_decode($url_64) ?>" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-list"></i> volver al listado</a>
	<?php
	if ($profile['is_central']) {
		$edit = 'edit/' . $info['Agencia']['id'] . '/' . $url_64;
		echo $this->Html->link('<i class="glyphicon glyphicon-edit"></i> edici&oacute;n', $edit, array('class' => 'btn btn-sm btn-default', 'escape' => false)) . "\n";
	}
	?> 
</div>
