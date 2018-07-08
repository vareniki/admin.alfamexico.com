<?php
$config = Configure::read('alfainmo');
$img_path = $config ['images.path'];
$rtf_file = 'ficha-escaparate-' . $info['Inmueble']['numero_agencia'] . '-' . $info['Inmueble']['codigo'] . '.rtf';

header('Content-Disposition: attachment; filename="' . $rtf_file . '"');

App::import('Vendor', 'phprtflite', array('file' => 'phprtf' . DS . 'PHPRtfLite.php'));

PHPRtfLite::registerAutoloader();
$rtf = new PHPRtfLite();

$rtf->setMarginLeft(1);
$rtf->setMarginRight(1);
$rtf->setMarginTop(1);
$rtf->setMarginBottom(1);

$sect = $rtf->addSection();

// Formatos
$centerFormat = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER);
$descFont = new PHPRtfLite_Font(14, 'Verdana');

// Titular de la ficha de escaparate
$font = new PHPRtfLite_Font(16, 'Verdana');
$fontTitle = new PHPRtfLite_Font(16, 'Verdana');
$fontFooter = new PHPRtfLite_Font(8, 'Verdana');
$sect->writeText($this->Inmuebles->printDescripcion($info), $fontTitle, $centerFormat);
$sect->writeText('<br><br>');

// Tabla con imágenes y descripción
$table = $sect->addTable();
$table->addRow();
$table->addColumnsList([8, 11]);

$left_cell = $table->getCell(1, 1);
$right_cell = $table->getCell(1, 2);
$right_cell->setPaddingLeft(0.2);

// Foto 1
$img = $img_path . $this->Inmuebles->getFirstImage($info, 'm', array('nohtml' => true));
if (file_exists($img)) {
	$left_cell->addImage($img);
	$left_cell->writeText("<br><br>");
}

// Foto 2
$img = $img_path . $this->Inmuebles->getImageIndex($info, 'm', 1, array('nohtml' => true));
if (file_exists($img)) {
	$left_cell->addImage($img);
	$left_cell->writeText("<br><br>");
}

// Plano
$img = $img_path . $this->Inmuebles->getPlano($info, 'm', array('nohtml' => true, 'original' => false));
if (file_exists($img)) {
	$left_cell->addImage($img);
}

// Descripción
$subtipo = $this->Inmuebles->getSubtipo($info);

$lines = [];
$lines[] = $this->Model->getIfExists($info, 'numero_habitaciones', array('label' => 'Habitaciones:', 'model' => $subtipo));
$lines[] = $this->Model->getIfExists($info, 'numero_banos', array('label' => 'Ba&ntilde;os:', 'model' => $subtipo));
$lines[] = $this->Model->getIfExists($info, 'numero_aseos', array('label' => 'Medios Ba&ntilde;os:', 'model' => $subtipo));

$lines[] = $this->Model->getIfExists($info, 'anio_construccion', array('label' => 'A&ntilde;o Construcci&oacute;n:', 'model' => $subtipo));
$lines[] = $this->Model->getIfExists($info, 'area_total_construida', array('label' => 'M2 Construidos:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, 'area_total_util', array('label' => 'M2 &Uacute;tiles:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, 'area_salon', array('label' => '&Aacute;rea Sal&oacute;n:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, 'area_terraza', array('label' => '&Aacute;rea Terraza:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, 'area_parcela', array('label' => 'M2 Terreno:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, '["' . $subtipo . '"]["PlantaPiso"]["description"]', array('label' => 'Piso:', 'model' => 'expression'));
$lines[] = $this->Model->getIfExists($info, 'plantas_edificio', array('label' => 'Plantas Edificio:', 'model' => $subtipo, 'format' => 'number'));

$lines[] = $this->Model->getIfExists($info, 'plazas_parking', array('label' => 'Plazas de Estacionamiento:', 'model' => $subtipo));
$lines[] = $this->Model->getIfExists($info, 'numero_ascensores', array('label' => 'Ascensores:', 'model' => $subtipo));

$lines[] = $this->Model->getBooleans($info, array(
		'con_piscina' => 'piscina',
		'con_parking' => 'parking',
		'con_areas_verdes' => 'áreas verdes',
		'con_zonas_infantiles' => 'zona de juego infantil',
		'con_trastero' => 'trastero/bodega'), array('model' => $subtipo, 'label' => 'Con '));

if ($info['Inmueble']['es_venta'] == 't') {
	$lines[] = $this->Model->getIfExists($info, 'precio_venta', array('label' => 'Precio Venta:', 'format' => 'currency'));
}
if ($info['Inmueble']['es_alquiler'] == 't') {
	$lines[] = $this->Model->getIfExists($info, 'precio_alquiler', array('label' => 'Precio Renta:', 'format' => 'currency'));
}

foreach ($lines as $line) {
	if (!empty($line)) {
		$right_cell->writeText( str_replace ('.', '', strip_tags($line))  . '<br>', $descFont);
	}
}

$right_cell->writeText("<br>REFERENCIA " . $info['Inmueble']['referencia']. '<br><br>', $font);
$right_cell->writeText($info['Inmueble']['descripcion'], $descFont);

// Fin de la tabla

// Información de la agencia
$info_agencia =
		$agencia['Agencia']['nombre_agencia'] . ' - ' .
    $agencia['Agencia']['nombre_calle'] . ' n. ' . $agencia['Agencia']['numero_calle'] .
    ', CP: ' . $agencia['Agencia']['codigo_postal'] . ' - ' . $agencia['Agencia']['poblacion'] . ' (' . $agencia['Agencia']['provincia'] . '). ' .
    $agencia['Agencia']['telefono1_contacto'] . ' - ' . $agencia['Agencia']['email_contacto'];

$sect->writeText('<br>');
$sect->writeText($info_agencia, $fontFooter, $centerFormat);

$footer = $sect->addFooter();
$image = $footer->addImage(realpath('img/pie-ficha-escaparate.png'), null, 19);

$rtf->sendRtf($rtf_file);