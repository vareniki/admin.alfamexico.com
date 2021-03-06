<?php
echo $this->Form->hidden('Otro.id');
echo $this->Form->hidden('Otro.inmueble_id');
echo $this->App->horizontalRadio('Otro.tipo_otro_id', '<span>[*]</span> Tipo:', $tiposOtro, array('required' => 'true'));
?>
<div class="form-group">
  <label class="control-label col-xs-5 col-lg-4 col-sm-4"><span>[*]</span> Operaci&oacute;n:</label>
  <div class="controls col-xs-7 col-lg-8 col-sm-8" id="tipoOperacion">
    <?php
    echo $this->Form->checkbox('Inmueble.es_venta', array('value' => 't', 'label' => 'venta'));
    echo $this->Form->checkbox('Inmueble.es_alquiler', array('value' => 't', 'label' => 'renta'));
    ?>
  </div>
</div>
<div class="oculto InmuebleEsVenta InmuebleEsAlquiler">
  <br>
  <?php
  echo $this->App->horizontalInput('Inmueble.precio_venta', '<span>[*]</span> Precio de venta:', array(
    'type' => 'number', 'required' => true, 'min' => 100, 'max' => 9999999999, 'divClass' => 'oculto InmuebleEsVenta', 'labelClass' => 'obligat'));
  echo $this->App->horizontalInput('Inmueble.precio_alquiler', '<span>[*]</span> Precio de renta:', array(
    'type' => 'number', 'required' => true, 'min' => 10, 'max' => 9999999, 'divClass' => 'oculto InmuebleEsAlquiler', 'labelClass' => 'obligat'));
  echo $this->App->horizontalSelect('Inmueble.moneda_id', '<span>[*]</span> Moneda:', $monedas, array('labelClass' => 'obligat', 'style' => 'width:96px'));
  ?>
	<hr>
	<?php
	echo $this->App->horizontalInput('Inmueble.precio_venta_ini', 'Precio de venta inicial:', array('divClass' => 'oculto InmuebleEsVenta', 'disabled' => 'disabled'));
	echo $this->App->horizontalInput('Inmueble.precio_alquiler_ini', 'Precio de renta inicial:', array('divClass' => 'oculto InmuebleEsAlquiler', 'disabled' => 'disabled'));
	?>
</div>
<?php
echo "<hr>";
echo $this->App->horizontalInput('Inmueble.titulo_anuncio', 'T&iacute;tulo del anuncio:', array('maxlength' => 100));