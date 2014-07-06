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

class FuncionInferida extends PersistentModel 
{
	var $id;
	var $descripcion;
	
	var $_data_table = 'funcion_inferida';
	
	public function __toString()
	{
		return $this->descripcion.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM funcion_inferida WHERE ISNULL(deleted_at) ORDER BY descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					funcion_inferida.* 
				FROM 
					funcion_inferida
					INNER JOIN yacimiento_funcion_inferida ON yacimiento_funcion_inferida.funcion_inferida_id = funcion_inferida.id  
			WHERE 
				yacimiento_funcion_inferida.yacimiento_id = '."'".$db->escape($yacimiento_id)."'".' AND
				ISNULL(yacimiento_funcion_inferida.deleted_at) AND
				ISNULL(funcion_inferida.deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}

	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM funcion_inferida WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
}