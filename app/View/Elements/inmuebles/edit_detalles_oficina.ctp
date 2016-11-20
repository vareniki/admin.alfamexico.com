<?php $this->start('header'); ?>
<script type="text/javascript">
  var $traspasoCheck;
  $(document).ready(function() {
    if ($("#OficinaTipoCalefaccionId").val() != '') {
      $(".subtipoCalefaccion").show();
    } else {
      $(".subtipoCalefaccion").hide();
    }
    $("#OficinaTipoCalefaccion").on("click", function() {
      if (this.value != '') {
        $(".subtipoCalefaccion").show();
      } else {
        $(".subtipoCalefaccion").hide();
      }
    });
  });
</script>
<?php $this->end(); ?>
<div class="row">
  <div class="col-xs-5 col-lg-4 col-sm-4"></div>
  <div class="col-xs-7 col-lg-8 col-sm-8"><p class="text-info"><em>use tecla "control" para seleccionar o desmarcar varios suelos.</em></p></div>
</div>
<?php echo $this->App->horizontalSelect('Oficina.TipoSuelo', 'Suelos:', $tiposSuelo, array('size' => '9', 'multiple' => 'multiple')); ?>
<div class="row">
  <div class="col-xs-5 col-lg-4 col-sm-4"></div>
  <div class="col-xs-7 col-lg-8 col-sm-8"><p class="text-info"><em>use tecla "control" para seleccionar o desmarcar varias puertas.</em></p></div>
</div>
<?php echo $this->App->horizontalSelect('Oficina.TipoPuerta', 'Puertas:', $tiposPuerta, array('size' => '4', 'multiple' => 'multiple')); ?>
<div class="row">
  <div class="col-xs-5 col-lg-4 col-sm-4"></div>
  <div class="col-xs-7 col-lg-8 col-sm-8"><p class="text-info"><em>use tecla "control" para seleccionar o desmarcar varias ventanas.</em></p></div>
</div>
<?php
echo $this->App->horizontalSelect('Oficina.TipoVentana', 'Ventanas:', $tiposVentana, array('size' => '12', 'multiple' => 'multiple'));
echo $this->App->horizontalSelect('Oficina.tipo_calefaccion_id', 'Calefacción:', $tiposCalefaccion, array('size' => '3', 'empty' => true));
echo $this->App->horizontalInput('Oficina.subtipo_calefaccion', 'Tipo de calefacción:', array('datalist' => $subtiposCalefaccion, 'divClass' => 'subtipoCalefaccion oculto', 'placeholder' => 'puede escribir o seleccionar haciendo doble click.'));
echo $this->App->horizontalSelect('Oficina.tipo_aa_id', 'Aire acondicionado:', $tiposAA, array('size' => '4', 'empty' => true));
echo $this->App->horizontalSelect('Oficina.tipo_agua_caliente_id', 'Agua caliente:', $tiposAguaCaliente, array('size' => '4', 'empty' => true));
echo $this->App->horizontalSelect('Oficina.tipo_cableado_id', 'Cableado:', $tiposCableado, array('size' => '5', 'empty' => true));
?>
<div class="form-group">
  <label class="control-label col-md-5 col-lg-4 col-sm-4">Detalles:</label>
  <div class="col-md-7 col-lg-8 col-sm-8">
    <?php
    echo $this->Form->checkbox('Oficina.con_almacen', array('value' => 't', 'label' => 'almacén'));
    echo $this->Form->checkbox('Oficina.con_zona_carga_descarga', array('value' => 't', 'label' => 'zona de carga y descarga'));
    echo $this->Form->checkbox('Oficina.con_instalaciones_deportivas', array('value' => 't', 'label' => 'instalaciones deportivas'));
    ?>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-5 col-lg-4 col-sm-4">Seguridad:</label>
  <div class="col-md-7 col-lg-8 col-sm-8">
    <?php
    echo $this->Form->checkbox('Oficina.con_puerta_seguridad', array('value' => 't', 'label' => 'puerta de seguridad'));
    echo $this->Form->checkbox('Oficina.con_camaras_seguridad', array('value' => 't', 'label' => 'cámaras de seguridad'));
    echo $this->Form->checkbox('Oficina.con_alarma', array('value' => 't', 'label' => 'alarma'));
    echo $this->Form->checkbox('Oficina.con_vigilancia_24h', array('value' => 't', 'label' => 'vigilancia 24h'));
    ?>
  </div>
</div>
<?php
echo $this->App->horizontalSelect('Inmueble.calidad_precio', 'Calidad / precio:', array('' => '') + $calidadPrecio, array('size' => '3'));

echo $this->App->horizontalInput('Inmueble.descripcion', 'Descripci&oacute;n completa:', array('rows' => 6,
	'placeholder' => 'recuerda utilizar palabras clave como: zona, colegios, guarderías, metro, centros de salud, servicios comunes, autobuses,...'));
echo $this->App->horizontalInput('Inmueble.descripcion_abreviada', 'Descripci&oacute;n abreviada:<br><span>(max. 250 caracteres)</span>', array('rows' => 4));
echo $this->App->horizontalTextarea('Inmueble.video', 'Vídeo:', array('rows' => 3, 'placeholder' => 'pegue el código html'));
?>