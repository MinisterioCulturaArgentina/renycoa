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

class PaisController extends CustomController 
{   
	public function indexAction() 
    {    	
    	$this->view->pageTitle = "Países";

		$this->getHelper('dataGridManager')->initGrid('pais',
							'pais_id', 
							array ( 
								"pais:pais_descripcion"
							)
		);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "País";
    	$this->view->pais = Pais::findByPK($this->getRequest()->getParam('id'));
    }
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de País";
    	$pais = Pais::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormPais();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$pais->setAll($form->getValues());
				$pais->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del país.');
				return $this->_redirect(getControllerUrl('pais'));
			}
    	}
    	else
    	{
    		$form->populate((array)$pais);
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	if (!Pais::isInUse($this->getRequest()->getParam('id')))
    	{
    		$pais = Pais::findByPK($this->getRequest()->getParam('id'));
	    	Pais::delete($pais);
	    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del país.');
    	}
    	else
    	{
    		Zend_Registry::get('messagehandler')->add('ERROR', 'No se puede eliminar el país porque aún está en uso.');
    	}
    	return $this->_redirect(getControllerUrl('pais'));
    }
   
} 