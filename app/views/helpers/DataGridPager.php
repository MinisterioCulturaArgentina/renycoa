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

class Zend_View_Helper_DataGridPager
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function dataGridPager($gridId)
    {
	    $res = '';
	    $grid = $this->view->$gridId;
    	if ($grid['pagesize'] != 0 &&  $grid['totalpages'] > 1)
    	{
			$res .= '<div style="text-align:center">Página: '.
					'<select name="quantity_cmb" id="quantity_cmb" onchange="pagerSetFormValue(this,'."'".$gridId."'".')" >';
			foreach ($grid['aPages'] as $value=>$label)
			{
				$selected = ($grid['currentpage'] == $value) ? 'selected="selected"' : '';
				$res .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			}
			$res .= '</select></div>';
    	}
    	return $res;
    }
    
}

