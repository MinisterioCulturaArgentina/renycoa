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

class ConfSeguridad extends PersistentModel 
{
	
	var $id;
	var $min_letras;
	var $min_numeros;
	var $min_simbolos;
	var $simbolos;
	var $largo_min;
	var $largo_max;	
	var $dias_caducidad;
	var $created_at;
	
	var $_data_table = 'conf_seguridad';
	
	public static function findByPK($pk)	
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM conf_seguridad WHERE id = '."'".$db->escape($pk)."'";

		$rs = $db->get_row($sql);

		return self::hydrate($rs, get_class());
	}
	
	public static function findCurrent()
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM conf_seguridad ORDER BY created_at DESC LIMIT 1';

		$rs = $db->get_row($sql);

		return self::hydrate($rs, get_class());
	}
}