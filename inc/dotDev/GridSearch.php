<?php
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

class GridSearch
{
	function search($sql, $from, $primary_id, $arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false, $groupby = "")
	{	
		$db = Zend_Registry::get('db');
		$where = "";
		$first = true;
		foreach((array)$arrayParameters as $key => $value)
		{
			
			$aTable = explode(":",$key);
		
			$sTable = "";
			$sFieldAlias = "";
			if(count($aTable) > 1)
			{
				$sTable = $aTable[0];
				$sFieldAlias = $aTable[1];
			}
			else
			{
				$sFieldAlias = $key;
			}
			
			$aField = explode("_",$sFieldAlias);
			$sField = "";
			
			for($i = 1 ; $i < count($aField); $i++)
			{
				if($sField == "")
				{
					$sField = $aField[$i];
				}	
				else
				{
					$sField .= "_".$aField[$i];
				}
			}
			
			if($sTable != "")
			{
				$sFieldExt = $sTable.".".$sField;
			}
			else
			{
				$sFieldExt = $sField;
			}
			
			if ($first)
				$where = ' WHERE '; 
			else
				$where .= ' AND ';


			if ( ! (is_array($value)) )
			{
				$where .= "$sFieldExt LIKE '%".$db->escape($value)."%'";
			}
			else
			{
				if( strtoupper($value['OPERATOR']) == 'IN' )
				{
					$where .= "$sFieldExt ".$value['OPERATOR']." ".$db->escape($value['VALUE']);
				}
				elseif ( strtoupper($value['OPERATOR']) == 'ISNULL' )
				{
					$where .= "ISNULL($sFieldExt)";
				}
				else
				{
					$where .= "$sFieldExt ".$value['OPERATOR']." '".$db->escape($value['VALUE'])."'";
				}
			}
			$first = false;
			
		}
		
		if($order != "")
		{
			$aTable = explode(":",$order);
		
			$sTable = "";
			$sFieldAlias = "";
			if(count($aTable) > 1)
			{
				$sTable = $aTable[0];
				$sFieldAlias = $aTable[1];
			}
			else
			{
				$sFieldAlias = $order;
			}
			
			$aField = explode("_",$sFieldAlias);
			$sField = "";
			
			for($i = 1 ; $i < count($aField); $i++)
			{
				if($sField == "")
				{
					$sField = $aField[$i];
				}	
				else
				{
					$sField .= "_".$aField[$i];
				}
			}
			
			if($sTable != "")
			{
				$sFieldExt = $sTable.".".$sField;
			}
			else
			{
				$sFieldExt = $sField;
			}
		}
		
		if($limit != "" and $limit != '0')
		{	
			if($quantity != "" and $quantity != '0')
			{
				$quantity = " $quantity";
			}
			else
			{
				$quantity = 0;
			}	
		}
							   
		//count the number of records
		$select = "SELECT
				   $sql $from
			       $where $groupby";
		
		$rs = $db->get_results($select, 'ARRAY_A');
		
		$totalcount = count($rs);
		
		if($limit != "" and $limit != "0")
		{
			$result = count($rs)/ $limit;
		
			$tot = count($rs);
			$pagecount = 1;
			while($tot - $limit > 0)
			{
				$pagecount = $pagecount + 1;
				$tot = $tot - $limit;
			}
			
			$lastpagezise = $tot;
		}
		else
		{
			$pagecount = 1;	
		}
		
		if($count == true)
		{	
			return $pagecount;
		}
		else
		{
			if($limit != "" and $limit != "0")
			{
				$select = "SELECT 
						   $sql $from
						   $where $groupby
						   ORDER BY $sFieldExt $sense
						   LIMIT $quantity, $limit
						   ";
			}
			else
			{
				$select = "SELECT
						   $sql $from
						   $where $groupby
						   ORDER BY $sFieldExt $sense";
			}
			
			if(isset($_GET['debug']))
			{
				superecho($select);
			}
			$rs = $db->get_results($select,'ARRAY_A');
			
			return $rs;
		}
	}
	
	function applyOperators($arrayParameters, $strictParameters)
	{
		foreach ($strictParameters as $param)
		{
			if (isset($arrayParameters[$param]))
				$arrayParameters[$param] = array ('OPERATOR' => '=', 'VALUE' => $arrayParameters[$param]);
		}
		return $arrayParameters;
	}
	
	
	
	function tenedorSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "tenedor.id";
		$select_fields = "	tenedor.id as tenedor_id,
						   	tenedor.apellido  as tenedor_apellido,
						   	tenedor.nombres as tenedor_nombres,
						   	tenedor.nro_doc as tenedor_nro_doc,
						  	tenedor.domicilio as tenedor_domicilio,
							tenedor.telefono as tenedor_telefono,
							tenedor.fax as tenedor_fax,
							tenedor.email as tenedor_email,
							tipo_doc.id as tipodoc_id,
							tipo_doc.descripcion as tipodoc_descripcion,
							pais.id as pais_id,
							pais.descripcion as pais_descripcion,
							provincia.id as provincia_id,
							provincia.descripcion as provincia_descripcion,
							localidad.id as localidad_id,
							localidad.descripcion as localidad_descripcion,
							departamento.id as departamento_id,
							departamento.descripcion as departamento_descripcion,
							coleccion.id as coleccion_id, 
							GROUP_CONCAT(coleccion.sigla SEPARATOR ', ') as tenedor_colecciones";
		
		
		
		$select_from = "FROM tenedor
						LEFT JOIN tipo_doc ON tipo_doc.id = tenedor.tipo_doc_id
						LEFT JOIN pais ON pais.id = tenedor.pais_id
						LEFT JOIN provincia ON provincia.id = tenedor.provincia_id
						LEFT JOIN localidad ON localidad.id = tenedor.localidad_id
						LEFT JOIN departamento ON departamento.id = tenedor.departamento_id
						LEFT JOIN coleccion ON coleccion.tenedor_id = tenedor.id";
		
		$select_groupby = "GROUP BY tenedor.id";
		
		$arrayParameters["tenedor:tenedor_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		$arrayParameters["coleccion:coleccion_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'tipo_doc:tipodoc_id', 
			'pais:pais_id', 
			'provincia:provincia_id', 
			'localidad:localidad_id', 
			'departamento:departamento_id',
			'coleccion:coleccion_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count, $select_groupby);
		
		return $rs;
	}
	
	function depositoSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "deposito.id";
		$select_fields = "	deposito.id as deposito_id,
						   	deposito.nombre as deposito_nombre,
						  	deposito.domicilio as deposito_domicilio,
							deposito.telefono as deposito_telefono,
							deposito.fax as deposito_fax,
							deposito.email as deposito_email,
							pais.id as pais_id,
							pais.descripcion as pais_descripcion,
							provincia.id as provincia_id,
							provincia.descripcion as provincia_descripcion,
							localidad.id as localidad_id,
							localidad.descripcion as localidad_descripcion,
							departamento.id as departamento_id,
							departamento.descripcion as departamento_descripcion";	
		
		$select_from = "FROM deposito
						LEFT JOIN pais ON pais.id = deposito.pais_id
						LEFT JOIN provincia ON provincia.id = deposito.provincia_id
						LEFT JOIN localidad ON localidad.id = deposito.localidad_id
						LEFT JOIN departamento ON departamento.id = deposito.departamento_id";
		
		$arrayParameters["deposito:deposito_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'tipo_doc:tipodoc_id', 
			'pais:pais_id', 
			'provincia:provincia_id', 
			'localidad:localidad_id', 
			'departamento:departamento_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function coleccionSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "coleccion.id";
		$select_fields = "	coleccion.id as coleccion_id,
						   	coleccion.sigla as coleccion_sigla,
						  	coleccion.nombre as coleccion_nombre,
							coleccion.sitio_arqueologico as coleccion_sitio_arqueologico,
							coleccion.completado_por as coleccion_completado_por,
							coleccion.observaciones as coleccion_observaciones,
							tenedor.id  as tenedor_id,
							tenedor.apellido as tenedor_apellido,
							CONCAT(tenedor.apellido,', ',tenedor.nombres) as tenedor_nombrecompleto,
							deposito.id  as deposito_id,
							deposito.nombre as deposito_nombre,
							provincia.id as provincia_id,
							provincia.descripcion as provincia_descripcion";	
		
		$select_from = "FROM coleccion
						INNER JOIN tenedor ON tenedor.id = coleccion.tenedor_id
						LEFT JOIN deposito ON deposito.id = coleccion.deposito_id
						LEFT JOIN provincia ON provincia.id = coleccion.provincia_id";
		
		$arrayParameters["coleccion:coleccion_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'coleccion:coleccion_id', 
			'tenedor:tenedor_id', 
			'deposito:deposito_id',
			'provincia:provincia_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function yacimientoSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "yacimiento.id";
		$select_fields = "	yacimiento.id as yacimiento_id,
						   	yacimiento.sigla as yacimiento_sigla,
						  	yacimiento.denominacion_sitio as yacimiento_denominacion_sitio,
						  	yacimiento.completado_por as yacimiento_completado_por,
						  	yacimiento.fecha as yacimiento_fecha,
							pais.id as pais_id,
							pais.descripcion as pais_descripcion,
							provincia.id as provincia_id,
							provincia.descripcion as provincia_descripcion, 
							adscripcion_cultural.id as adscripcioncultural_id,
							GROUP_CONCAT(adscripcion_cultural.nombre SEPARATOR ', ') as yacimiento_adscripciones_culturales";	
		
		$select_from = "FROM yacimiento
						INNER JOIN pais ON pais.id = yacimiento.pais_id
						LEFT JOIN provincia ON provincia.id = yacimiento.provincia_id
						LEFT JOIN yacimiento_adscripcion_cultural ON yacimiento_adscripcion_cultural.yacimiento_id = yacimiento.id
						LEFT JOIN adscripcion_cultural ON adscripcion_cultural.id = yacimiento_adscripcion_cultural.adscripcion_cultural_id";
		
		$arrayParameters["yacimiento:coleccion_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		$arrayParameters["yacimiento_adscripcion_cultural:coleccion_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		$arrayParameters["adscripcion_cultural:coleccion_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');				
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'yacimiento:yacimiento_id', 
			'pais:pais_id', 
			'provincia:provincia_id',
			'adscripcion_cultural_id:adscripcioncultural_id'));
		
		$select_groupby = "GROUP BY yacimiento.id";
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count, $select_groupby);
		
		return $rs;
	}
	
	
	function loteSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "lote.id";
		$select_fields = "	lote.id as lote_id,
						   	lote.sigla as lote_sigla,
						  	lote.nombre as lote_nombre,
						  	lote.cantidad_objetos as lote_cantidad_objetos,
						  	lote.descripcion as lote_descripcion,
							lote.completado_por as lote_completado_por,
							coleccion.id  as coleccion_id,
							coleccion.sigla as coleccion_sigla,
							coleccion.nombre as coleccion_nombre,
							tipo_material.id  as tipomaterial_id,
							tipo_material.descripcion as tipomaterial_descripcion,
							yacimiento.id  as yacimiento_id,
							yacimiento.sigla as yacimiento_sigla";	
		
		$select_from = "FROM lote
						INNER JOIN coleccion ON coleccion.id = lote.coleccion_id
						LEFT JOIN tipo_material ON tipo_material.id = lote.tipo_material_id
						LEFT JOIN yacimiento ON yacimiento.id = lote.yacimiento_id";
		
		$arrayParameters["lote:lote_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'yacimiento:yacimiento_id', 
			'coleccion:coleccion_id',
			'tipo_material:tipomaterial_id',
			'lote:lote_cantidad_objetos'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function objetoSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "objeto.id";
		$select_fields = "	objeto.id as objeto_id,
						   	objeto.sigla as objeto_sigla,
						   	objeto.nombre as objeto_nombre,
							objeto.completado_por as objeto_completado_por,
							coleccion.id  as coleccion_id,
							coleccion.sigla as coleccion_sigla,
							coleccion.nombre as coleccion_nombre,
							yacimiento.id  as yacimiento_id,
							yacimiento.sigla as yacimiento_sigla,
							tipo_material.id  as tipomaterial_id,
							tipo_material.descripcion as tipomaterial_descripcion,
							adscripcion_cultural.id  as adscripcion_cultural_id,
							adscripcion_cultural.nombre as adscripcioncultural_nombre";	
		
		$select_from = "FROM objeto
						LEFT JOIN coleccion ON coleccion.id = objeto.coleccion_id
						LEFT JOIN yacimiento ON yacimiento.id = objeto.yacimiento_id
						LEFT JOIN tipo_material ON tipo_material.id = objeto.tipo_material_id
						LEFT JOIN adscripcion_cultural ON adscripcion_cultural.id = objeto.adscripcion_cultural_id";
		
		$arrayParameters["objeto:objeto_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'yacimiento:yacimiento_id', 
			'coleccion:coleccion_id',
			'tipo_material:tipomaterial_id',
			'adscripcion_cultural:adscripcion_cultural_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}	 
	

	function investigacionSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "investigacion.id";
		$select_fields = "	investigacion.id as investigacion_id,
						   	investigacion.investigadores as investigacion_investigadores,
						  	investigacion.proyecto as investigacion_proyecto,
							investigacion.ano_inicio as investigacion_ano_inicio,
							investigacion.ano_fin as investigacion_ano_fin,
							investigacion.institucion_interviniente as investigacion_institucion_interviniente,
							investigacion.institucion_depositaria as investigacion_institucion_depositaria,
							yacimiento.id as yacimiento_id,
							yacimiento.sigla as yacimiento_sigla";	
				
		$select_from = "FROM investigacion
						INNER JOIN yacimiento ON yacimiento.id = investigacion.yacimiento_id";
		
		$arrayParameters["investigacion:investigacion_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		$arrayParameters["yacimiento:yacimiento_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'yacimiento:yacimiento_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function cita_bibliograficaSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "citabibliografica.id";
		$select_fields = "	cita_bibliografica.id as citabibliografica_id,
						   	cita_bibliografica.autores as citabibliografica_autores,
						  	cita_bibliografica.cita as citabibliografica_cita,
							cita_bibliografica.ano as citabibliografica_ano,
							yacimiento.id as yacimiento_id,
							yacimiento.sigla as yacimiento_sigla";	
				
		$select_from = "FROM cita_bibliografica
						INNER JOIN yacimiento ON yacimiento.id = cita_bibliografica.yacimiento_id";
		
		$arrayParameters["cita_bibliografica:citabibliografica_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		$arrayParameters["yacimiento:yacimiento_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'yacimiento:yacimiento_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function fechadoSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "fechado.id";
		$select_fields = "	fechado.id as fechado_id,
						   	fechado.cod_laboratorio as fechado_cod_laboratorio,
						  	CONCAT(fechado.fecha_c14,' ± ',fechado.fecha_c14_desvio,' AP') as fechado_fecha_c14,
							CONCAT(fechado.fecha_calibrada_2s,' ',tf_2s.descripcion,' - ',fecha_calibrada_2s_desvio,' ',tfdes_2s.descripcion) as fechado_fecha_calibrada_2s,
							CONCAT(fechado.fecha_calendarica,' ',tf_calendarica.descripcion) as fechado_fecha_calendarica,
							yacimiento.id as yacimiento_id,
							yacimiento.sigla as yacimiento_sigla";	
				
		$select_from = "FROM fechado
						INNER JOIN yacimiento ON yacimiento.id = fechado.yacimiento_id
						INNER JOIN tipo_fecha as tf_2s ON tf_2s.id = fechado.fecha_calibrada_2s_tipo_fecha_id
						INNER JOIN tipo_fecha as tfdes_2s ON tfdes_2s.id = fechado.fecha_calibrada_2s_desvio_tipo_fecha_id
						INNER JOIN tipo_fecha as tf_calendarica ON tf_calendarica.id = fechado.fecha_calendarica_tipo_fecha_id";
		
		$arrayParameters["fechado:fechado_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		$arrayParameters["yacimiento:yacimiento_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'yacimiento:yacimiento_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function mensajeSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "mensaje.id";
		$select_fields = "	mensaje.id as mensaje_id,
						   	mensaje.mensaje as mensaje_mensaje,
						  	mensaje.administrador as mensaje_administrador,
							mensaje.created_at as mensaje_created_at,
							usuario.id as usuario_id,
							usuario.usuario as usuario_usuario,
							usuario.apellido as usuario_apellido,
							usuario.nombre as usuario_nombre";	
				
		$select_from = "FROM mensaje
						INNER JOIN usuario ON usuario.id = mensaje.usuario_id";
		
		$arrayParameters["mensaje:mensaje_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');		
		$arrayParameters["usuario:usuario_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'usuario:usuario_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function usuarioSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "usuario.id";
		$select_fields = "	usuario.id as usuario_id,
						   	usuario.usuario as usuario_usuario,
						   	usuario.nombre as usuario_nombre,
						   	usuario.apellido as usuario_apellido,
						   	rol.id as rol_id,
						   	rol.descripcion as rol_descripcion";	
				
		$select_from = "FROM usuario
						INNER JOIN rol ON rol.id = usuario.rol_id";
				
		$arrayParameters["usuario:usuario_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'usuario:usuario_id',
			'rol:rol_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function adscripcion_culturalSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "adscripcion_cultural.id";
		$select_fields = "	adscripcion_cultural.id as adscripcioncultural_id,
						   	adscripcion_cultural.nombre as adscripcioncultural_nombre,
						   	adscripcion_cultural.descripcion as adscripcioncultural_descripcion";	
				
		$select_from = "FROM adscripcion_cultural";
				
		$arrayParameters["adscripcion_cultural:adscripcioncultural_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'adscripcion_cultural:adscripcioncultural_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function tipo_materialSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "tipo_material.id";
		$select_fields = "	tipo_material.id as tipomaterial_id,
						   	tipo_material.descripcion as tipomaterial_descripcion";	
				
		$select_from = "FROM tipo_material";
				
		$arrayParameters["tipo_material:tipomaterial_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'tipo_material:tipomaterial_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
	function paisSearch($arrayParameters = "", $order = "", $sense = "", $quantity = "", $limit = "", $count = false)
	{	
		$primary_id = "pais.id";
		$select_fields = "	pais.id as pais_id,
						   	pais.descripcion as pais_descripcion";	
				
		$select_from = "FROM pais";
				
		$arrayParameters["pais:pais_deleted_at"] = array ('OPERATOR' => 'ISNULL', 'VALUE' => '');
		
		$arrayParameters = $this->applyOperators($arrayParameters, array(
			'pais:pais_id'));
		
		$rs = $this->search($select_fields, $select_from, $primary_id, $arrayParameters, $order, $sense, $quantity, $limit, $count);
		
		return $rs;
	}
	
}
?>