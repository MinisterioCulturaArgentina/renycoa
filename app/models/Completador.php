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

class Completador extends PersistentModel 
{
	var $id;
	var $tipo_doc_completador_id;
	var $nro_doc;
	var $nombre_completo;
	
	var $_data_table = 'completador';
	
	public function __toString()
	{
		return $this->nombre_completo.'';
	}
	
	public static function findAll($in_pairs = false)
	{
		$sql =	'SELECT * FROM completador WHERE ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM completador WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
  public static function findByTipoDocCompletadorAndNroDoc($tipo_doc_completador_id, $nro_doc)
  {
    $db = Zend_Registry::get('db');
    
    $sql =  'SELECT * FROM completador WHERE tipo_doc_completador_id = '."'".$db->escape($tipo_doc_completador_id)."'". ' AND  nro_doc = '."'".$db->escape($nro_doc)."'";
    $rs = $db->get_row($sql);
    return self::hydrate($rs, get_class());
  }
}