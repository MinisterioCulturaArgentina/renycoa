/**
 * Copyright (C) 2008 Marcelo Costanzi - www.dotdev.com.ar
 * 
 * This file is part of Sistema RENYCOA
 *
 * Sistema RENYCOA is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Sistema RENYCOA is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Sistema RENYCOA.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
 
$(document).ready(function() {

	$('#tenedor_id').change(loadTenedor);
	
	loadTenedor();
	
	$('#deposito_id').change(loadDeposito);
	
	loadDeposito();
	
	var paises_ids = $('#paises').val().split(',');
	for (i in paises_ids)
	{
		$('#paises_all option').each(function(){
			if ($(this).val() == paises_ids[i]){
				$(this).attr('selected','selected');
			}
		})
	}
	 
    moveOption('paises_all','paises_selected');
    
    $('#paises_all').dblclick(function(){
    	moveOption('paises_all', 'paises_selected');
    	loadProvincias();
    })
    
    $('#paises_selected').dblclick(function(){
    	moveOption('paises_selected', 'paises_all');
    	loadProvincias();
    })
    
    $('#btn_unselect_pais').click(function(){
      	moveOption('paises_selected', 'paises_all');
      	loadProvincias();
    })
    
    $('#btn_select_pais').click(function(){
      	moveOption('paises_all', 'paises_selected');
      	loadProvincias();
    })
	
	$('#provincia_id').change(loadDepartamentos);
	$('#departamento_id').change(loadLocalidades);
	
	loadProvincias();
	
	$('#form_coleccion').submit(function(){
		$('#hidden_provincia_id').val( $('#provincia_id').val());
		$('#hidden_departamento_id').val( $('#departamento_id').val());
		$('#hidden_localidad_id').val( $('#localidad_id').val());
	})
	
	$("#fecha").datepicker($.extend({}, 
		$.datepicker.regional["es"], { 
    	showStatus: true,
    	dateFormat: "dd/mm/yy"
	})); 
	
	if ($("#fecha").val() =='0000-00-00')
	{
		$("#fecha").val('');
	} 
	
	var tipos_material_ids = $('#tipos_material').val().split(',');
	for (i in tipos_material_ids)
	{
		$('#tipos_material_all option').each(function(){
			if ($(this).val() == tipos_material_ids[i]){
				$(this).attr('selected','selected');
			}
		})
	}
	 
    moveOption('tipos_material_all','tipos_material_selected');
    
    $('#tipos_material_all').dblclick(function(){
    	moveOption('tipos_material_all', 'tipos_material_selected');
    })
    
    $('#tipos_material_selected').dblclick(function(){
    	moveOption('tipos_material_selected', 'tipos_material_all');
    })
    
    $('#btn_unselect_tipo_material').click(function(){
      	moveOption('tipos_material_selected', 'tipos_material_all');
    })
    
    $('#btn_select_tipo_material').click(function(){
      	moveOption('tipos_material_all', 'tipos_material_selected');
    })
    
    $('#form_coleccion').submit(function(){
    	serializeTiposMaterial();
    	serializePaises();
    })
    
    $('#tipo_doc_completador_id').change(getCompletador);
    $('#completador_nro_doc').change(getCompletador);
});


function serializePaises()
{
	$('#paises').attr('value','');
	var i = 0;
	$('#paises_selected option').each(function(){
		i++;
		$('#paises').val($('#paises').val() + $(this).val());
		if ( i <$('#paises_selected option').length)
		{
			$('#paises').val($('#paises').val() + ',');
		}
	})
}

function serializeTiposMaterial()
{
	$('#tipos_material').attr('value','');
	var i = 0;
	$('#tipos_material_selected option').each(function(){
		i++;
		$('#tipos_material').val($('#tipos_material').val() + $(this).val());
		if ( i <$('#tipos_material_selected option').length)
		{
			$('#tipos_material').val($('#tipos_material').val() + ',');
		}
	})
}


function loadTenedor()
{
	$('#tenedor_container').load(getUrl('tenedor','get','id='+$('#tenedor_id').val()));
}

function loadDeposito()
{
	$('#deposito_container').load(getUrl('deposito','get','id='+$('#deposito_id').val()));
}

function loadProvincias()
{
	if ($('#paises_selected OPTION[value="1"]').size() > 0)
	{
		if ($('#provincia_id OPTION').size() <= 1)
		{
			$('#provincia_id').empty();
			$.getJSON(getUrl('provincia','get','format=json&pais_id=1'), function(json) { 
				$('#provincia_id').append('<option label="--Seleccionar--" value="">--Seleccionar--</option>');
				if (json.provincias.length > 0)
				{
					for (var i in json.provincias)
					{
						var o = json.provincias[i];
						var selected = '';
						if ($('#hidden_provincia_id').val() == o.id)
						{
							selected = 'selected="selected"';
						}
						$('#provincia_id').append('<option label="'+o.descripcion+'" value="'+o.id+'" '+selected+'>'+o.descripcion+'</option>');
					} 
					$('#provincia_id').removeAttr('disabled');
				}
				else
				{
					$('#provincia_id').attr('disabled','disabled');
				}
				loadDepartamentos();
			});
		}
	}
	else if ($('#provincia_id OPTION').size() > 1)
	{
		$('#provincia_id').attr('disabled','disabled');
		$('#provincia_id').empty();
		$('#provincia_id').append('<option label="--Seleccionar--" value="">--Seleccionar--</option>');
		loadDepartamentos();
	}
}

function loadDepartamentos()
{
	$('#departamento_id').empty();
	$.getJSON(getUrl('departamento','get','format=json&provincia_id='+$('#provincia_id').val()), function(json) { 
		$('#departamento_id').append('<option label="--Seleccionar--" value="">--Seleccionar--</option>');
		if (json.departamentos.length > 0)
		{
			for (var i in json.departamentos)
			{
				var o = json.departamentos[i];
				var selected = '';
				if ($('#hidden_departamento_id').val() == o.id)
				{
					selected = 'selected="selected"';
				}
				$('#departamento_id').append('<option label="'+o.descripcion+'" value="'+o.id+'" '+selected+'>'+o.descripcion+'</option>');
			}
			$('#departamento_id').removeAttr('disabled');
		}
		else
		{
			$('#departamento_id').attr('disabled','disabled');
		}
		loadLocalidades(); 
	});
}

function loadLocalidades()
{
	$('#localidad_id').empty();
	$.getJSON(getUrl('localidad','get','format=json&departamento_id='+$('#departamento_id').val()), function(json) {
		$('#localidad_id').append('<option label="--Seleccionar--" value="">--Seleccionar--</option>');
		if (json.localidades.length > 0)
		{
			for (var i in json.localidades)
			{
				var o = json.localidades[i];
				var selected = '';
				if ($('#hidden_localidad_id').val() == o.id)
				{
					selected = 'selected="selected"';
				}
				$('#localidad_id').append('<option label="'+o.descripcion+'" value="'+o.id+'" '+selected+'>'+o.descripcion+'</option>');			
			}
			$('#localidad_id').removeAttr('disabled');
		}
		else
		{
			$('#localidad_id').attr('disabled','disabled');
		}
	});
}

function getCompletador()
{
	var tipo_doc_completador_id = $('#tipo_doc_completador_id').val();
	var completador_nro_doc = $('#completador_nro_doc').val();
	
	if (tipo_doc_completador_id != '' && completador_nro_doc != '')
	{
		$.get(getUrl('completador','get','tipo_doc_completador_id='+tipo_doc_completador_id+'&nro_doc='+completador_nro_doc), function(data){
			if (data != '') {
				$('#completado_por').val(data);
			}
		});
	}
}