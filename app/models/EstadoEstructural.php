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

class EstadoEstructural extends PersistentModel 
{
	var $id;
	var $descripcion;
	
	var $_data_table = 'estado_estructural';
	
	public function __toString()
	{
		return $this->descripcion.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM estado_estructural WHERE ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromObjeto($objeto_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					estado_estructural.* 
				FROM 
					estado_estructural
					INNER JOIN objeto_estado_estructural ON objeto_estado_estructural.estado_estructural_id = estado_estructural.id  
			WHERE 
				objeto_estado_estructural.objeto_id = '."'".$db->escape($objeto_id)."'".' AND
				ISNULL(objeto_estado_estructural.deleted_at) AND
				ISNULL(estado_estructural.deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}

	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM estado_estructural WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
}