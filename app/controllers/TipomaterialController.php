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

class TipomaterialController extends CustomController 
{   
	public function indexAction() 
    {    	
    	$this->view->pageTitle = "Tipos de material";

		$this->getHelper('dataGridManager')->initGrid('tipo_material',
							'tipomaterial_id', 
							array ( 
								"tipo_material:tipomaterial_descripcion"
							)
		);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Tipo de Material";
    	$this->view->tipomaterial = TipoMaterial::findByPK($this->getRequest()->getParam('id'));
    }
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Tipo de Material";
    	$tipomaterial = TipoMaterial::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormTipoMaterial();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$tipomaterial->setAll($form->getValues());
				$tipomaterial->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del tipo de material.');
				return $this->_redirect(getControllerUrl('tipomaterial'));
			}
    	}
    	else
    	{
    		$form->populate((array)$tipomaterial);
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	if (!TipoMaterial::isInUse($this->getRequest()->getParam('id')))
    	{
    		$tipomaterial = TipoMaterial::findByPK($this->getRequest()->getParam('id'));
	    	TipoMaterial::delete($tipomaterial);
	    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del tipo de material.');
    	}
    	else
    	{
    		Zend_Registry::get('messagehandler')->add('ERROR', 'No se puede eliminar el tipo de material porque aún está en uso.');
    	}
    	return $this->_redirect(getControllerUrl('tipomaterial'));
    }
   
} 