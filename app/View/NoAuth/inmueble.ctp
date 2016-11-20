<?php $subinfo = $this->Inmuebles->getSubtipoInfo( $info ); ?>
[one_full last="yes"]
[images picture_size="auto" hover_type="none" autoplay="yes" columns="5" column_spacing="13"
	scroll_items="" show_nav="yes" mouse_scroll="no" border="no" lightbox="yes" class="" id=""]
<?php
$images = $this->Inmuebles->getAllImages( $info, array( 'no_forzar' => true ) );
$i = 0;
foreach ( $images as $image ):
	$img = 'http://admin.alfamexico.com/' . $image['src-g'];
	?>
	[image link="" linktarget="_self" image="<?php echo $img?>" alt=""]
<?php endforeach; ?>
[/images]
[/one_full]
[one_half last="no"]
<div class="info-ficha">
<?php
	if ( ! empty( $info['Inmueble']['descripcion'] ) ) {
		echo '[title size="3" content_align="left" style_type="underline solid"]Descripción[/title]';
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
	[title size="3" content_align="left" style_type="underline solid"]Localización[/title]
	[fusion_text]
<?php
  $ciudad = $this->Inmuebles->printCiudad( $info );
  if (!empty($ciudad)) { echo $ciudad; }
?>
	[/fusion_text]
<br>[title size="3" content_align="left" style_type="underline solid"]Contacto[/title]
	%datos_agencia%
</div>
[/one_half]
[one_half last="yes"]
<div class="info-ficha">
	[title size="3" content_align="left" style_type="underline solid"]Información[/title]
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
	if ( $info['Inmueble']['es_traspaso'] == 't' ) {
		$this->Model->printIfExists( $info, 'precio_traspaso', array(
				'label'  => 'Precio de traspaso',
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
[/one_half]
