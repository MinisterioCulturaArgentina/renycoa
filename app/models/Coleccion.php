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

class Coleccion extends PersistentModel 
{
	
	var $id;
	var $sigla;
	var $nombre;
	var $sitio_arqueologico;
	var $observaciones;
	var $tenedor_id;
	var $deposito_id;
	var $deposito_temporario;
	var $fur;
	var $provisorio;
	var $completado_por;
	var $tipo_doc_completador_id;
	var $completador_nro_doc;
	var $fecha;
	var $provincia_id;
	var $localidad_id;
	var $departamento_id;
	
	var $_data_table = 'coleccion';
	
	public function __construct()
	{
		parent::__construct();
		$this->_date_fields = array('fecha'); 
	}
	public function __toString()
	{
		return $this->sigla.' - '.$this->nombre;
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM coleccion WHERE ISNULL(deleted_at) ORDER BY sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromTenedor($tenedor_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'	SELECT * FROM coleccion WHERE tenedor_id = '."'".$db->escape($tenedor_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromPais($pais_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'	SELECT * FROM coleccion WHERE pais_id = '."'".$db->escape($pais_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithLote($in_pairs = false)
	{
		$sql =	'	SELECT coleccion.* 
					FROM 
						coleccion 
						INNER JOIN lote ON lote.coleccion_id = coleccion.id
					WHERE 
						ISNULL(coleccion.deleted_at) AND
						ISNULL(lote.deleted_at)
					ORDER BY coleccion.sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithObjeto($in_pairs = false)
	{
		$sql =	'	SELECT coleccion.* 
					FROM 
						coleccion 
						INNER JOIN objeto ON objeto.coleccion_id = coleccion.id
					WHERE 
						ISNULL(coleccion.deleted_at) AND
						ISNULL(objeto.deleted_at)
					ORDER BY coleccion.sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithTenedor($in_pairs = false)
	{
		$sql =	'	SELECT coleccion.* 
					FROM 
						coleccion 
						INNER JOIN tenedor ON tenedor.id = coleccion.tenedor_id
					WHERE 
						ISNULL(coleccion.deleted_at) AND
						ISNULL(tenedor.deleted_at)
					ORDER BY coleccion.sigla';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM coleccion WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function deleteAllFromTenedor($tenedor_id, $in_pairs = false)
	{
		$collection = Coleccion::findAllFromTenedor($tenedor_id);
		foreach ($collection as $item)
		{
			Coleccion::delete($item);
		}
	}
	
	public static function delete($instance)
	{
		ColeccionTipoMaterial::deleteAllFromColeccion($instance->id);
		Lote::deleteAllFromColeccion($instance->id);
		Objeto::deleteAllFromColeccion($instance->id);
		
		parent::delete($instance);
	}
	
	public function getCountChildObjeto()
	{
		$db = Zend_Registry::get('db');
		$sql ='	SELECT COUNT(id) 
				FROM objeto 
				WHERE ISNULL(deleted_at) AND coleccion_id = '."'".$db->escape($this->id)."'";
		return $db->get_var($sql);
	}
	
	public function getCountChildLote()
	{
		$db = Zend_Registry::get('db');
		$sql ='	SELECT COUNT(id) 
				FROM lote 
				WHERE ISNULL(deleted_at) AND coleccion_id = '."'".$db->escape($this->id)."'";
		return $db->get_var($sql);
	}
	
	public function getSumChildLoteCantidadObjetos()
	{
		$db = Zend_Registry::get('db');
		$sql = 'SELECT SUM( cantidad_objetos ) 
				FROM lote 
				WHERE ISNULL(deleted_at) AND coleccion_id ='."'".$db->escape($this->id)."'".' 
				GROUP BY coleccion_id';
		return $db->get_var($sql);
	}
	
	
}