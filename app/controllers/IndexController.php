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
 
class IndexController extends CustomController 
{ 
	public function preDispatch()
    {
    	parent::preDispatch();
    	$action = $this->_getParam('action');
    	$mensaje = Mensaje::findByPK($this->getRequest()->getParam('id'));
    	
    	if ($mensaje->id && $action == 'delete')
    	{
    		$session = new Zend_Session_Namespace();
		    $usuario = Usuario::findByPK($session->uid);
	   		
    		$resource =  'mensaje_others';
			if ($mensaje->usuario_id == $usuario->id)
			{
				$resource = 'mensaje_self';
			}
	   		$acl = Zend_Registry::get('acl');
		    if (!$acl->isAllowed($usuario->rol_id, $resource,$action))
		    {
		    	Zend_Registry::get('messagehandler')->add('ERROR', 'No tiene privilegios para realizar esta acción.');
		    		
    			$this->_redirect(getControllerUrl('index','index'));
	   		}
    	}
    }
    
	public function indexAction() 
    {    	
		$this->getHelper('dataGridManager')->initGrid('mensaje',
							'mensaje_id', 
							array ( 
								"mensaje:mensaje_mensaje",
								"mensaje:mensaje_created_at",
								"usuario:usuario_usuario",
								"usuario:usuario_id"
							),
							array (
								"mensaje:mensaje_administrador" => array (
									'OPERATOR' => '!=', 
									'VALUE' => '1'
								)
							),
							'g_usuarios',
							'mensaje:mensaje_created_at',
							'DESC'
							
		);
		
		$this->getHelper('dataGridManager')->initGrid('mensaje',
							'mensaje_id', 
							array ( 
								"mensaje:mensaje_mensaje",
								"mensaje:mensaje_created_at",
								"usuario:usuario_usuario",
								"usuario:usuario_id"
							),
							array (
								"mensaje:mensaje_administrador" => array (
									'OPERATOR' => '=', 
									'VALUE' => '1'
								)
							),
							'g_admin',
							'mensaje:mensaje_created_at',
							'DESC'
		);
		$this->view->usuarios = Usuario::findAllWithMensaje(true);
		
        $session = new Zend_Session_Namespace();
    	$usuario = Usuario::findByPK($session->uid);
   		if ($usuario->id )
   		{
   			$this->view->loggedin = true;
   		}
   		else
   		{
   			$this->view->loggedin = false;
   		}
   		$this->view->usuario = $usuario;
   		$this->view->acl = Zend_Registry::get('acl');
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->mensaje = Mensaje::findByPK($this->getRequest()->getParam('id'));
    	$session = new Zend_Session_Namespace();
   		$this->view->usuario = Usuario::findByPK($session->uid);
   		$this->view->acl = Zend_Registry::get('acl');
    }
    
    
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Mensaje";
    	
    	$form = new FormMensaje();
    	$session = new Zend_Session_Namespace();
	    $usuario = Usuario::findByPK($session->uid);
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$mensaje = new Mensaje();
				$mensaje->mensaje = $form->getValue('mensaje');
				$mensaje->usuario_id = $usuario->id;
				$mensaje->administrador = $usuario->isAdmin();
				$mensaje->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Su Mensaje se grabó correctamente.');
				return $this->_redirect(getControllerUrl('index'));
			}
    	}
		$this->view->form = $form;
		$this->view->usuario = $usuario;
    }
    
	public function deleteAction() 
    { 
    	$mensaje = Mensaje::findByPK($this->getRequest()->getParam('id'));
    	
    	$session = new Zend_Session_Namespace();
    	$usuario = Usuario::findByPK($session->uid);
    	$acl = Zend_Registry::get('acl');
    	
    	if ($mensaje->usuario_id != $usuario->id && 
    		!$acl->isAllowed($usuario->rol_id, 'mensaje_others','delete'))
	    {
	   		Zend_Registry::get('messagehandler')->add('ERROR', 'No tiene privilegios para realizar esta acccón.');
		 	$this->_redirect(getControllerUrl('index'));	
	    }
	    
    	Mensaje::delete($mensaje);
    	Zend_Registry::get('messagehandler')->add('INFO', 'El mensaje fue eliminado.');
    	return $this->_redirect(getControllerUrl('index'));
    }
    
    function logoutAction()
    {
    	$session = new Zend_Session_Namespace();
		$session->uid = null;
    	return $this->_redirect(getControllerUrl('index'));
    }
} 