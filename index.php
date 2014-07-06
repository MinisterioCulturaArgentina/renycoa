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

//Error Reporting Level p/ desarrollo 
error_reporting(E_ALL | E_STRICT);  
ini_set('display_startup_errors', 1);  
ini_set('display_errors', 1); 
iconv_set_encoding('input_encoding','UTF-8');
iconv_set_encoding('output_encoding', 'UTF-8');
iconv_set_encoding('internal_encoding', 'UTF-8');
 
//TZ
date_default_timezone_set('America/Argentina/Buenos_Aires');

//Revertir Magic Quotes
if (get_magic_quotes_gpc()) 
{
	$in = array(&$_GET, &$_POST, &$_COOKIE);
    while (list($k,$v) = each($in)) 
    {
    	foreach ($v as $key => $val) 
    	{
    		if (!is_array($val)) 
    		{
    			$in[$k][$key] = stripslashes($val);
    			continue;
    		}
    		$in[] =& $in[$k][$key];
    	}
    }
    unset($in);
}


//Includes
set_include_path(	'inc' . PATH_SEPARATOR .
					'inc/dotDev' . PATH_SEPARATOR .
					'app/models' . PATH_SEPARATOR . 
					'app/forms' . PATH_SEPARATOR .
          			'app/grids' . PATH_SEPARATOR .
					get_include_path());  

//Helpers
require_once 'HelperFunctions.php';

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

//Levantar Config
$config = new Zend_Config(require 'config.php');
Zend_Registry::set('config', $config);

//Registrar Locale
Zend_Locale::setDefault('es_AR');
require_once 'lang.php';
$translate = new Zend_Translate('array',$es,'es');
Zend_Registry::set('Zend_Translate', $translate );

//Registrar DB
require 'ezSql/mysql/ez_sql.php';
$db = new db($config->db->params->username, $config->db->params->password, $config->db->params->dbname, $config->db->params->localhost);
$db->query('SET NAMES utf8');
Zend_Registry::set('db', $db );

//Registrar Permisos
require_once 'acl.php';
Zend_Registry::set('acl', $acl);

//Registrar Mensajes
$messagehandler = new MessageHandler();
Zend_Registry::set('messagehandler', $messagehandler);

//Helpers
Zend_Controller_Action_HelperBroker::addPath($config->base_path.'app/controllers/helpers');

//View
Zend_Layout::startMvc(array(
    'layoutPath' => $config->base_path . '/app/layouts'
));
$view = Zend_Layout::getMvcInstance()->getView();
$view->baseUrl = $config->base_url;

$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->view->doctype('XHTML1_TRANSITIONAL');  

//Front Controller
$frontController = Zend_Controller_Front::getInstance(); 
$frontController->throwExceptions(true); 
$frontController->setControllerDirectory('app/controllers'); 

if ($config->url_rewrite == false)
{
	$router = $frontController->getRouter();
	$router->addRoute('requestVars', new NoRewriteRouter());
}
$frontController->dispatch(); 

