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

class Zend_View_Helper_Url
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function url($controller, $action = 'index', $params = array(), $baseUrl = '')
    {
    	$config = Zend_Registry::get('config');
    	$query_string = '';
    	if ( $baseUrl == '')
    	{
    		$baseUrl = $this->view->baseUrl;
    	}
    	if ($config->url_rewrite)
    	{
    		$query_string = $baseUrl.$controller.'/'.$action;
    		foreach ($params as $k => $v)
    		{
    			$query_string .= '/'.$k.'/'.$v;
    		}
    	}
    	else
    	{
    		$query_string = $baseUrl.'?controller='.$controller.'&action='.$action;
    		foreach ($params as $k => $v)
    		{
    			$query_string .= '&'.$k.'='.$v;
    		}
    	}
   		return $query_string;
    }
    
}

