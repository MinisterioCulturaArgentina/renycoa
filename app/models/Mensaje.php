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

class Mensaje extends PersistentModel 
{
	var $id;
	var $usuario_id;
	var $mensaje;
	var $administrador;
	var $creation_date;
	
	var $_data_table = 'mensaje';
	
	public function __construct()
	{
		parent::__construct();
		$this->_datetime_fields = array('creation_date'); 
	}
	
	public function __toString()
	{
		return $this->mensaje.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT 
					mensaje.*,
					mensaje.created_at as creation_date
				  FROM mensaje WHERE ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT 
					mensaje.*,
					mensaje.created_at as creation_date 
				FROM mensaje WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
}