<?php $subinfo = $this->Inmuebles->getSubtipoInfo( $info ); ?>
[fusion_images picture_size="auto" hover_type="none" autoplay="yes" columns="5" column_spacing="13"	scroll_items="" show_nav="yes" mouse_scroll="no" border="no" lightbox="yes" class="" id=""]
<?php
$images = $this->Inmuebles->getAllImages( $info, array( 'no_forzar' => true ) );
$i = 0;
foreach ( $images as $image ):

  $img = str_replace('/g/', '/gw/', $image['src-g']);
  $img = str_replace('/o/', '/ow/', $img);

	$img = 'http://admin.alfamexico.com/' . $img;
	?>
	[fusion_image link="" linktarget="_self" image="<?php echo $img?>" alt="" /]
<?php endforeach; ?>
[/fusion_images]
[fusion_builder_row]
[fusion_builder_column type="1_2" layout="1_2" spacing="" center_content="no" padding="0px 30px 0px 30px"]
<div class="info-ficha">
<?php
	if ( ! empty( $info['Inmueble']['descripcion'] ) ) {
		echo '[fusion_title size="3" content_align="left" style_type="underline solid"]Descripción[/fusion_title]';
		echo '[fusion_text]';
		$this->Model->printIfExists( $info, 'descripcion', array( 'tag' => 'p' ) );

		if (!empty($info['Inmueble']['video'])) {

			$video = $info['Inmueble']['video'];
			$parse = $this->Inmuebles->parseVideos($video);

			if (empty($parse)) {
				echo '<p style="text-align: center; font-size: 20px"><a href="' . $video . '" target="_blank">VER V&Iacute;DEO</a></p>';
			} else {
				foreach ($parse as $videoact) {
					echo '<p style="text-align:center"><iframe src="' . $videoact['url'] . '" frameborder="0" height="225px"></iframe></p>';
				}
			}
		}
	echo '[/fusion_text]';
}?><br>
	[fusion_title size="3" content_align="left" style_type="underline solid"]Localización[/fusion_title]
	[fusion_text]
<?php
  $ciudad = $this->Inmuebles->printCiudad( $info );
  if (!empty($ciudad)) { echo $ciudad; }
?>
	[/fusion_text]
<br>[fusion_title size="3" content_align="left" style_type="underline solid"]Contacto[/fusion_title]
	%datos_agencia%
</div>
[/fusion_builder_column]
[fusion_builder_column type="1_2" layout="1_2" spacing="" center_content="no"  padding="0px 30px 0px 30px"]
<div class="info-ficha">
	[fusion_title size="3" content_align="left" style_type="underline solid"]Información[/fusion_title]
	[fusion_text]
<p><?php
	$adicional = '';
	echo $this->Inmuebles->printDescripcion( $info, $adicional );
	?>
</p>
<ul>
	<?php
	if ( $info['Inmueble']['es_venta'] == 't' ) {
		$this->Model->printIfExists( $info, 'precio_venta', array(
				'label'  => 'Precio de venta',
				'format' => 'currency'
		) );
	}
	if ( $info['Inmueble']['es_alquiler'] == 't' ) {
		$this->Model->printIfExists( $info, 'precio_alquiler', array(
				'label'  => 'Precio de renta',
				'format' => 'currency'
		) );
	}
	?>
</ul>
<?php
if ( $info['Inmueble']['es_promocion'] == 't' ) {
	echo '<p class="text-info">El inmueble forma parte de un desarrollo.</p>';
	echo '<ul>';
	$this->Model->printIfExists( $info, 'nombre_promocion', array( 'label' => 'Nombre desarrollo: ' ) );
	$this->Model->printIfExists( $info, 'entrega_promocion', array( 'label' => 'Entrega desarrollo: ' ) );
	echo '</ul>';
}

if ( isset($tipoInmueble) ) {
	echo $this->element( "inmuebles/view_info_$tipoInmueble" );
}
?>
[/fusion_text]
</div>
[/fusion_builder_column][/fusion_builder_row]