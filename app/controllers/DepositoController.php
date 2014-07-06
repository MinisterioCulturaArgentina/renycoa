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

class DepositoController extends CustomController 
{   
	public function indexAction() 
    {    	
    	$this->view->pageTitle = "Depósitos";
		$this->getHelper('dataGridManager')->initGrid('deposito',
							'deposito_id', 
							array ( 
								"deposito:deposito_nombre",
								"deposito:deposito_domicilio",
								"provincia:provincia_descripcion",
								"provincia:provincia_id"
							)
		);
		$this->view->provincias = Provincia::findAllWithDeposito(true);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Depósito";
    	$this->view->deposito = Deposito::findByPK($this->getRequest()->getParam('id'));
    }
    
    public function getAction() 
    { 
    	$this->_helper->layout->setLayout('ajax');

    	if ($this->getRequest()->getParam('id'))
    	{
    		$deposito = Deposito::findByPK($this->getRequest()->getParam('id'));
    	}

    	if (isset($deposito) && is_object($deposito))
    	{
    		$this->view->deposito = $deposito;
    	}
    	else
    	{
    		$this->getHelper("ViewRenderer")->setNoRender();
    	}
    }
    
    
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Depósito";
    	$deposito = Deposito::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormDeposito();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$deposito->setAll($form->getValues());
				$deposito->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del Depósito.');
				return $this->_redirect(getControllerUrl('deposito'));
			}
    	}
    	else
    	{
    		$form->populate((array)$deposito);
    		$form->populate(array('hidden_provincia_id' => $deposito->provincia_id ));
    		$form->populate(array('hidden_departamento_id' => $deposito->departamento_id ));
    		$form->populate(array('hidden_localidad_id' => $deposito->localidad_id ));
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	$deposito = Deposito::findByPK($this->getRequest()->getParam('id'));
    	Deposito::delete($deposito);
    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del Depósito.');
    	return $this->_redirect(getControllerUrl('deposito'));
    }

} 