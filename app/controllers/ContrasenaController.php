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

class ContrasenaController extends CustomController 
{   
 
	public function indexAction() 
    { 
    	$this->view->pageTitle = "Actualización de Contraseña";
    	$form = new FormContrasena();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$session = new Zend_Session_Namespace();
				$usuario = Usuario::findByPK($session->uid);
				$usuario->setPassword($form->getValue('contrasena'));
				Zend_Registry::get('messagehandler')->add('INFO', 'Se actualizó su contraseña.');
				
				return $this->_redirect(getControllerUrl('index'));
			}
    	}
		$this->view->form = $form;
    }
   
} 