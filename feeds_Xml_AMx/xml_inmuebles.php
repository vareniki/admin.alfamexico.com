<?php
	//ini_set('display_errors', 1);
header( "Content-type: text/xml" );

$host     = "91.142.209.61";
$database = "alfamx_web_info";
$user     = "alfainmo";
$pass     = "f4k86y6";

$linkID = mysqli_connect( $host, $user, $pass, $database ) or die( "Could not connect to host." );

$query = "SELECT i.id, 
	CONCAT(i.numero_agencia, '/', i.codigo) as referencia_publica, 
  i.numero_agencia AS _owner_office_id, 
  i.descripcion as texto_web, 
  0 as featured, 
  i.provincia, 
  i.poblacion, 
  i.zona, 
  i.nombre_calle as direccion, 
  i.codigo_postal as c_p,
  ti.description AS tipo_familia_inmueble,
  COALESCE(tipo_pi.description, tipo_ch.description, tipo_of.description) AS tipo_inmueble,
  COALESCE(i.precio_venta, i.precio_alquiler) AS pvp,
  tipo_mo.description AS currency_id,
  COALESCE(pi.area_total_construida, ch.area_total_construida, lo.area_total_construida, of.area_total_construida) AS superficie,
  COALESCE(pi.numero_habitaciones, ch.numero_habitaciones, of.numero_habitaciones) AS habitaciones,
  IF(i.es_venta = 't', 'Venta', 'Renta') AS operacion_inmueble,
  COALESCE(pi.numero_banos, ch.numero_banos, of.numero_banos) + COALESCE(pi.numero_aseos, ch.numero_aseos, of.numero_aseos, 0) AS banos,
  COALESCE(pi.numero_ascensores, of.numero_ascensores) AS ascensor,
  YEAR(CURDATE()) - COALESCE(pi.anio_construccion, ch.anio_construccion, of.anio_construccion) AS years,
  IF(COALESCE(pi.con_piscina, ch.con_piscina) = 't', 1, 0) AS pool,
  COALESCE(pi.plazas_parking, ch.plazas_parking, lo.plazas_parking, of.plazas_parking) AS pk,
  ch.area_parcela AS supt,
  IF(COALESCE(pi.tipo_equipamiento_id, ch.tipo_equipamiento_id) = '03', 1, 0) AS muebles,
  COALESCE(plantas_pi1.description, plantas_pi2.description) AS nivel,
  IF(COALESCE(pi.con_areas_verdes, ch.con_areas_verdes) = 't', 1, 0) AS garden

FROM inmuebles i JOIN taux_tipos_inmueble ti ON i.tipo_inmueble_id = ti.id
  LEFT JOIN pisos pi ON pi.inmueble_id = i.id
    LEFT JOIN taux_tipos_piso tipo_pi ON tipo_pi.id = pi.tipo_piso_id
    LEFT JOIN taux_plantas_piso plantas_pi1 ON plantas_pi1.id = pi.piso
  LEFT JOIN chalets ch ON ch.inmueble_id = i.id
    LEFT JOIN taux_tipos_chalet tipo_ch ON tipo_ch.id = ch.tipo_chalet_id
  LEFT JOIN locales lo ON lo.inmueble_id = i.id
  LEFT JOIN oficinas of ON of.inmueble_id = i.id
    LEFT JOIN taux_tipos_oficina tipo_of ON tipo_of.id = of.tipo_oficina_id
    LEFT JOIN taux_plantas_piso plantas_pi2 ON plantas_pi2.id = of.piso
  JOIN taux_tipos_moneda tipo_mo ON tipo_mo.id = i.moneda_id
  
WHERE i.web='t' AND i.tipo_inmueble_id IN ('01', '02', '03', '04') AND (i.precio_particular IS NULL OR i.precio_particular = 0)";
  
$resultID = mysqli_query( $linkID, $query ) or die( "Data not found." );

$xml_output = "<?xml version=\"1.0\"?>\n";
$xml_output .= "<inmuebles>\n";

for ( $x = 0; $x < mysqli_num_rows( $resultID ); $x ++ ) {

	$row = mysqli_fetch_assoc( $resultID );

	$inmuID = $row['id'];

	$xml_output .= "\t<inmueble>\n";
	$xml_output .= "\t\t<referencia>" . $row['referencia_publica'] . "</referencia>\n";
	$xml_output .= "\t\t<operacion>" . $row['operacion_inmueble'] . "</operacion>\n";
	$xml_output .= "\t\t<familia>" . $row['tipo_familia_inmueble'] . "</familia>\n";
	$xml_output .= "\t\t<tipo>" . utf8_encode($row['tipo_inmueble']) . "</tipo>\n";
	$xml_output .= "\t\t<destacado>" . $row['featured'] . "</destacado>\n";
	$xml_output .= "\t\t<url>" . "http://alfamexico.com/referencia?id=" . $row['id'] . "</url>\n";

	$xml_output .= "\t\t<precio>" . $row['pvp'] . "</precio>\n";
	$xml_output .= "\t\t<currency>" . utf8_encode($row['currency_id']) . "</currency>\n";
	$xml_output .= "\t\t<superficie>" . $row['superficie'] . "</superficie>\n";
	$xml_output .= "\t\t<habitaciones>" . $row['habitaciones'] . "</habitaciones>\n";

	$supTerreno = $row['supt'];
	$xml_output .= "\t\t<superficie_terreno>" . $supTerreno . "</superficie_terreno>\n";


	$xml_output .= "\t\t<nivel>" . utf8_encode($row['nivel']) . "</nivel>\n";
	$parking = $row['pk'];
	$xml_output .= "\t\t<estacionamiento>" . $parking . "</estacionamiento>\n";

	$years = $row['years'];
	$xml_output .= "\t\t<antiguedad>" . $years . "</antiguedad>\n";

	$banos = $row['banos'];
	$xml_output .= "\t\t<banos>" . $banos . "</banos>\n";

	$pool = $row['pool'];
	$xml_output .= "\t\t<piscina>" . $pool . "</piscina>\n";

	$amb = $row['muebles'];
	$xml_output .= "\t\t<amueblado>" . $amb . "</amueblado>\n";


	$xml_output .= "\t\t<jardin>" . $row['garden'] . "</jardin>\n";
	$xml_output .= "\t\t<ascensor>" . $row['ascensor'] . "</ascensor>\n";
	$xml_output .= "\t\t<oficina>" . $row['_owner_office_id'] . "</oficina>\n";
	$xml_output .= "\t\t<estado>" . utf8_encode( $row['provincia'] ) . "</estado>\n";
	$xml_output .= "\t\t<ciudad>" . utf8_encode( $row['poblacion'] ) . "</ciudad>\n";
	$xml_output .= "\t\t<zona>" . utf8_encode($row['zona']) . "</zona>\n";

	$st = array( "&", "<", ">", "\"", "¡" );
	$rp = array( "y", "-", "-", "-", "I" );

	$xml_output .= "\t\t<direccion>" . str_replace( $st, $rp, utf8_encode($row['direccion']) ) . "</direccion>\n";
	$xml_output .= "\t\t<codigo_postal>" . $row['c_p'] . "</codigo_postal>\n";

	$xml_output .= "\t\t<texto_web>" . str_replace( $st, $rp, utf8_encode($row['texto_web']) ) . "</texto_web>\n";

	$queryFotos = "SELECT id, path, fichero, tipo_imagen_id FROM imagenes WHERE inmueble_id='$inmuID' ORDER BY orden ASC";
	$resultFotos = mysqli_query( $linkID, $queryFotos ) or die( "No hay fotos." );

	while ( $rowFoto = mysqli_fetch_assoc( $resultFotos ) ) {

		$xml_output .= "\t\t<fotos>http://admin.alfamexico.com/noauth/image/o/" . str_replace('/', '|', $rowFoto['path']) . '|' . $rowFoto['fichero'] . "</fotos>\n";
	}

	$xml_output .= "\t</inmueble>\n";
}

$xml_output .= "</inmuebles>";

echo $xml_output;

?>