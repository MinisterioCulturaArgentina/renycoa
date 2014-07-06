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

class DeterioroBiologico extends PersistentModel 
{
	var $id;
	var $descripcion;
	
	var $_data_table = 'deterioro_biologico';
	
	public function __toString()
	{
		return $this->descripcion.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM deterioro_biologico WHERE ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromObjeto($objeto_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					deterioro_biologico.* 
				FROM 
					deterioro_biologico
					INNER JOIN objeto_deterioro_biologico ON objeto_deterioro_biologico.deterioro_biologico_id = deterioro_biologico.id  
			WHERE 
				objeto_deterioro_biologico.objeto_id = '."'".$db->escape($objeto_id)."'".' AND
				ISNULL(objeto_deterioro_biologico.deleted_at) AND
				ISNULL(deterioro_biologico.deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}

	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM deterioro_biologico WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
}