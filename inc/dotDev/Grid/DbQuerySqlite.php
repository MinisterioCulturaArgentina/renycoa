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

class dotDev_Grid_DbQuerySqlite extends dotDev_Grid_DbQuery 
{
	
	protected function getSelectQuery($startRowIndex = 0, $pageSize = 0, $orderField = null, $orderDir = 'ASC')
	{
		$this->buildWhereDefinition();
		
		$sql = "SELECT $this->selectExpr 
				FROM $this->tableRefs ".
				(($this->whereDefinition!='')? "WHERE $this->whereDefinition":'')."
				GROUP BY $this->groupByExpr ".
				(($orderField) ? "ORDER BY $orderField $orderDir " : '').
				(($pageSize) ? "LIMIT $startRowIndex,$pageSize":'')	;
  				
  		return $sql;
	}
	
	protected function getCountQuery()
	{
		$sql = 'SELECT COUNT(*) FROM('.$this->getSelectQuery().')';
		return $sql;
	}
	
	protected function getFilterParamString($filterParam)
	{
		$db = Zend_Registry::get('db'); 
		$res = '';
		
		switch ($filterParam->filterType)
		{
			case dotDev_Grid_FilterField::FILTER_LITERAL:
				$res = '( '.$filterParam->value.' )';
				break;
				
			case dotDev_Grid_FilterField::FILTER_EQ:
			case dotDev_Grid_FilterField::FILTER_GT:
			case dotDev_Grid_FilterField::FILTER_LT:
				if ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_NUMERIC)
				{
					$res = $this->getFieldName($filterParam).' '.$filterParam->filterType.' '.$db->escape($filterParam->value);
				}
				elseif ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_DATE)
				{
					$res = '(julianday(date('.$this->getFieldName($filterParam).')) - julianday(date('."'".$db->escape($filterParam->value)."'".'))) '.$filterParam->filterType.' 0';
				}
				elseif ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_BOOLEAN)
				{
					$res = $this->getFieldName($filterParam).' = '.($filterParam->value == 'true' ? '1':'0');
				}
				break;
				
			case dotDev_Grid_FilterField::FILTER_LIKE:
				$res = $this->getFieldName($filterParam)." LIKE '%".$db->escape($filterParam->value)."%'";
				break;
				
			case dotDev_Grid_FilterField::FILTER_IN:
				
				$res = $this->getFieldName($filterParam)." IN (".$filterParam->value.')';
				break;
				
			case dotDev_Grid_FilterField::FILTER_NULL:
				$res = 'ISNULL('.$this->getFieldName($filterParam).')';
				break;
		}
		return $res;
	}
	
}
?>