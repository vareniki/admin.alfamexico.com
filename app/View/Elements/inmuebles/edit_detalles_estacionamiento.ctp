<div class="form-group">
  <label class="control-label col-md-5 col-lg-4 col-sm-4">Detalles:</label>
  <div class="col-md-7 col-lg-8 col-sm-8">
    <?php
    echo $this->Form->checkbox('Garaje.plaza_cubierta', array('value' => 't', 'label' => 'plaza cubierta'));
    echo $this->Form->checkbox('Garaje.plaza_doble', array('value' => 't', 'label' => 'plaza doble'));
    ?>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-5 col-lg-4 col-sm-4">Informaci&oacute;n adicional:</label>
  <div class="col-md-7 col-lg-8 col-sm-8">
    <?php
    echo $this->Form->checkbox('Garaje.con_puerta_automatica', array('value' => 't', 'label' => 'puerta automática'));
    echo $this->Form->checkbox('Garaje.con_camaras_seguridad', array('value' => 't', 'label' => 'cámaras de seguridad'));
    echo $this->Form->checkbox('Garaje.con_personal_seguridad', array('value' => 't', 'label' => 'personal de seguridad'));
    echo $this->Form->checkbox('Garaje.con_alarma', array('value' => 't', 'label' => 'alarma'));
    ?>
  </div>
</div>
<?php
echo $this->App->horizontalSelect('Inmueble.calidad_precio', 'Calidad / precio:', array('' => '') + $calidadPrecio, array('size' => '3'));

echo $this->App->horizontalInput('Inmueble.descripcion', 'Descripci&oacute;n completa:', array('rows' => 6,
  'placeholder' => 'recuerda utilizar palabras clave como: zona, colegios, guarderías, metro, centros de salud, servicios comunes, autobuses,...', 'labelClass' => 'obligat'));
echo $this->App->horizontalInput('Inmueble.descripcion_abreviada', 'Descripci&oacute;n abreviada:<br><span>(max. 250 caracteres)</span>', array('rows' => 4, 'maxlength' => 250));

echo $this->App->horizontalTextarea('Inmueble.video', 'Vídeos:', array('rows' => 3, 'placeholder' => 'pegue la URL o URLs separadas por salto de línea'));
?>