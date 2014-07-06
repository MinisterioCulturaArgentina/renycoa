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

	$('#pais_id').change(loadProvincias);
	$('#provincia_id').change(loadDepartamentos);
	$('#departamento_id').change(loadLocalidades);
	
	loadProvincias();
	
	$('#form_deposito').submit(function(){
		$('#hidden_provincia_id').val( $('#provincia_id').val());
		$('#hidden_departamento_id').val( $('#departamento_id').val());
		$('#hidden_localidad_id').val( $('#localidad_id').val());
	})
});


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