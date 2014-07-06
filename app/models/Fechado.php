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

class Fechado extends PersistentModel 
{
	
	var $id;
	var $yacimiento_id; 	
	var $cod_laboratorio;
	var $fecha_c14;
	var $fecha_c14_desvio;
	var $fecha_calibrada_2s;
	var $fecha_calibrada_2s_tipo_fecha_id;	
	var $fecha_calibrada_2s_desvio;
	var $fecha_calibrada_2s_desvio_tipo_fecha_id;	
	var $fecha_calendarica;
	var $fecha_calendarica_tipo_fecha_id; 
	
	var $_data_table = 'fechado';
	
	public function __toString()
	{
		return $this->cod_laboratorio;
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM fechado WHERE ISNULL(deleted_at) ORDER BY cod_laboratorio';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT * FROM fechado WHERE yacimiento_id = '."'".$db->escape($yacimiento_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
		
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM fechado WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function deleteAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$collection = Fechado::findAllFromYacimiento($yacimiento_id);
		foreach ($collection as $item)
		{
			Fechado::delete($item);
		}
	}
}