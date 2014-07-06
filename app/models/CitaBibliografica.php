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

class CitaBibliografica extends PersistentModel 
{
	
	var $id;
	var $yacimiento_id; 	
	var $ano;
	var $autores;
	var $cita;
		
	var $_data_table = 'cita_bibliografica';
	
	public function __toString()
	{
		return $this->ano;
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM cita_bibliografica WHERE ISNULL(deleted_at) ORDER BY ano';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
		
	public static function findAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT * FROM cita_bibliografica WHERE yacimiento_id = '."'".$db->escape($yacimiento_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM cita_bibliografica WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function deleteAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$collection = CitaBibliografica::findAllFromYacimiento($yacimiento_id);
		foreach ($collection as $item)
		{
			CitaBibliografica::delete($item);
		}
	}
}