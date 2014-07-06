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

class dotDev_Grid_FilterField
{
	
	var $fieldId;
	var $label;
	var $valueType;
	var $value;
	var $filterType;
	var $applyFilterOn;
	var $listFilterOptions;
	var $renderer;
	var $hidden = 'false';
	var $width;

	const FILTER_EQ		= '=';
	const FILTER_LT 	= '<';
	const FILTER_LTE 	= '<=';
	const FILTER_GT 	= '>';
	const FILTER_GTE 	= '>=';
	const FILTER_LIKE 	= '%';
	const FILTER_IN 	= 'IN';
	const FILTER_NULL 	= 'NULL';
	const FILTER_LITERAL = 'FUNC';
	
	const VALUE_DATE = 'date';
	const VALUE_STRING = 'string';
	const VALUE_NUMERIC = 'numeric';
	const VALUE_BOOLEAN = 'boolean';
	const VALUE_LIST = 'list';

	
	public function __construct($fieldId, $params = array())
	{
		$this->fieldId = $fieldId;
		
		if (is_array($params))
		{
			if (isset($params['label']))
				$this->label = $params['label'];
			
			if (isset($params['valueType']))
				$this->valueType = $params['valueType'];
				
			if (isset($params['value']))
				$this->value = $params['value'];
				
			if (isset($params['filterType']))
				$this->filterType = $params['filterType'];
				
			if (isset($params['applyFilterOn']))
				$this->applyFilterOn = $params['applyFilterOn'];
				
			if(isset($params['listFilterOptions']))
				$this->listFilterOptions = $params['listFilterOptions'];
			
			if(isset($params['renderer']))
				$this->renderer = $params['renderer'];
				
			if(isset($params['hidden']))
				$this->hidden = $params['hidden'];
				
			if(isset($params['width']))
				$this->width = $params['width'];
		}
	}
}
?>