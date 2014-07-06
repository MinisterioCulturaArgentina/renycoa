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

class NoRewriteRouter implements Zend_Controller_Router_Route_Interface
{
	protected $_current = array();

	public static function getInstance(Zend_Config $config)
	{
		return new self();
	}

	public function match($path)
	{
		$frontController = Zend_Controller_Front::getInstance();
		$request = $frontController->getRequest();
		
		$baseUrl = $request->getBaseUrl();
		
		if (strpos($baseUrl, 'index.php') !== false)
		{
			$url = str_replace('index.php', '', $baseUrl);
			$request->setBaseUrl($url);
		}
		
		$params = $request->getParams();
		
		if (	array_key_exists('module', $params)
				|| array_key_exists('controller', $params)
				|| array_key_exists('action', $params)	)
		{
		
			$module = $request->getParam('module', $frontController->getDefaultModule());
			$controller = $request->getParam('controller', $frontController->getDefaultControllerName());
			$action = $request->getParam('action', $frontController->getDefaultAction());
			
			$result = array(
				'module' => $module,
                'controller' => $controller, 
                'action' => $action, 
			);
			$this->_current = $result;
			return $result;
		}
		return false;
	}
		
	
	public function assemble($data = array(), $reset = false )
	{
		$frontController = Zend_Controller_Front::getInstance();
		
		if(	!array_key_exists('module', $data) && !$reset
			&& array_key_exists('module', $this->_current)
			&& $this->_current['module'] != $frontController->getDefaultModule()) 
		{
			$data = array_merge(array('module'=>$this->_current['module']), $data);
		}
		
		if(	!array_key_exists('controller', $data) && !$reset
			&& array_key_exists('controller', $this->_current)
			&& $this->_current['controller'] != $frontController->getDefaultControllerName()) 
		{
			$data = array_merge(array('controller'=>$this->_current['controller']), $data);
		}
		
		if(	!array_key_exists('action', $data) && !$reset
			&& array_key_exists('action', $this->_current)
			&& $this->_current['action'] != $frontController->getDefaultAction()) 
		{
			$data = array_merge(array('action'=>$this->_current['action']), $data);
		}
		
		$url = '';
		if(!empty($data)) 
		{
			$urlParts = array();
			foreach($data as $key=>$value) 
			{
				$urlParts[] = $key . '=' . $value;
			}
			$url = '?' . implode('&', $urlParts);
		}		
		return $url;
	}
}