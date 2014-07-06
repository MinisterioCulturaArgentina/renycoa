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

class TipoMaterial extends PersistentModel 
{
	var $id;
	var $descripcion;
	
	var $_data_table = 'tipo_material';
	
	public function __toString()
	{
		return $this->descripcion.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM tipo_material WHERE ISNULL(deleted_at) ORDER BY tipo_material.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithObjeto($in_pairs = false)
	{
		$sql =	'	SELECT tipo_material.* 
					FROM 
						tipo_material 
						INNER JOIN objeto ON objeto.tipo_material_id = tipo_material.id
					WHERE 
						ISNULL(tipo_material.deleted_at) AND
						ISNULL(objeto.deleted_at)
					ORDER BY tipo_material.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithLote($in_pairs = false)
	{
		$sql =	'	SELECT tipo_material.* 
					FROM 
						tipo_material 
						INNER JOIN lote ON lote.tipo_material_id = tipo_material.id
					WHERE 
						ISNULL(tipo_material.deleted_at) AND
						ISNULL(lote.deleted_at)
					ORDER BY tipo_material.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromColeccion($coleccion_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					tipo_material.* 
				FROM 
					tipo_material
					INNER JOIN coleccion_tipo_material ON coleccion_tipo_material.tipo_material_id = tipo_material.id  
			WHERE 
				coleccion_tipo_material.coleccion_id = '."'".$db->escape($coleccion_id)."'".' AND
				ISNULL(coleccion_tipo_material.deleted_at) AND
				ISNULL(tipo_material.deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
public static function findAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					tipo_material.* 
				FROM 
					tipo_material
					INNER JOIN yacimiento_tipo_material ON yacimiento_tipo_material.tipo_material_id = tipo_material.id  
			WHERE 
				yacimiento_tipo_material.yacimiento_id = '."'".$db->escape($yacimiento_id)."'".' AND
				ISNULL(yacimiento_tipo_material.deleted_at) AND
				ISNULL(tipo_material.deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM tipo_material WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function isInUse($pk)
	{
		$db = Zend_Registry::get('db');
		
		if ( count(ColeccionTipoMaterial::findAllFromTipoMaterial($db->escape($pk))) +
			 count(Lote::findAllFromTipoMaterial($db->escape($pk))) +
			 count(Objeto::findAllFromTipoMaterial($db->escape($pk)))
			)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}