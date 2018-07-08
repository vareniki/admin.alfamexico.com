<?php
$config = Configure::read('alfainmo');
$img_path = $config ['images.path'];
$rtf_file = 'hoja-visita-' . $info['Inmueble']['numero_agencia'] . '-' . $info['Inmueble']['codigo'] . '.rtf';
$subtipo = $this->Inmuebles->getSubtipo($info);

header('Content-Disposition: attachment; filename="' . $rtf_file . '"');

App::import('Vendor', 'phprtflite', array('file' => 'phprtf' . DS . 'PHPRtfLite.php'));

PHPRtfLite::registerAutoloader();
$rtf = new PHPRtfLite();

$rtf->setPaperFormat(PHPRtfLite::PAPER_LETTER);

$rtf->setMarginLeft(1);
$rtf->setMarginRight(1);
$rtf->setMarginTop(1);
$rtf->setMarginBottom(1);

$font = new PHPRtfLite_Font(11, 'Verdada', '#172556');
$fontBig = new PHPRtfLite_Font(14, 'Verdada', '#172556');
$font_monospace = new PHPRtfLite_Font(11, 'Courier New', '#172556');
$fontFooter = new PHPRtfLite_Font(8, 'Verdana');
$page_center = new PHPRtfLite_ParFormat('center');
$sect = $rtf->addSection();

$sect->writeText("<strong>HOJA DE VISITA E INFORMATIVA PARA LOS INTERESADOS</strong><br>", $fontBig, $page_center);

// FOTOS E INFORMACIÓN BÁSICA
$table = $sect->addTable();
$table->addRow();
$table->addColumnsList([6, 6, 7]);

$left_cell = $table->getCell(1, 1);
$left_cell->setCellPaddings(0, 0.3, 0.3, 0.3);

$center_cell = $table->getCell(1, 2);
$center_cell->setCellPaddings(0.3, 0.3, 0.3, 0.3);

$right_cell = $table->getCell(1, 3);
$right_cell->setCellPaddings(0.3, 0.3, 0, 0.3);

// Foto 1
$img = $img_path . $this->Inmuebles->getFirstImage($info, 'm', array('nohtml' => true));
if (file_exists($img)) {
	$left_cell->addImage($img);
}

// Foto 2
$img = $img_path . $this->Inmuebles->getImageIndex($info, 'm', 1, array('nohtml' => true));
if (file_exists($img)) {
	$center_cell->addImage($img);
}

// INFORMACIÓN DEL INMUEBLE - Columna derecha
$right_cell->writeText('REF: ' . $info['Inmueble']['referencia'] . '<br>', $font);
$right_cell->writeText('TIPO: ' . $info['TipoInmueble']['description'] . '<br>', $font);

$lines = [];
$lines[] = $this->Model->getIfExists($info, 'piso', array('label' => 'PLANTA:', 'model' => $subtipo));
$lines[] = $this->Model->getIfExists($info, 'area_total_construida', array('label' => 'SUPERF:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, 'area_total', array('label' => 'SUPERF:', 'model' => $subtipo, 'format' => 'area'));
$lines[] = $this->Model->getIfExists($info, 'numero_habitaciones', array('label' => 'HABITAC:', 'model' => $subtipo));

$lines[] = $this->Model->getIfExists($info, 'numero_banos', array('label' => 'Ba&ntilde;os:', 'model' => $subtipo));
$lines[] = $this->Model->getIfExists($info, 'numero_aseos', array('label' => 'Medios Ba&ntilde;os:', 'model' => $subtipo));

if ($info['Inmueble']['es_venta'] == 't') {
	$lines[] = $this->Model->getIfExists($info, 'precio_venta', array('label' => 'PRECIO:', 'format' => 'currency'));
}
if ($info['Inmueble']['es_alquiler'] == 't') {
	$lines[] = $this->Model->getIfExists($info, 'precio_alquiler', array('label' => 'PRECIO', 'format' => 'currency'));
}

foreach ($lines as $line) {
	if (!empty($line)) {
		$right_cell->writeText( strip_tags($line) . '<br>', $font);
	}
}

$right_cell->writeText('<br>P.V.P.: Gastos de Notaría, Registro e impuestos no incluidos.', new PHPRtfLite_Font(9, 'Arial'));

// Plano
$img = $img_path . $this->Inmuebles->getPlano($info, 'o', array('nohtml' => true, 'original' => false));
if (file_exists($img)) {
	$sect->addImage($img, new PHPRtfLite_ParFormat('center'));
}

// LEYENDA
$info_agencia =
		$agencia['Agencia']['nombre_calle'] . ' n. ' . $agencia['Agencia']['numero_calle'] .
		$agencia['Agencia']['codigo_postal'] . ' - ' . $agencia['Agencia']['poblacion'] . ' (' . $agencia['Agencia']['provincia'] . ')';

$leyenda =
		'<br><br>La inmobiliaria <strong>' . $agencia['Agencia']['nombre_fiscal'] . '</strong>, con domicilio en <b>' . $info_agencia .
    ' </b>en cumplimiento del encargo de venta realizado por Sr/Sra ________________ y _____________________' .
    ' así como de la normativa vigente en materia de consumo pone en conocimiento y entrega al Sr/Sra _________________,' .
    ' con No. De INE _________________ y domicilio en _________________. (en adelante los "interesados")' .
    ' la información contenida en el presente documento.<br>' .
    ' El inmueble descrito anteriormente SI [ ] / NO [ ] (márquese lo que proceda) ha sido visitado por "los interesados"' .
    ' con la inmobiliaria el día ______________ a las _________ horas; comprometiéndose los interesados expresamente a informar' .
    ' a la inmobiliaria en el caso de que finalmente decidiera adquirir el inmueble referenciado anteriormente.<br><br><br>' .
    'En ' . $info['Agencia']['poblacion'] . ' a ' . date('d') . ' de ' . date('M') . ' de ' . date('Y') . '.<br><br><br>';

$sect->writeText($leyenda, $font, new PHPRtfLite_ParFormat('justify'));
$sect->writeText('LA INMOBILIARIA          LOS INTERESADOS          EL PROPIETARIO', $font, $page_center);

$sect->insertPageBreak();

$sect->writeText("Conclusiones de la Visita<br><br>", new PHPRtfLite_Font(16), $page_center);
$sect->writeText('VALORACIÓN DE LA VIVIENDA (1 a 10)<br><br><br>', new PHPRtfLite_Font(12));

$valoracs = ['Cocina', 'Salón-Comedor', 'Baños', 'Dormitorio principal', 'Dormitorio 1', 'Dormitorio 2',
	'Areas comunes', 'Orientación', '', 'VALORACIÓN ZONA', 'VALORACIÓN EDIFICIO O FACHADA', 'PRECIO DE VENTA'];

foreach ($valoracs as $valorac) {
	if (empty($valorac)) {
		$sect->writeText('<br><br>');
	} else {
		$str = str_pad($valorac, 40, '.');
		if (mb_strlen($str) != 40) {
			$str .= '.';
		}

		$sect->writeText($str . ' : ____<br>', $font_monospace);
	}
}

$sect->writeText('<br>VALORACIÓN GENERAL DE TODOS LOS INMUEBLES VISITADOS : _____', $font, new PHPRtfLite_ParFormat('center'));
$sect->writeText('<br>CONCLUSIONES :', $font, new PHPRtfLite_ParFormat('center'));

// CABECERA
$header = $sect->addHeader();
$image = $header->addImage(realpath('img/cab-hoja-visita.png'), null, 19);

// PIE
$footer = $sect->addFooter();

$info_agencia =
		$agencia['Agencia']['nombre_agencia'] . ' - ' .
		$agencia['Agencia']['nombre_calle'] . ' n. ' . $agencia['Agencia']['numero_calle'] .
		$agencia['Agencia']['codigo_postal'] . ' - ' . $agencia['Agencia']['poblacion'] . ' (' . $info['Agencia']['provincia'] . '). ' .
		$agencia['Agencia']['telefono1_contacto'] . ' - ' . $agencia['Agencia']['email_contacto'];

$footer->writeText($info_agencia, $fontFooter, new PHPRtfLite_ParFormat('center'));

$rtf->sendRtf($rtf_file);