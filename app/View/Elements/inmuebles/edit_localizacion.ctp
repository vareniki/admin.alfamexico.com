<?php
$this->start('header');
?>
<script type="text/javascript">
    var localizaciones = null;

    /**
     *
     * @param loc
     * @returns {Object}
     */
    function convertMap2Info(loc) {
        var item = new Object();

        item.calle = "";
        item.numero = "";
        item.cp = "";
        item.poblacion = "";
        item.provincia = "";
        item.direccion = "";
        item.provincia_id = "";

        for (var j = 0; j < loc.address_components.length; j++) {
            var obj = loc.address_components[j];
            var type = obj['types'][0];
            switch (type) {
                case 'street_number':
                    item.numero = loc.address_components[j]['long_name'];
                    break;
                case 'route':
                    item.calle = loc.address_components[j]['long_name'];
                    break;
                case 'postal_code':
                    item.cp = loc.address_components[j]['long_name'];
                    item.provincia_id = item.cp.substr(0, 2);
                    break;
                case 'locality':
                    item.poblacion = loc.address_components[j]['long_name'];
                    break;
                case 'administrative_area_level_1':
                    if (item.provincia == '') {
                        item.provincia = loc.address_components[j]['long_name'];
                    }
                    break;
                case 'administrative_area_level_2':
                    item.provincia = loc.address_components[j]['long_name'];

                    break;
            }
        }
        item.lat = loc.geometry.location.lat;
        item.lng = loc.geometry.location.lng;
        item.direccion = loc.formatted_address;

        return item;
    }

    /**
     *
     * @param direccion
     * @param callback
     */
    var getMapAddresses = function (direccion, callback) {

        $.ajax("<?php echo $this->base; ?>/ajax/getAddresses/?lugar=" + direccion, {
            dataType: 'json',
            success: function (data) {
                $.each(data.results, function (i, obj) {
                    callback(convertMap2Info(obj));
                });
            }
        });
    }

    /**
     *
     * @returns {string}
     */
    function maps_get_html_dialog() {
        var result = '<form id="buscarMapa_form" class="form-inline" action="#">';
        result += '<div class="input-group">';
        result += '<input type="text" class="form-control col-xs-12" id="buscarMapa_input" placeholder="ej.: tankah 65, cancun" autofocus>';
        result += '<span class="input-group-btn"><button id="buscarMapa_button" class="btn btn-default" type="button">Buscar</button></span>';
        result += '</div>';
        result += '</form><br>';
        result += '<div id="buscarMapa_results"></div>';

        return result;
    }

    /**
     *
     * @param direccion
     * @param pais
     */
    function maps_begin_search(direccion, pais) {
        if (direccion.length < 3) {
            return;
        }
        direccion += "," + pais;

        localizaciones = Array();

        $("#buscarMapa_results").empty();
        getMapAddresses(direccion, function (item) {
            localizaciones[localizaciones.length] = item;
            var html = '<p><a class="bootbox-close-button" href="#" rel="' + localizaciones.length + '">' + item.direccion + '</a></p>';
            $("#buscarMapa_results").append(html);
        });

    }

    function maps_dialog(properties, callback) {
        var dialog_obj = bootbox.dialog({
            message: maps_get_html_dialog(properties.pais),
            title: properties.title + ' en ' + properties.pais,
            buttons: {
            }
        });

        $("#buscarMapa_input").on("keyup", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                maps_begin_search(this.value, properties.pais);
            }
        }).on("keypress", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });

        $("#buscarMapa_button").on("click", function () {
            maps_begin_search($("#buscarMapa_input").val(), properties.pais);
        });

        $("#buscarMapa_results").on("click", "a", function () {
            var id = parseInt(this.rel) - 1;
            callback(localizaciones[id]);

            return true;
        });


        $(dialog_obj).on('shown.bs.modal', function () {
            $("#buscarMapa_input").focus();
        });

        return dialog_obj;
    }

</script>

<script type="text/javascript">

    var map;
    function mapCallBack() {
        map = new Microsoft.Maps.Map('#mapResult', {
            disableZooming: true,
            mapTypeId: Microsoft.Maps.MapTypeId.road, zoom: 15 });

      <?php if (!empty($info['Inmueble']['coord_x']) && !empty($info['Inmueble']['coord_y'])): ?>
        var center = new Microsoft.Maps.Location(<?php echo $info['Inmueble']['coord_x'] ?>, <?php echo $info['Inmueble']['coord_y'] ?>);
        map.setView({center: center })

        var pins = [];
        pins.push(new Microsoft.Maps.Pushpin(center, { icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' }));
        map.entities.push(pins);

        $("#mapResult").css('height', '200px');
      <?php endif; ?>
    }

    function localizarInmuebleResult(item) {

        map.entities.clear();
        map.setView({ bounds: item.bestView });
        map.entities.push(new Microsoft.Maps.Pushpin(item.location));

        $("#InmuebleCoordX").val(item.location.latitude);
        $("#InmuebleCoordY").val(item.location.longitude);

        $("#InmuebleCodigoPostal").val(item.address.postalCode);

        if (item.address.addressLine != null) {
            var streetNumber = item.address.addressLine.split(',');
            $("#InmuebleNombreCalle").val(streetNumber[0]);
            if (streetNumber.length > 1) {
                $("#InmuebleNumeroCalle").val(parseInt(streetNumber[1]));
            }
        }
    }

    $(document).ready(function() {
	  $("#mapResult").empty();

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

      $("#localizarInmueble").on("click", function() {
          maps_dialog({
              title: 'Geolocalizar propiedad',
              pais: $("#InmueblePaisId option:selected").text()

          }, function(item) {

              /*
               * Al pinchar aplicar se ejecuta esta función
               */
              $("#InmuebleCoordX").val(item.lat);
              $("#InmuebleCoordY").val(item.lng);

              $("#InmuebleCodigoPostal").val(item.cp);

              $("#InmuebleNombreCalle").val(item.direccion);
              $("#InmuebleNumeroCalle").val(item.numero);


              if (item.lat != 0 && item.lng != 0) {

                  var center = new Microsoft.Maps.Location(item.lat, item.lng);
                  map.setView({center: center })

                  var pins = [];
                  pins.push(new Microsoft.Maps.Pushpin(center, { icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' }));
                  map.entities.push(pins);

                  $("#mapResult").css('height', '200px');
              }

          });
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
<div class="form-group" style="margin-bottom: 0;">
    <div class="col-xs-5 col-lg-4 col-sm-4 text-right">&nbsp;</div>
    <div class="controls col-xs-7 col-lg-8 col-sm-8">
        <p class="text-info">Geolocaliza el inmueble y los datos se rellenan autom&aacute;ticamente</p>
    </div>
</div>
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
	if (isset($info['Inmueble']['tipo_inmueble_id'])) {
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
    }

	?>
<?php endif; ?>
<?php $this->end(); ?>
