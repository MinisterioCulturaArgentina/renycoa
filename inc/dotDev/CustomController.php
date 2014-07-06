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

class CustomController extends Zend_Controller_Action 
{ 
	public function init()
    {       
        $this->_helper->actionStack('navigation', 'Common');
        $this->_helper->actionStack('messages', 'Common');
        $this->_helper->actionStack('login', 'Common');
    }

	public function preDispatch()
    {
    	$controller = $this->_getParam('controller');
    	$action = $this->_getParam('action');
    	
    	$session = new Zend_Session_Namespace();
	    $usuario = Usuario::findByPK($session->uid);
	    
	    if ($usuario->id )
	   	{
	   		$confSeguridad = ConfSeguridad::findCurrent();
   			if ($confSeguridad->dias_caducidad > 0 && $controller != 'contrasena' && !($controller == 'index' && $action == 'logout'))
   			{
   				date_default_timezone_set('America/Buenos_Aires'); 
	   			$earliest_date =  date_create();
	   			$earliest_date->modify('-'.$confSeguridad->dias_caducidad.' days');
	   			
	   			
	   			$contrasena_datetime_parts = explode(' ',$usuario->contrasena_updated_at);
	   			$contrasena_date_parts = explode('-',$contrasena_datetime_parts[0]);
	   			$contrasena_time_parts = explode(':',$contrasena_datetime_parts[1]);
	   			$contrasena_date = date_create();
	   			$contrasena_date->setDate($contrasena_date_parts[0],$contrasena_date_parts[1], $contrasena_date_parts[2]);
	   			$contrasena_date->setTime($contrasena_time_parts[0],$contrasena_time_parts[1], $contrasena_time_parts[2]);
	   			
	   			if ($contrasena_date->format('U') < $earliest_date->format('U'))
	   			{
   					Zend_Registry::get('messagehandler')->add('ERROR', 'Su contraseña ha caducado. Por favor actualícela.');
    				$this->_redirect(getControllerUrl('contrasena','index'));
	   			}
   			}
   		
   			if ($controller != 'index')
    		{
	   			$acl = Zend_Registry::get('acl');
		    	if (!$acl->isAllowed($usuario->rol_id, $controller,$action))
		    	{
		    		Zend_Registry::get('messagehandler')->add('ERROR', 'No tiene privilegios para realizar esta acción.');
		    		
		    		if ($acl->isAllowed($usuario->rol_id, $controller,'index'))
		    		{
		    			$this->_redirect(getControllerUrl($controller,'index'));
		    		}
		    		else
		    		{
		    			$this->_redirect(getControllerUrl('index','index'));
		    		}
		    	}
   			}
	   	}
   		else if ($controller != 'index')
    	{
   			Zend_Registry::get('messagehandler')->add('ERROR', 'Por favor inicie su sesión.');
   			$this->_redirect(getControllerUrl('index','logout'));
    	}
    }
    
  protected function setGridScripts()
  {
    $baseUrl = Zend_Registry::get('config')->base_url;
    /* expanded static */
    $this->view->headScript()->appendFile($baseUrl.'scripts/date.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext/adapter/jquery/ext-jquery-adapter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext/ext-all.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext/build/locale/ext-lang-es.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/menu/EditableItem.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/menu/RangeMenu.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/GridFilters.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/filter/Filter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/filter/StringFilter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/filter/DateFilter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/filter/ListFilter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/filter/NumericFilter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-filtering/grid/filter/BooleanFilter.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-rowactions/js/Ext.ux.grid.RowActions.js');
    $this->view->headScript()->appendFile($baseUrl.'scripts/ext-grid-renderers.js');
    $this->view->headLink()->appendStylesheet($baseUrl.'scripts/ext/resources/css/ext-all.css');
    $this->view->headLink()->appendStylesheet($baseUrl.'scripts/ext/resources/css/xtheme-gray.css');
    $this->view->headLink()->appendStylesheet($baseUrl.'scripts/ext-rowactions/css/Ext.ux.grid.RowActions.css');
    /**/
    /* minified static
     $this->view->headScript()->appendFile($baseUrl.'scripts/grid-min.js');
     $this->view->headLink()->appendStylesheet($baseUrl.'styles/grid-min.css');
     /**/
    /* minified dynamic
     $this->view->headScript()->appendFile($baseUrl.'inc/min/?g=gridJs');
     $this->view->headLink()->appendStylesheet($baseUrl.'inc/min/?g=gridCss');
     /**/
      
    $this->view->headLink()->appendStylesheet($baseUrl.'styles/grid.css');
  }
}