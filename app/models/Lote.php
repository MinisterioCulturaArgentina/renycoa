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

class Lote extends PersistentModel 
{
	
	var $id;
	var $nombre;
	var $sigla;
	var $tipo_material_id;
	var $yacimiento_id;
	var $descripcion;
	var $coleccion_id;
	var $cantidad_objetos;
	var $completado_por;
	var $tipo_doc_completador_id;
  var $completador_nro_doc;
	var $fecha;
	var $pais_id;
	var $provincia_id;
	var $localidad_id;
	var $departamento_id;
	var $pais_detalle;
	
	var $_data_table = 'lote';
	
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
		$sql =	'SELECT * FROM lote WHERE ISNULL(deleted_at) ORDER BY sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromPais($pais_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'	SELECT * FROM lote WHERE pais_id = '."'".$db->escape($pais_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromColeccion($coleccion_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT * FROM lote WHERE coleccion_id = '."'".$db->escape($coleccion_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromTipoMaterial($tipo_material_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT * FROM lote WHERE tipo_material_id = '."'".$db->escape($tipo_material_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM lote WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function deleteAllFromColeccion($coleccion_id, $in_pairs = false)
	{
		$collection = Lote::findAllFromColeccion($coleccion_id);
		foreach ($collection as $item)
		{
			Lote::delete($item);
		}
	}
	
	
	public static function delete($instance)
	{
		LoteAdscripcionCultural::deleteAllFromLote($instance->id);
		LoteFotografia::deleteAllFromLote($instance->id);
		
		parent::delete($instance);
	}
	
}