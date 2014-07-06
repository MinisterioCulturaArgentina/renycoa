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

class AdscripcionCultural extends PersistentModel 
{
	var $id;
	var $nombre;
	var $descripcion;
	
	var $_data_table = 'adscripcion_cultural';
	
	public function __toString()
	{
		return $this->nombre.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM adscripcion_cultural WHERE ISNULL(deleted_at) ORDER BY nombre';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					adscripcion_cultural.* 
				FROM 
					adscripcion_cultural
					INNER JOIN yacimiento_adscripcion_cultural ON yacimiento_adscripcion_cultural.adscripcion_cultural_id = adscripcion_cultural.id  
			WHERE 
				yacimiento_adscripcion_cultural.yacimiento_id = '."'".$db->escape($yacimiento_id)."'".' AND
				ISNULL(yacimiento_adscripcion_cultural.deleted_at) AND
				ISNULL(adscripcion_cultural.deleted_at)
			ORDER BY nombre';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	

	public static function findAllFromLote($lote_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					adscripcion_cultural.* 
				FROM 
					adscripcion_cultural
					INNER JOIN lote_adscripcion_cultural ON lote_adscripcion_cultural.adscripcion_cultural_id = adscripcion_cultural.id  
			WHERE 
				lote_adscripcion_cultural.lote_id = '."'".$db->escape($lote_id)."'".' AND
				ISNULL(lote_adscripcion_cultural.deleted_at) AND
				ISNULL(adscripcion_cultural.deleted_at)
			ORDER BY nombre';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithObjeto($in_pairs = false)
	{
		$sql =	'	SELECT adscripcion_cultural.* 
					FROM 
						adscripcion_cultural 
						INNER JOIN objeto ON objeto.adscripcion_cultural_id = adscripcion_cultural.id
					WHERE 
						ISNULL(adscripcion_cultural.deleted_at) AND
						ISNULL(objeto.deleted_at)
					ORDER BY adscripcion_cultural.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithYacimiento($in_pairs = false)
	{
		$sql =	'	SELECT adscripcion_cultural.* 
					FROM 
						adscripcion_cultural 
						INNER JOIN yacimiento_adscripcion_cultural ON yacimiento_adscripcion_cultural.adscripcion_cultural_id = adscripcion_cultural.id
						INNER JOIN yacimiento ON yacimiento.id = yacimiento_adscripcion_cultural.yacimiento_id   
					WHERE 
						ISNULL(yacimiento_adscripcion_cultural.deleted_at) AND
						ISNULL(adscripcion_cultural.deleted_at) AND
						ISNULL(yacimiento.deleted_at)
					ORDER BY adscripcion_cultural.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM adscripcion_cultural WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function isInUse($pk)
	{
		$db = Zend_Registry::get('db');
		
		if ( count(Objeto::findAllFromAdscripcionCultural($db->escape($pk))) +
			 count(LoteAdscripcionCultural::findAllFromAdscripcionCultural($db->escape($pk))) +
			 count(YacimientoAdscripcionCultural::findAllFromAdscripcionCultural($db->escape($pk)))
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