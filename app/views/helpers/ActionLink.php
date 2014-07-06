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

class Zend_View_Helper_ActionLink
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function actionLink($text, $controller, $action = 'index', $params = array(), $attribs = array(), $print_disabled = false, $disabled_attribs = array())
    { 
    	$acl = Zend_Registry::get('acl');
    	$session = new Zend_Session_Namespace();
    	$usuario = Usuario::findByPK($session->uid);
    	
    	if ( !$usuario->rol_id || $acl->isAllowed($usuario->rol_id, $controller, $action))
	    {
	    	
	    	$config = Zend_Registry::get('config');
	    	$query_string = '';
	    	if ($config->url_rewrite)
	    	{
	    		$query_string = $this->view->baseUrl.$controller.'/'.$action;
	    		foreach ($params as $k => $v)
	    		{
	    			$query_string .= '/'.$k.'/'.$v;
	    		}
	    	}
	    	else
	    	{
	    		$query_string = $this->view->baseUrl.'?controller='.$controller.'&amp;action='.$action;
	    		foreach ($params as $k => $v)
	    		{
	    			$query_string .= '&amp;'.$k.'='.$v;
	    		}
	    	}
	    	
	    	$attribute_string =  '';
	    	foreach ($attribs as $k => $v)
	    	{
	    		$attribute_string .= ' '.$k.'="'.$v.'"';
	    	}
	
	   		return '<a href="'.$query_string.'"'.$attribute_string.'>'.$text.'</a>';
	    }
	    else
	    {
	    	if($print_disabled)
	    	{
	    		$disabled_attribute_string =  '';
	    		foreach ($disabled_attribs as $k => $v)
		    	{
		    		$disabled_attribute_string .= ' '.$k.'="'.$v.'"';
		    	}
		
		   		return '<span '.$disabled_attribute_string.'>'.$text.'</span>';
	    	}
	    	else
	    	{
	    		return '';
	    	}
	    }
	    	
    }
    
}

