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

class Usuario extends PersistentModel 
{
	
	var $id;
	var $usuario;
	var $nombre;
	var $apellido;
	var $rol_id;
	var $cargo; 	
	var $observaciones;
	var $fotografia_id;
	var $contrasena_updated_at; 	
	
	var $_data_table = 'usuario';
	
	public function __construct()
	{
		parent::__construct();
		array_push($this->_protected_fields, 'contrasena_updated_at');
	}
	
	
	public function __toString()
	{
		return $this->usuario.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM usuario ORDER BY usuario';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllWithMensaje($in_pairs = false)
	{
		$sql =	'	SELECT DISTINCT usuario.* 
					FROM 
						usuario 
						INNER JOIN mensaje ON mensaje.usuario_id = usuario.id
					WHERE 
						ISNULL(usuario.deleted_at) AND
						ISNULL(mensaje.deleted_at)
					ORDER BY usuario.usuario';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM usuario WHERE id = '."'".$db->escape($pk)."'";

		$rs = $db->get_row($sql);

		return self::hydrate($rs, get_class());
	}
	
	public static function findByUsuario($usuario)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM usuario WHERE usuario = '."'".$db->escape($usuario)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function authenticate($usuario, $password)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT 
					* FROM usuario 
				WHERE 
					usuario = '."'".$db->escape($usuario)."' AND 
					contrasena = SHA1('" . $db->escape($password) . "') AND
				 	ISNULL(usuario.deleted_at)";

		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public function setPassword($password)
	{
		$db = Zend_Registry::get('db');
		
		$sql = "UPDATE 
					usuario 
				SET
					contrasena = SHA1('" . $db->escape($password) . "') ,
					contrasena_updated_at = NOW()
				WHERE 
					usuario.id = '" . $db->escape($this->id) . "'";

		if ($db->query($sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function isAdmin()
	{
		if ($this->rol_id == 'admin')
		{
			return 1;		
		}
		return 0;
	}
}