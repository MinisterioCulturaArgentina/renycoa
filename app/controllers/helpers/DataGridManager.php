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

class Zend_Controller_Action_Helper_DataGridManager extends Zend_Controller_Action_Helper_Abstract
{
	public function initGrid($sEntity, $sEntityIdField, $aFields, $parameters = array(), $gridId = 'grid', $order = '', $sense = 'ASC')
    { 
    	$grid = array();    	
    	$sGrid =  $gridId.'::';

    	$pagesize = Zend_Registry::get('config')->grid_size;
		 

		if(isset($_REQUEST[$sGrid.'limit']))
		{
			$pagesize = $_REQUEST[$sGrid.'limit'];
		}

		//grid order sense
		if(isset($_REQUEST[$sGrid.'sense']))
		{
			$sense = $_REQUEST[$sGrid.'sense'];
		}

		//grid order field
		if(isset($_REQUEST[$sGrid.'order']))
		{
			$order = $_REQUEST[$sGrid.'order'];
		}
		elseif (!$order)
		{
			$order = $aFields[0];
		}
		
		//grid filtering
		if(isset($_REQUEST[$sGrid.'dofilter']))
		{
			$dofilter = $_REQUEST[$sGrid.'dofilter'];
		}
		else
		{
			$dofilter = "false";
		}
		
   		//grid quantity
		if(isset($_REQUEST[$sGrid.'quantity']))
		{
			$quantity = $_REQUEST[$sGrid.'quantity'];
		}
		else
		{
			$quantity = 0;
		}
		
		if($dofilter == "true")
		{
			foreach($aFields as $k=>$v)
			{
				if(isset($_REQUEST[$sGrid.$v]))
				{
					$parameters[$v] = $_REQUEST[$sGrid.$v];		
				}
			}
		}

		$gridSearch = new GridSearch();

		$searchFunction = $sEntity."Search";

		$totalpages = $gridSearch->$searchFunction($parameters, $order , $sense, $quantity, $pagesize, true);
		
		$aData = $gridSearch->$searchFunction($parameters, $order , $sense, $quantity, $pagesize, false);
		
		if(isset($quantity) and $quantity !=  0 and $pagesize > 0)
		{
			$currentpage = ($quantity / $pagesize) + 1;
		}
		else
		{
			$currentpage = 1;
		}
		
		foreach ($aFields as $v)
		{
			$order_field = false;
			$varParts = explode(':',$v);
			$name = $varParts[1]; 
			$name_sense = $name.'_sense';
			$name_className = $name.'_className';
			$name_searchKey = $name.'_searchKey';
			if($v == $order)
			{
				$order_field = true;
				
				if($sense == "ASC")
				{
   					$grid[$name_sense] =  'DESC';
					$grid[$name_className] = 'active_up';
				}
				else
				{
					$grid[$name_sense] = 'ASC';
					$grid[$name_className] = 'active_down';
				}
			}
			else
			{
				$grid[$name_sense] = $sense;
				$grid[$name_className] = '';
			}
			$grid[$name_searchKey] = (isset($_REQUEST[$sGrid.$v])) ? $_REQUEST[$sGrid.$v] : '' ;
		}
		
		$grid['aFields'] = $aFields;
		$grid['aData'] = $aData;
		
		$grid['order'] = $order;
		$grid['sense'] = $sense;
		$grid['totalpages'] = $totalpages;
		$grid['pagesize'] = $pagesize;
		$grid['dofilter'] =  $dofilter;
		$grid['currentpage'] = ($currentpage * $pagesize) -$pagesize;
		
		$aPages = array();
		for($i = 0 ; $i < $totalpages; $i++)
		{
			$aPages[$i * $pagesize] = $i + 1;
		}
		
		$grid['aPages'] = $aPages;
		$grid['quantity'] = $quantity;
		
		$this->_actionController->view->$gridId = $grid;
    }
}