<?php
echo $this->Form->hidden('Local.id');
echo $this->Form->hidden('Local.inmueble_id');
echo $this->App->horizontalRadio('Local.tipo_local_id', '<span>[*]</span> Tipo de local:', $tiposLocal, array('required' => 'true'));
?>
<div class="form-group">
  <label class="control-label col-xs-5 col-lg-4 col-sm-4"><span>[*]</span> Operaci&oacute;n:</label>
  <div class="controls col-xs-7 col-lg-8 col-sm-8" id="tipoOperacion">
    <?php
    echo $this->Form->checkbox('Inmueble.es_venta', array('value' => 't', 'label' => 'venta'));
    echo $this->Form->checkbox('Inmueble.es_alquiler', array('value' => 't', 'label' => 'renta'));
    echo $this->Form->checkbox('Inmueble.es_traspaso', array('value' => 't', 'label' => 'traspaso'));
    echo '<br>';
    echo $this->Form->checkbox('Inmueble.es_promocion', array('value' => 't', 'label' => 'desarrollo'));
    ?>
  </div>
</div>
<?php
echo $this->App->horizontalInput('Inmueble.nombre_promocion', '<span>[*]</span> Nombre del desarrollo:', array(
  'required' => true, 'maxlength' => 64, 'divClass' => 'oculto divEsPromocion'));
echo $this->App->horizontalInput('Inmueble.entrega_promocion', 'Entrega aproximada:', array(
  'type' => 'text', 'maxlength' => 64, 'placeholder' => 'escriba una fecha aproximada de entrega', 'divClass' => 'oculto divEsPromocion'));
?>
<div class="oculto InmuebleEsVenta InmuebleEsAlquiler">
  <br>
  <?php
  echo $this->App->horizontalInput('Inmueble.precio_venta', '<span>[*]</span> Precio de venta:', array(
    'type' => 'number', 'required' => true, 'min' => 100, 'max' => 9999999999, 'divClass' => 'oculto InmuebleEsVenta', 'labelClass' => 'obligat'));
  echo $this->App->horizontalInput('Inmueble.precio_alquiler', '<span>[*]</span> Precio de renta:', array(
    'type' => 'number', 'required' => true, 'min' => 10, 'max' => 9999999, 'divClass' => 'oculto InmuebleEsAlquiler'));
  echo $this->App->horizontalInput('Inmueble.precio_traspaso', '<span>[*]</span> Precio de traspaso:', array(
    'type' => 'number', 'required' => true, 'min' => 100, 'max' => 9999999999, 'divClass' => 'oculto InmuebleEsTraspaso', 'labelClass' => 'obligat'));
  echo $this->App->horizontalSelect('Inmueble.moneda_id', '<span>[*]</span> Moneda:', $monedas, array('labelClass' => 'obligat', 'style' => 'width:96px'));
  ?>
	<hr>
	<?php
	echo $this->App->horizontalInput('Inmueble.precio_venta_ini', 'Precio de venta inicial:', array('divClass' => 'oculto InmuebleEsVenta', 'disabled' => 'disabled'));
	echo $this->App->horizontalInput('Inmueble.precio_alquiler_ini', 'Precio de renta inicial:', array('divClass' => 'oculto InmuebleEsAlquiler', 'disabled' => 'disabled'));
	echo $this->App->horizontalInput('Inmueble.precio_traspaso_ini', 'Precio de traspaso:', array('divClass' => 'oculto InmuebleEsTraspaso', 'disabled' => 'disabled'));
	?>
</div>
<!-- Gastos de comunidad -->
<?php
echo $this->App->horizontalInput('Local.gastos_comunidad', 'Gastos de comunidad:', array('type' => 'number', 'div' => false, 'min' => 5, 'max' => 100000));
echo "<hr>";
echo $this->App->horizontalInput('Inmueble.titulo_anuncio', 'T&iacute;tulo del anuncio:', array('maxlength' => 100));