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

class Provincia extends PersistentModel  
{
	var $id;
	var $pais_id;
	var $descripcion;
	
	var $_data_table = 'provincia';
	
	public function __toString()
	{
		return $this->descripcion.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		if ($in_pairs)
		{
			$sql = 'SELECT id, descripcion FROM provincia WHERE ISNULL(deleted_at) ORDER BY descripcion';
		}
		else
		{
			$sql = 'SELECT * FROM provincia WHERE ISNULL(deleted_at) ORDER BY descripcion';
		}
		
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromPais($pais_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT * FROM provincia WHERE ISNULL(deleted_at) AND pais_id = '."'".$db->escape($pais_id)."'".' ORDER BY descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithColeccion($in_pairs = false)
	{
		$sql =	'	SELECT ';
		if ($in_pairs)
		{
			$sql .= 'provincia.id, provincia.descripcion';
		}
		else
		{
			 $sql .= 'provincia.*';
		}
		$sql .= '	FROM 
						provincia 
						INNER JOIN coleccion ON coleccion.provincia_id = provincia.id
					WHERE 
						ISNULL(provincia.deleted_at) AND
						ISNULL(coleccion.deleted_at)
					ORDER BY provincia.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithTenedor($in_pairs = false)
	{
		$sql =	'	SELECT ';
		if ($in_pairs)
		{
			$sql .= 'provincia.id, provincia.descripcion';
		}
		else
		{
			 $sql .= 'provincia.*';
		}
		$sql .= '	FROM 
						provincia 
						INNER JOIN tenedor ON tenedor.provincia_id = provincia.id
					WHERE 
						ISNULL(provincia.deleted_at) AND
						ISNULL(tenedor.deleted_at)
					ORDER BY provincia.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithDeposito($in_pairs = false)
	{
		$sql =	'	SELECT ';
		if ($in_pairs)
		{
			$sql .= 'provincia.id, provincia.descripcion';
		}
		else
		{
			 $sql .= 'provincia.*';
		}
		$sql .= '	FROM 
						provincia 
						INNER JOIN deposito ON deposito.provincia_id = provincia.id
					WHERE 
						ISNULL(provincia.deleted_at) AND
						ISNULL(deposito.deleted_at)
					ORDER BY provincia.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}

	public static function findAllWithYacimiento ($in_pairs = false)
	{
		$sql =	'	SELECT ';
		if ($in_pairs)
		{
			$sql .= 'provincia.id, provincia.descripcion';
		}
		else
		{
			 $sql .= 'provincia.*';
		}
		$sql .= '	FROM 
						provincia 
						INNER JOIN yacimiento ON yacimiento.provincia_id = provincia.id
					WHERE 
						ISNULL(provincia.deleted_at) AND
						ISNULL(yacimiento.deleted_at)
					ORDER BY provincia.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM provincia WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
}