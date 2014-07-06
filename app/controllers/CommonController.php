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

class CommonController extends Zend_Controller_Action 
{ 
    public function navigationAction() 
    { 
		$this->getHelper("ViewRenderer")->setNoRender();
		
    	$session = new Zend_Session_Namespace();
    	$usuario = Usuario::findByPK($session->uid);
   		if ($usuario->id )
   		{
   			$sections = array(
				'index' => 'Inicio',
				'tenedor' => 'Tenedores',
				'deposito' => 'Depósitos',
				'coleccion' => 'Colecciones',
				'yacimiento' => 'Yacimientos',
				'lote' => 'Lotes',
				'objeto' => 'Objetos',
   				'pais' => 'Países',
   				'adscripcioncultural' => 'Adsc. Culturales',
   				'tipomaterial' => 'Tipos de Material',
				'usuario' => 'Usuarios',
   				'seguridad' => 'Seguridad'
			);
   		}
   		else
   		{
   			$sections = array(
				'index' => 'Inicio'
			);
   		}
   		
		
		
		$currentNavSection = $this->_getParam('controller');
		
		
		$linkAttributes = array();
		
		foreach ($sections as $k=>$v)
		{
			if ($this->_getParam('controller') == $k)
			{
				$linkAttributes[$k] = array('class'=>'selected');
			}
			else
			{
				$linkAttributes[$k] = array();
			}
		}	
		$this->view->sections = $sections;	
		$this->view->linkAttributes = $linkAttributes;
		
        $this->getResponse()->append('navigation', $this->view->render('common/navigation.phtml'));
    }
    
	public function messagesAction() 
    { 
		$this->getHelper("ViewRenderer")->setNoRender();
		$messages = Zend_Registry::get('messagehandler')->get();
		
		if(is_array($messages))
		{
			$this->view->messages = $messages;
			$this->getResponse()->append('messages', $this->view->render('common/messages.phtml'));
		}
    }
    
	public function loginAction()
    {
 		$this->getHelper("ViewRenderer")->setNoRender();
 		
 		$session = new Zend_Session_Namespace();
    	$usuario = Usuario::findByPK($session->uid);
    	
   		if ($usuario->id)
   		{
   			$this->view->usuario = $usuario;
   			$this->view->rol = Rol::findByPK($usuario->rol_id);
   			$this->view->fotografia = Fotografia::findByPK($usuario->fotografia_id);
   			$this->view->img_folder = Zend_Registry::get('config')->img_folder;
        	$this->getResponse()->append('userinfo', $this->view->render('common/userinfo.phtml'));
   		}
   		else
   		{
	    	$form = new FormLogin();
	    	if ($this->getRequest()->isPost())
	    	{
	    		if ($form->isValid($_POST)) 
				{	
					$usuario = Usuario::authenticate($form->getValue('usuario'), $form->getValue('contrasena'));	
	            	if ($usuario->id)
	            	{ 
	            		$session->uid = $usuario->id;

	            		$this->_redirect(getControllerUrl('index'));
	            		$this->dispatch();
	            	}
	            	else
	            	{//	session_unregister('UID');
	            		Zend_Registry::get('messagehandler')->add('ERROR', 'Nombre de usuario o contraseña incorrectos.');
	            	}
				}
	    	}
	    	$this->view->form = $form;
	    	$this->getResponse()->append('userinfo', $this->view->render('common/login.phtml'));
   		}	
    	
    }
} 