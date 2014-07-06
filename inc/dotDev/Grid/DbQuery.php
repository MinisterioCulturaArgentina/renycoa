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

abstract class dotDev_Grid_DbQuery
{
	
	var $selectExpr;
	var $groupByExpr;
	var $tableRefs;
	var $initialFilters = array();
	var $filterArgs = array();
	var $initialWhereDefinition;
	var $whereDefinition;
	var $initialOrder;
	var $_totalRows = 0;
	
	public function __construct()
	{
		if (!isset($this->selectExpr) || !isset($this->tableRefs) )
		{ 
		   throw new Exception('Faltan parámetros');
		} 
	}
	
	public function addFilterArg($filterArg)
	{
		array_push($this->filterArgs, $filterArg);
	}
 
	public function query($startRowIndex = 0, $pageSize = 0, $orderField = null, $orderDir = 'ASC')
	{
		$db = Zend_Registry::get('db');
		
		$sql = $this->getSelectQuery($startRowIndex, $pageSize, $orderField, $orderDir);
  				
		if(isset($_GET['debug']))
		{
			superecho($_REQUEST);
			superecho($sql,true);
		}
		return $db->get_results($sql);
	}
	
	public function count()
	{
		$db = Zend_Registry::get('db');

		$sql = $this->getCountQuery();

		if(isset($_GET['debug_count']))
		{
			superecho($_REQUEST);
			superecho($sql,true);
		}
		$this->_totalRows = $db->get_var($sql);
		return $this->_totalRows;
	}
	
	abstract protected function getSelectQuery($startRowIndex = 0, $pageSize = 0, $orderField = null, $orderDir = 'ASC');
	
	abstract protected function getCountQuery();
	
	abstract protected function getFilterParamString($filterParam);
	
	protected function buildWhereDefinition()
	{
		//Initial Filters
		$initialWhereDef = '';
		foreach ((array)$this->initialFilters as $arg)
		{
			if ($initialWhereDef)
				$initialWhereDef .= ' AND ';

			if ($arg instanceof dotDev_Grid_FilterGroup)
			{
				$initialWhereDef .= $this->getFilterGroupString($arg);
			}
			elseif ($arg instanceof dotDev_Grid_FilterField)
			{
				$initialWhereDef .= $this->getFilterParamString($arg);
			}
		}
		$this->initialWhereDefinition = $initialWhereDef;

		//Runtime Filters
		$whereDef = '';
		foreach ((array)$this->filterArgs as $arg)
		{
			if ($whereDef)
				$whereDef .= ' AND ';

			if ($arg instanceof dotDev_Grid_FilterGroup)
			{
				$whereDef .= $this->getFilterGroupString($arg);
			}
			elseif ($arg instanceof dotDev_Grid_FilterField)
			{
				$whereDef .= $this->getFilterParamString($arg);
			}
		}
		$this->whereDefinition = $whereDef;		
	}
	
	protected function getFilterGroupString($filterGroup)
	{
		if (count((array)$filterGroup->filterParams))
		{
			$res = ' ( ';
			$first = true;
			foreach ((array)$filterGroup->filterParams as $param)
			{
				if (!$first)
					$res .= ' '.$filterGroup->operator.' ';
	
				$res .= $this->getFilterParamString($param);
				$first = false;
			}
			$res .= ' ) ';
			if ($first)
			{
				$res = '';
			}
			return $res;
		}
	}
}
?>