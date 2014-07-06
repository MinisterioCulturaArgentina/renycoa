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

class Deposito extends PersistentModel 
{
	
	var $id;
	var $nombre;
	var $domicilio;
	var $pais_id;
	var $provincia_id;
	var $localidad_id;
	var $departamento_id;
	var $telefono;
	var $fax;
	var $email;
	
	var $_data_table = 'deposito';
	
	public function __toString()
	{
		return $this->nombre;
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM deposito WHERE ISNULL(deleted_at) ORDER BY nombre';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromPais($pais_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'	SELECT * FROM deposito WHERE pais_id = '."'".$db->escape($pais_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithColeccion($in_pairs = false)
	{
		$sql =	'	SELECT deposito.* 
					FROM 
						deposito
						INNER JOIN coleccion ON coleccion.deposito_id = deposito.id
					WHERE 
						ISNULL(deposito.deleted_at) AND
						ISNULL(coleccion.deleted_at)
					ORDER BY deposito.nombre';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM deposito WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
}