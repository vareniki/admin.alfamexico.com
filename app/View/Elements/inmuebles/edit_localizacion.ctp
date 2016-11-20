<?php
$this->start('header');
?>
<script type="text/javascript">

  $(document).ready(function() {
	  $("#mapResult").empty();

	  <?php if (!empty($info['Inmueble']['coord_x']) && !empty($info['Inmueble']['coord_y'])): ?>
		  var latLng = "<?php echo $info['Inmueble']['coord_x'] . ',' . $info['Inmueble']['coord_y']; ?>";
		  $("#mapResult").append(getMap({center: latLng, markers: latLng}));
	  <?php endif; ?>

    $("#localizarInmueble").on("click", function() {
      maps_dialog({
        title: 'Geolocalizar propiedad',
        pais: $("#InmueblePaisId option:selected").text()
      }, function(item) {
        /*
         * Al pinchar aplicar se ejecuta esta función
         */
	      $("#mapResult").empty();

	      if (item.lat != 0 && item.lng != 0) {
		      var latLng = item.lat + "," + item.lng;
		      $("#mapResult").append(getMap({center: latLng, markers: latLng}));
	      }

        $("#InmuebleCoordX").val(item.lat);
        $("#InmuebleCoordY").val(item.lng);

	      if ($("#InmuebleCodigoPostal").val() == '') {
		      $("#InmuebleCodigoPostal").val(item.cp);
	      }

        if ($("#InmuebleNombreCalle").val() == '') {
	        $("#InmuebleNombreCalle").val(item.calle);
	        $("#InmuebleNumeroCalle").val(item.numero);
        }

      });
    });

	  $("#InmuebleProvinciaId").on("change", function() {

		  var $this = $(this);
		  var text = '';
		  if ($this.value != '') {
			  text = $this.find('option:selected').text();
		  }
		  $("#InmuebleProvincia").val(text);

		  actualizarPoblaciones();
	  });

	  function actualizarPoblaciones() {

		  $("#InmueblePoblacionId").html('<option value="">(seleccionar municipio)</option>');
		  $("#InmueblePoblacion").val('');
		  $("#InmuebleCodigoPostal").val('');
		  $("#InmuebleCiudad").find('option').remove();
		  $("#InmuebleZona").find('option').remove();

		  var provId = $("#InmuebleProvinciaId").val();
		  if (provId == '') {
			  return;
		  }

		  $.ajax("<?php echo $this->base; ?>/ajax/getPoblacionesProvincia/" + provId, {
			  dataType: 'json',
			  success: function(data) {
				  $.each(data, function(i, obj) {
					  $("#InmueblePoblacionId").append('<option value="' + obj.id + '">' + obj.description + '</option>');
				  });
			  }
		  });
	  }

	  $("#InmueblePoblacionId").on("change", function() {

		  var $this = $(this);
		  var text = '';
		  if ($this.value != '') {
			  text = $this.find('option:selected').text();
		  }
		  $("#InmueblePoblacion").val(text);

		  $("#InmuebleCiudad").find('option').remove();
		  $("#InmuebleZona").find('option').remove();

		  actualizarCiudadesZonas();
	  });

	  function actualizarCiudadesZonas() {

		  var poblId = $("#InmueblePoblacionId").val();
		  if (poblId == '' || provId == '') {
			  return;
		  }
		  var provId = $("#InmuebleProvinciaId").val();

		  // Actualiza ciudades
		  $.ajax("<?php echo $this->base; ?>/ajax/getCiudadesPoblacion/" + provId + "/" + poblId, {
			  dataType: 'json',
			  success: function(data) {
				  $.each(data, function(i, obj) {
					  $("#InmuebleCiudad").append('<option value="' + obj.description + '">' + obj.description + '</option>');
				  });
			  }
		  });

		  // Actualiza zonas / colonias
		  $.ajax("<?php echo $this->base; ?>/ajax/getZonasPoblacion/" + provId + "/" + poblId, {
			  dataType: 'json',
			  success: function(data) {
				  $.each(data, function(i, obj) {
					  $("#InmuebleZona").append('<option value="' + obj.description + '">' + obj.description + '</option>');
				  });
			  }
		  });

	  }

	  $("#buscarRC_btn").on("click", function () {
		  $("#buscarRC_modal").modal();
	  });

  });
</script>
<?php
$this->end();
echo $this->App->horizontalSelect('Inmueble.pais_id', '<span>[*]</span> Pa&iacute;s:', $paises, array('required' => 'required', 'labelClass' => 'obligat'));

echo $this->Form->hidden('Inmueble.poblacion');
echo $this->Form->hidden('Inmueble.provincia');

echo $this->App->horizontalSelect('Inmueble.provincia_id', '<span>[*]</span> Estado:', $provincias_ids, array('labelClass' => 'obligat'));
echo $this->App->horizontalSelect('Inmueble.poblacion_id', '<span>[*]</span> Municipio:', $poblaciones_ids, array('labelClass' => 'obligat'));

echo $this->App->horizontalSelect('Inmueble.ciudad', 'Ciudad:', $ciudades_ids);
echo $this->App->horizontalSelect('Inmueble.zona', '<span>[*]</span> Colonia:', $zonas_ids, array('labelClass' => 'obligat'));
echo $this->App->horizontalInput('Inmueble.codigo_postal', 'Código postal:', array('labelClass' => 'obligat'));
echo $this->App->horizontalInput('Inmueble.nombre_calle', 'Calle:', array('labelClass' => 'obligat'));
echo $this->App->horizontalInput('Inmueble.numero_calle', 'Número:', array('min' => 0, 'labelClass' => 'obligat'));

if ($info['Inmueble']['estado_inmueble_id'] >= '02') {
	$read_only =  array('readonly' => 'readonly');
	$click_rc = array();
} else {
	$read_only =  array();
	$click_rc = array();
}
?>
<div class="form-group">
	<label class="col-xs-5 col-lg-4 col-sm-4"></label>
	<div class="controls col-xs-7 col-lg-8 col-sm-8">
		<button class="btn btn-default btn-sm" type="button" id="localizarInmueble">Geolocalizar inmueble...</button>
	</div>
</div>
<?php
echo $this->App->horizontalInput('Inmueble.coord_x', '<span>[*]</span> Latitud:', array('labelClass' => 'obligat'));
echo $this->App->horizontalInput('Inmueble.coord_y', '<span>[*]</span> Longitud:', array('labelClass' => 'obligat'));
echo '<hr>';
?>
<div id="mapResult"></div>
<br>
<?php
if (isset($tipoInmueble)) {
  echo $this->element("inmuebles/edit_localizacion_$tipoInmueble");
}
?>
<?php $this->start('dialogs'); ?>
<?php if (empty($read_only)):

	$bloque = '';
	$escalera = '';
	$piso = '';
	$puerta = '';
	switch ($info['Inmueble']['tipo_inmueble_id']) {
		case '01':
			$bloque = $info['Piso']['bloque'];
			$escalera = $info['Piso']['escalera'];
			$piso = $info['Piso']['piso'];
			$puerta = $info['Piso']['puerta'];
			break;
		case '02':
			break;
		case '03':
			$bloque = $info['Local']['bloque'];
			break;
		case '04':
			$bloque = $info['Oficina']['bloque'];
			$escalera = $info['Oficina']['escalera'];
			$piso = $info['Oficina']['piso'];
			$puerta = $info['Oficina']['puerta'];
			break;
		case '05':
			break;
		case '06':
			break;
		case '07':
			break;
		case '08':
			break;
	}
	?>
<?php endif; ?>
<?php $this->end(); ?>
