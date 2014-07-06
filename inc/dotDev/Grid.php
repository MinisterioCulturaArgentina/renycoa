<?php
/**
 * Copyright (C) 2009 Marcelo Costanzi - www.dotdev.com.ar
 * 
 * This file is part of JDA Building Manager
 *
 * JDA Building Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * JDA Building Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with JDA Building Manager.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

abstract class dotDev_Grid
{
	var $dbQuery;
	var $fields;
	var $id = 'grid';
	var $startRowIndex = 0;
	var $pageSize = 0;
	var $orderField;
	var $orderDir;
	var $exportXlsUrl;
	var $printUrl;
	
	var $data;
	var $totalRows;
	
	
	public function __construct($dbQuery, $fields, $params = null)
	{
		$this->dbQuery = $dbQuery;	
		$this->fields = $fields;
				
		$this->pageSize = Zend_Registry::get('config')->grid->page_size;
		
		if (is_array($params))
		{
			if (isset($params['id']))
				$this->id = $params['id'];
				
			if (isset($params['exportXlsUrl']))
				$this->exportXlsUrl = $params['exportXlsUrl'];
			
			if (isset($params['printUrl']))
				$this->printUrl = $params['printUrl'];
			
			if (isset($params['startRowIndex']))
				$this->startRowIndex = $params['startRowIndex'];
				
			if (isset($params['pageSize']))
				$this->pageSize = $params['pageSize'];
				
			if (isset($params['orderField']))
			{
				$this->orderField = $params['orderField'];
				$this->orderDir = 'ASC';
			}

			if (isset($params['orderDir']))
				$this->orderDir = $params['orderDir'];
		}
	}
	
	abstract public function fetchArgsFromRequest();
	
	public function fecthData()
	{ 
		$this->totalRows = $this->dbQuery->count();
		$this->data = 	$this->dbQuery->query($this->startRowIndex, $this->pageSize, $this->orderField, $this->orderDir);
	}
	
	protected function getField($fieldId)
	{
		foreach ($this->fields as $field)
		{
			if ($field->fieldId == $fieldId)
			{
				return $field;
			}
		}
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getData()
	{  
		if (!count((array)$this->data))
      return array();
    else 
		  return $this->data;
	}
	
	public function getTotalRows()
	{
		return $this->totalRows;
	}
	
}