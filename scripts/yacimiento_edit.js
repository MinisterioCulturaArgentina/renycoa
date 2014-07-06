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
	
	$('IFRAME').load(function(){
		$("IFRAME").contents().find('.fancyimgs').fancybox({ 'hideOnContentClick': true, 'overlayShow' : true });
	});

	$('#pais_id').change(loadPaisDet);
	$('#pais_id').change(loadProvincias);
	
	$('#provincia_id').change(loadDepartamentos);
	$('#departamento_id').change(loadLocalidades);
	
	loadProvincias();
	loadPaisDet();
	

	$('#form_yacimiento').submit(function(){
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
	
	$('#latitud, #longitud').change(function(){
		var val = $(this).val();
		val = val.replace(/\054/,"\056"); // , x .
		val = val.replace(/\272/,"\260"); // � x �
		val = val.replace(/\264/,"\047"); // � x ' 
		val = val.replace(/\047\047/,"\042"); // '' x "
		val =jQuery.trim(val);
		while(val.indexOf("\040\040") != -1)// espaciados
		{
			val = val.replace(/\040\040/,"\040"); 
		}
		val = val.replace(/\040\260/,"\260"); // � x�
		val = val.replace(/\040\047/,"\047"); // � x ' 
		val = val.replace(/\040\042/,"\042"); // '' x "
		$(this).val(val);
	});
	
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
	
	var adscripciones_ids = $('#adscripciones_culturales').val().split(',');
	for (i in adscripciones_ids)
	{
		$('#adscripciones_all option').each(function(){
			if ($(this).val() == adscripciones_ids[i]){
				$(this).attr('selected','selected');
			}
		})
	}
	 
    moveOption('adscripciones_all','adscripciones_selected');
    
    $('#adscripciones_all').dblclick(function(){
    	moveOption('adscripciones_all', 'adscripciones_selected');
    })
    
    $('#adscripciones_selected').dblclick(function(){
    	moveOption('adscripciones_selected', 'adscripciones_all');
    })
    
    $('#btn_unselect_adscripcion').click(function(){
      	moveOption('adscripciones_selected', 'adscripciones_all');
    })
    
    $('#btn_select_adscripcion').click(function(){
      	moveOption('adscripciones_all', 'adscripciones_selected');
    })
    
    $('#form_yacimiento').submit(function(){
    	serializeTiposMaterial();
    	serializeAdscripciones();
    })
    
    $('#tipo_doc_completador_id').change(getCompletador);
    $('#completador_nro_doc').change(getCompletador);
	
});


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


function serializeAdscripciones()
{
	$('#adscripciones_culturales').attr('value','');
	var i = 0;
	$('#adscripciones_selected option').each(function(){
		i++;
		$('#adscripciones_culturales').val($('#adscripciones_culturales').val() + $(this).val());
		if ( i <$('#adscripciones_selected option').length)
		{
			$('#adscripciones_culturales').val($('#adscripciones_culturales').val() + ',');
		}
	})
}


function loadProvincias()
{
	$('#provincia_id').empty();
	$.getJSON(getUrl('provincia','get','format=json&pais_id='+$('#pais_id').val()), function(json) { 
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

function loadPaisDet()
{
	if ($('#pais_id').val() == 1) {
		$('.pais_det').hide();
		$('.pais_spec').show();
	}
	else {
		$('.pais_spec').hide();
		$('.pais_det').show();
	}
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