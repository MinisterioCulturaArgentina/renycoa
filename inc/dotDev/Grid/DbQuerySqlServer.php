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

class dotDev_Grid_DbQuerySqlServer extends dotDev_Grid_DbQuery 
{	
	protected function getSelectQuery($startRowIndex = 0, $pageSize = 0, $orderField = null, $orderDir = 'ASC')
	{
		$this->buildWhereDefinition();
		$orderDirInverted = 'DESC';
		if ($orderDir == 'DESC')
			$orderDirInverted ='ASC';
			
			 
		if ($pageSize > 0)
		{
			$pageIndex = 1;
			
			if($startRowIndex > 0)
				$pageIndex = ($startRowIndex / $pageSize) + 1;

			$rowIndex = $pageIndex * $pageSize;

			if ($this->_totalRows < $rowIndex )
				$pageSize = ( $this->_totalRows - $rowIndex ) + $pageSize;

			/* SQLSERVER 2005 ACTUALIZAR!
			$sql =  "SELECT TOP ($pageSize) * FROM 
					(
						SELECT $this->selectExpr,
	  					ROW_NUMBER() OVER (".(($orderField) ? "ORDER BY $orderField $orderDir " : '').") AS [Result Number]
	  					FROM $this->tableRefs ".
	  					(($this->whereDefinition!='')? "WHERE $this->whereDefinition":'')." 
	  					GROUP BY $this->groupByExpr  
	  				) sel1
	  				WHERE [Result Number]> (($pageIndex - 1) * $pageSize) 
	  				ORDER BY [Result Number]";
	  		/**/
	  		/* SQLSERVER 2000 */	
			$sql =  "
					SELECT * FROM(
					SELECT TOP $pageSize * FROM( 
					SELECT TOP $rowIndex * FROM(
						SELECT TOP $rowIndex * FROM(
						SELECT $this->selectExpr
						FROM $this->tableRefs 
						".(($this->initialWhereDefinition !='')? "WHERE $this->initialWhereDefinition":'')."
						".(($this->groupByExpr!='')? "GROUP BY $this->groupByExpr":'')." 
					) sel 
						".(($this->whereDefinition!='')? "WHERE $this->whereDefinition":'')." 
						ORDER BY $orderField $orderDir
					) sel1 ORDER BY $orderField $orderDirInverted
					) sel2
					) sel3 ORDER BY $orderField $orderDir";
						  				
	  		/**/
		}
		else
		{
			$sql =  "
					SELECT * FROM(
					SELECT $this->selectExpr
	  				FROM $this->tableRefs
	  				".(($this->initialWhereDefinition!='')? "WHERE $this->initialWhereDefinition":'')." 
	  				".(($this->groupByExpr!='')? "GROUP BY $this->groupByExpr":'')."
	  				) sel 
	  				".(($this->whereDefinition!='')? "WHERE $this->whereDefinition":'')."
					".(($orderField) ? " ORDER BY $orderField $orderDir " : '');
		}	
  				
  		return $sql;
	}
	
	protected function getCountQuery()
	{		
		$sql = 'SELECT COUNT(*) FROM('.$this->getSelectQuery().') as innerSql';
		
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
			case dotDev_Grid_FilterField::FILTER_GTE:
			case dotDev_Grid_FilterField::FILTER_LT:
			case dotDev_Grid_FilterField::FILTER_LTE:
				if ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_NUMERIC)
				{
					$res = $filterParam->fieldId.' '.$filterParam->filterType.' '.$db->escape($filterParam->value);
				}
				elseif ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_DATE)
				{
					$res = $filterParam->fieldId.' '.$filterParam->filterType.' CONVERT( smalldatetime, '."'".$db->escape($filterParam->value)."', 112)";
					
				}
				elseif ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_BOOLEAN)
				{
					$res = $filterParam->fieldId.' = '.($filterParam->value == 'true' ? "'True'":"'False'");
				}
				break;
				
			case dotDev_Grid_FilterField::FILTER_LIKE:
				$res = $filterParam->fieldId." LIKE '%".$db->escape($filterParam->value)."%'";
				break;
				
			case dotDev_Grid_FilterField::FILTER_IN:
				if ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_NUMERIC)
				{
					$res =  "$filterParam->fieldId IN ($filterParam->value)";
				}
				elseif ($filterParam->valueType == dotDev_Grid_FilterField::VALUE_STRING)
				{ 
					$vals = explode(',',$filterParam->value);
					$res = '';
					foreach ((array)$vals as $v )
					{
						if ($res != '')
							$res .= ' OR ';
	
						$res .= $filterParam->fieldId." LIKE '".$db->escape($v)."'";
					}
					$res = " ( ".$res." ) ";
				}
				break;
				
			case dotDev_Grid_FilterField::FILTER_NULL:
				$res = ''.$filterParam->fieldId.' IS NULL';
				break;
		}
		return $res;
	}

	
}
?>