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

class Yacimiento extends PersistentModel 
{
	
	var $id;
	var $sigla;
	var $denominacion_sitio;
	var $pais_id;
	var $provincia_id;
	var $localidad_id;
	var $departamento_id;
	var $pais_detalle;
	var $lugar_paraje;
	var $fur;
	var $altitud;
	var $longitud;
	var $latitud;
	var $datum;
	var $carta_igm;
	var $plano_catastral;
	var $croquis;
	var $tipo_sitio_id;
	var $plano_sitio;
	var $superficie;
	var $intervenciones;
	var $intervenciones_desc;
	var $danos_antropicos;
	var $danos_antropicos_desc;
	var $danos_naturales;
	var $danos_naturales_desc;
	var $observaciones;
	var $provisorio;
	var $riesgo_impacto_cultural_id;
	var $riesgo_impacto_ambiental_id; 
	var $dominio_id;
	var $completado_por;
	var $tipo_doc_completador_id;
  var $completador_nro_doc;
	var $fecha;
	
	var $_data_table = 'yacimiento';
	
	
	public function __construct()
	{
		parent::__construct();
		$this->_date_fields = array('fecha'); 
	}
	
	public function __toString()
	{
		return $this->sigla;
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM yacimiento WHERE ISNULL(deleted_at) ORDER BY sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromPais($pais_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'	SELECT * FROM yacimiento WHERE pais_id = '."'".$db->escape($pais_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithInvestigacion($in_pairs = false)
	{
		$sql =	'	SELECT yacimiento.* 
					FROM 
						yacimiento 
						INNER JOIN investigacion ON investigacion.yacimiento_id = yacimiento.id
					WHERE 
						ISNULL(yacimiento.deleted_at) AND
						ISNULL(investigacion.deleted_at)
					ORDER BY yacimiento.sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithLote($in_pairs = false)
	{
		$sql =	'	SELECT yacimiento.* 
					FROM 
						yacimiento 
						INNER JOIN lote ON lote.yacimiento_id = yacimiento.id
					WHERE 
						ISNULL(yacimiento.deleted_at) AND
						ISNULL(lote.deleted_at)
					ORDER BY yacimiento.sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithObjeto($in_pairs = false)
	{
		$sql =	'	SELECT yacimiento.* 
					FROM 
						yacimiento 
						INNER JOIN objeto ON objeto.yacimiento_id = yacimiento.id
					WHERE 
						ISNULL(yacimiento.deleted_at) AND
						ISNULL(objeto.deleted_at)
					ORDER BY yacimiento.sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM yacimiento WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function delete($instance)
	{
		YacimientoAdscripcionCultural::deleteAllFromYacimiento($instance->id);
		YacimientoFotografia::deleteAllFromYacimiento($instance->id);
		YacimientoFuncionInferida::deleteAllFromYacimiento($instance->id);
		YacimientoLegislacionProteccion::deleteAllFromYacimiento($instance->id);
		YacimientoRepresentacionRupestre::deleteAllFromYacimiento($instance->id);
		YacimientoTareaRealizada::deleteAllFromYacimiento($instance->id);
		YacimientoTipoMaterial::deleteAllFromYacimiento($instance->id);
		Investigacion::deleteAllFromYacimiento($instance->id);
		Fechado::deleteAllFromYacimiento($instance->id);
		CitaBibliografica::deleteAllFromYacimiento($instance->id);
		//TODO: Borrar objetos y lotes
		parent::delete($instance);
	}
}