<?php 
header("Content-type: text/xml");

header( "Content-type: text/xml" );

$host     = "91.142.209.61";
$database = "alfamx_web_info";
$user     = "alfainmo";
$pass     = "f4k86y6";

$linkID = mysqli_connect( $host, $user, $pass, $database ) or die( "Could not connect to host." );

$query="SELECT id, numero_agencia, provincia, poblacion, nombre_calle, numero_calle, codigo_postal, telefono1_contacto, email_contacto FROM agencias";

$resultID = mysqli_query( $linkID, $query ) or die( "Data not found." );

$xml_output = "<?xml version=\"1.0\"?>\n";
$xml_output .= "<oficinas>\n";

for($x = 0 ; $x < mysqli_num_rows($resultID) ; $x++){
	
    $row = mysqli_fetch_assoc($resultID);

    $xml_output .= "\t<oficina>\n";
    $xml_output .= "\t\t<referencia>" . $row['id'] . "</referencia>\n";
		$xml_output .= "\t\t<oficina>" . $row['numero_agencia'] . "</oficina>\n";
		$xml_output .= "\t\t<estado>" . utf8_encode($row['provincia']) . "</estado>\n";
		$xml_output .= "\t\t<ciudad>" . utf8_encode($row['poblacion']) . "</ciudad>\n";
	
	  $direccion = utf8_encode(trim($row['nombre_calle'] . ' ' . $row['numero_calle']));
	
	 	$direccion = str_replace("&", "&", $direccion);
    $direccion = str_replace("<", "<", $direccion);
    $direccion = str_replace(">", "&gt;", $direccion);
    $direccion = str_replace("\"", "&quot;", $direccion);
		
		 $st = array("&", "<", ">", "\"", "¡" ); 
		 $rp = array("y", "-", "-", "-", "I" ); 
	
		$xml_output .= "\t\t<direccion>" . str_replace($st, $rp, $direccion) . "</direccion>\n";
		 
		$xml_output .= "\t\t<codigopostal>" . $row['codigo_postal'] . "</codigopostal>\n";
		$xml_output .= "\t\t<telefono>" . $row['telefono1_contacto'] . "</telefono>\n";
		$xml_output .= "\t\t<email>" . utf8_encode($row['email_contacto']) . "</email>\n";
     
  $xml_output .= "\t</oficina>\n";
}

$xml_output .= "</oficinas>";

echo $xml_output;