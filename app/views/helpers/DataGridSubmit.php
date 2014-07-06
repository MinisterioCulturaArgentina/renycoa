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

class Zend_View_Helper_DataGridSubmit
{
	public $view;

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

	public function dataGridSubmit($gridIds = array())
	{
		if(!count($gridIds))
		{
			$gridIds[0] = 'grid';
		}
		$res = '<form name="GridForm" id="GridForm" method="post" action="">';
	  
		foreach ($gridIds as $grid)
		{
			$gridData =  $this->view->$grid;
			$res .=	'<input type="hidden" name="'.$grid.'::order" id="'.$grid.'::order" value="'.$gridData['order'].'" />'.
							'<input type="hidden" name="'.$grid.'::sense" id="'.$grid.'::sense" value="'.$gridData['sense'].'" />'.
							'<input type="hidden" name="'.$grid.'::quantity" id="'.$grid.'::quantity" value="'.$gridData['quantity'].'" />'.
	    				'<input type="hidden" name="'.$grid.'::limit" id="'.$grid.'::limit" value="'.$gridData['pagesize'].'" />'.
	    				'<input type="hidden" name="'.$grid.'::dofilter" id="'.$grid.'::dofilter" value="'.$gridData['dofilter'].'" />';
				
			foreach ($gridData['aFields'] as $field)
			{
				if (isset($_REQUEST[$grid.'::'.$field]))
				{
					$res .= '<input type="hidden" name="'.$grid.'::'.$field.'" id="'.$grid.'::'.$field.'" value="'.$_REQUEST[$grid.'::'.$field].'">';
				}
			}
		}
		$res .='</form>';

		return $res;
	}

}

