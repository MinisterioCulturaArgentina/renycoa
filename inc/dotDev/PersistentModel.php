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

class PersistentModel
{
	
	var $_pk = 'id';
	var $_data_table = '';
	var $_date_fields = array();
	var $_datetime_fields = array();
	var $_protected_fields = array();
	
	const PROVIDER_MYSQL = 'mysql';
	const PROVIDER_SQLSERVER = 'sqlserver';
	const PROVIDER_SQLITE = 'sqlite';
	
	const DEBUG = false;
	
	public function __construct()
	{
		$this->_protected_fields = array( 
			'_pk', 
			'_data_table',
			'_date_fields',
			'_datetime_fields',
			'_protected_fields',
			'created_at', 
			'created_by', 
			'updated_at',
			'updated_by',
			'deleted_at',
			'deleted_by'
		);
		
		foreach ($this as $attr=>$val)
		{
			if ( !in_array($attr, $this->_protected_fields) )
			{
				$this->$attr = "";
			}
		}
	}

	protected static function findMultiple($sql, $class, $in_pairs = false, $values = null)
	{
		$db = Zend_Registry::get('db');
	
		$rs = $db->get_results($sql);
		
		if (!$in_pairs)
		{
			return self::hydrateCollection($rs, $class);
		}
		else
		{
			$cols =$db->get_col_info();
			$pairs = array();
			foreach ((array)$rs as $row)
			{
				$pairs[$row->$cols[0]] = $row->$cols[1];
			}
			return $pairs;
		}
	}
	
	protected  static function hydrateCollection($rs, $class)
	{
		$res = array();
		$i = 0;
		foreach ((array)$rs as $r)
		{
			$res[$i] = self::hydrate($r, $class);
			$i++;
		}
		return $res;
	}
	
	protected static function hydrate($r, $class)
	{
		$o = new $class();
		if (is_object($r))
		{
			foreach ($o as $attr=>$val)
			{
				if ( !in_array($attr, $o->_protected_fields) )
				{
					if (in_array($attr,$o->_date_fields) && $r->$attr != '')
					{
						$date = new Zend_Date($r->$attr, 'YYYY-MM-dd');
						$o->$attr = $date->get($date->toString('dd/MM/yyyy'));
					}
					elseif (in_array($attr,$o->_datetime_fields) && $r->$attr != '')
					{
						$date = new Zend_Date($r->$attr, 'YYYY-MM-dd HH:mm:ss');
						$o->$attr = $date->get($date->toString('dd/MM/yyyy HH:mm:ss'));
					}
					else
					{
						$o->$attr = $r->$attr;
					}
				}
			}
		}
		return $o;
	}
	
	public function save()
	{
		$db = Zend_Registry::get('db');
		$session = new Zend_Session_Namespace();
		$data = $this->getDataArray();
		$pk = $this->_pk;

		if (!$this->$pk)
		{
			$keys = array_keys($data);
			
			$sql = 'INSERT INTO '.$this->_data_table. '(';
			
			$first = true;
			foreach ($keys as $k)
			{
				if ($data[$k] != '')
				{
					$sql .= ((!$first)?', ':'').$k;
					$first = false;
				}
			}
			$sql .= ', created_at, created_by) VALUES (';
			
			$first = true;
			foreach ($keys as $k)
			{
				if ($data[$k] != '')
				{
					if (in_array($k, $this->_date_fields))
					{
						$date = new Zend_Date($data[$k], 'dd/MM/yyyy');
						$data[$k] = $date->get($date->toString('yyyy-MM-dd'));
					}
					$sql .= ((!$first)?', ':'')."'".$db->escape($data[$k])."'";
					$first = false;
				}
			}
			$sql .= ', '.self::dateNow().', '.$session->uid.')';
			if(self::DEBUG)
			{
				superecho($_REQUEST);
				superecho($sql,true);
			}
			$db->query($sql);
			$this->$pk = $db->insert_id;
		}
		else
		{
			$sql = 'UPDATE '.$this->_data_table. ' SET '."\n";
			
			$first = true;
			foreach ($data as $k => $v)
			{
				if (in_array($k, $this->_date_fields) && $v != '')
				{
					$date = new Zend_Date($v, 'dd/MM/yyyy');
					$v = $date->get($date->toString('yyyy-MM-dd'));
				}
				$sql .= ((!$first)?', ':'').$k.' = '.(($v == '')?'NULL':"'".$db->escape($v)."'")."\n";
				$first = false;
			}
			$sql .= ', updated_at = '.self::dateNow()."\n".
					', updated_by = '.$session->uid.' '."\n".
					'WHERE '.$pk.' = '.$db->escape($this->$pk);
			
			if(self::DEBUG)
			{
				superecho($_REQUEST);
				superecho($sql,true);
			}
			$db->query($sql);
		}
	}
	
	public static function delete($instance)
	{
		$db = Zend_Registry::get('db');
		$session = new Zend_Session_Namespace();
		$pk = $instance->_pk;
		
		$sql = 'UPDATE '.$instance->_data_table. 
			   ' SET deleted_at = '.self::dateNow().', deleted_by = '.$session->uid.
			   ' WHERE id = '.$db->escape($instance->$pk);
		
		if(self::DEBUG)
		{
			superecho($_REQUEST);
			superecho($sql,true);
		}
		$db->query($sql);
		$instance = null;
	}
	
	public function setAll($data)
	{
		foreach ($this as $attr=>$val)
		{
			if (!in_array($attr, $this->_protected_fields))
			{
				if ($attr != $this->_pk)
				{
					$this->$attr = $data[$attr];
				}
				
				if (!in_array($attr ,array_keys($data)))
				{
					$this->$attr = null;
				}
			}
		}
	}
	
	public function isEqual($toCompare)
	{
		$equal = true;
		foreach ($this as $attr=>$val)
		{
			if ($this->$attr != $toCompare->$attr)
			{
				$equal = false;
				break;
			}
		}
		return $equal;
	}
	
	private function getDataArray()
	{
		$data = array();
		foreach ($this as $attr=>$val)
		{
			if ($attr != $this->_pk && !in_array($attr, $this->_protected_fields) )
			{
				$data[$attr] = $val;
			}
		}
		return $data;
	}
	
	protected static function isNull($field)
	{
		switch (Zend_Registry::get('config')->db->provider)
		{
			case self::PROVIDER_MYSQL:
				return " ISNULL($field) ";
				break;
				
			case self::PROVIDER_SQLSERVER:
				return " $field IS NULL ";
				break;
				
			case self::PROVIDER_SQLITE:
				return " $field ISNULL ";
				break;
		}
	}
	
	protected static function dateNow()
	{
		switch (Zend_Registry::get('config')->db->provider)
		{
			case self::PROVIDER_MYSQL:
				return ' NOW() ';
				break;
				
			case self::PROVIDER_SQLSERVER:
				return ' GETDATE() ';
				break;
				
			case self::PROVIDER_SQLITE:
				return " datetime('now') ";
				break;
		}
	}
}