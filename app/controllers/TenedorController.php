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

class TenedorController extends CustomController 
{   
	public function indexAction() 
    { 		
		$this->view->pageTitle = "Tenedores";
		$this->getHelper('dataGridManager')->initGrid('tenedor',
							'tenedor_id', 
							array ( 
								"tenedor:tenedor_nombres",
								"tenedor:tenedor_apellido",
								"tenedor:tenedor_colecciones",
								"coleccion:coleccion_id",
								"provincia:provincia_descripcion",
								"provincia:provincia_id"
							)
		);
		$this->view->provincias = Provincia::findAllWithTenedor(true);
		$this->view->colecciones = Coleccion::findAllWithTenedor(true);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Tenedor";
    	$this->view->tenedor = Tenedor::findByPK($this->getRequest()->getParam('id'));
    }
    
	public function getAction() 
    { 
    	$this->_helper->layout->setLayout('ajax');

    	if ($this->getRequest()->getParam('id'))
    	{
    		$tenedor = Tenedor::findByPK($this->getRequest()->getParam('id'));
    	}
    	else if($this->getRequest()->getParam('coleccion_id'))
    	{
    		$coleccion = Coleccion::findByPK($this->getRequest()->getParam('coleccion_id'));
    		$tenedor = Tenedor::findByPK($coleccion->tenedor_id);
    	}
    	if (isset($tenedor) && is_object($tenedor))
    	{
    		$this->view->tenedor = $tenedor;
    	}
    	else
    	{
    		$this->getHelper("ViewRenderer")->setNoRender();
    	}
    }
    
    
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Tenedor";
    	$tenedor = Tenedor::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormTenedor();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$tenedor->setAll($form->getValues());
				$tenedor->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del Tenedor.');
				return $this->_redirect(getControllerUrl('tenedor'));
			}
    	}
    	else
    	{
    		$form->populate((array)$tenedor);
    		$form->populate(array('hidden_provincia_id' => $tenedor->provincia_id ));
    		$form->populate(array('hidden_departamento_id' => $tenedor->departamento_id ));
    		$form->populate(array('hidden_localidad_id' => $tenedor->localidad_id ));
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	$tenedor = Tenedor::findByPK($this->getRequest()->getParam('id'));
    	Tenedor::delete($tenedor);
    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del Tenedor.');
    	return $this->_redirect(getControllerUrl('tenedor'));
    }

} 