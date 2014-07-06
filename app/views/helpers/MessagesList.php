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

class Zend_View_Helper_MessagesList
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function messagesList($listElements, $listId = null, $listClass=null)
    {
		$res = '';
		if(count($listElements) > 0)
		{
			$res = '<ul '.(($listId!=null)?'id="'.$listId.'" ':'').(($listClass!=null)?'class="'.$listClass.'" ':'').'>';
			foreach ($listElements as $e)
			{
				$res .= '<li>'.$e.'</li>';
			}
			$res .= '</ul>';
		}
    	return $res;
    }
    
}

