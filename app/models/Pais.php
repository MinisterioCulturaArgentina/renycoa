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

class Pais extends PersistentModel 
{
	var $id;
	var $descripcion;
	
	var $_data_table = 'pais';
	
	public function __toString()
	{
		return $this->descripcion.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM pais WHERE ISNULL(deleted_at) ORDER BY descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromColeccion($coleccion_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT 
					pais.* 
				FROM 
					pais
					INNER JOIN coleccion_pais ON coleccion_pais.pais_id = pais.id  
			WHERE 
				coleccion_pais.coleccion_id = '."'".$db->escape($coleccion_id)."'".' AND
				ISNULL(coleccion_pais.deleted_at) AND
				ISNULL(pais.deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithTenedor($in_pairs = false)
	{
		$sql =	'	SELECT DISTINCT pais.* 
					FROM 
						pais 
						INNER JOIN tenedor ON tenedor.pais_id = pais.id
					WHERE 
						ISNULL(pais.deleted_at) AND
						ISNULL(tenedor.deleted_at)
					ORDER BY pais.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}

	public static function findAllWithDeposito($in_pairs = false)
	{
		$sql =	'	SELECT pais.* 
					FROM 
						pais 
						INNER JOIN deposito ON deposito.pais_id = pais.id
					WHERE 
						ISNULL(pais.deleted_at) AND
						ISNULL(deposito.deleted_at)
					ORDER BY pais.descripcion';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM pais WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function isInUse($pk)
	{
		$db = Zend_Registry::get('db');
		
		if ( count(ColeccionPais::findAllFromPais($db->escape($pk))) +
			 count(Departamento::findAllFromPais($db->escape($pk))) +
			 count(Deposito::findAllFromPais($db->escape($pk))) +
			 count(Localidad::findAllFromPais($db->escape($pk))) +
			 count(Lote::findAllFromPais($db->escape($pk))) +
			 count(Objeto::findAllFromPais($db->escape($pk))) +
			 count(Provincia::findAllFromPais($db->escape($pk))) +
			 count(Tenedor::findAllFromPais($db->escape($pk))) +
			 count(Yacimiento::findAllFromPais($db->escape($pk)))
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