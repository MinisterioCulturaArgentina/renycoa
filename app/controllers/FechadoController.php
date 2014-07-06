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

class FechadoController extends CustomController  
{   
	
		
	public function init()
    {       
    	parent::init();
   		$this->view->yacimiento_id = $this->getRequest()->getParam('yacimiento_id');
   				
   		$this->view->mode = 'edit';
		if ($this->getRequest()->getParam('mode') == 'view')
		{
			$this->view->mode = 'view';
		}
    }
    
	public function indexAction() 
    {   
    	$this->view->pageTitle = "Fechados"; 	
		$this->_helper->layout->setLayout('iframe');
		
		$this->getHelper('dataGridManager')->initGrid('fechado',
							'fechado_id', 
							array ( 
								"fechado:fechado_cod_laboratorio",
								"fechado:fechado_fecha_c14",
								"fechado:fechado_fecha_calibrada_2s",
								"fechado:fechado_fecha_calendarica"
							),
							array (
								"yacimiento:yacimiento_id" => $this->getRequest()->getParam('yacimiento_id')
							)
		);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Fechado";
    	$this->_helper->layout->setLayout('iframe');
    	$this->view->fechado = Fechado::findByPK($this->getRequest()->getParam('id'));
    }
    
    
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Fechado";
    	$this->_helper->layout->setLayout('iframe');
    	$fechado = Fechado::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormFechado();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$fechado->setAll($form->getValues());
				$fechado->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del fechado.');
				return $this->_redirect(getControllerUrl('fechado','index',array('yacimiento_id'=> +$this->getRequest()->getParam('yacimiento_id'))));
			}
    	}
    	else
    	{	
    		$form->populate((array)$fechado);
    		$form->populate(array('yacimiento_id' => $this->getRequest()->getParam('yacimiento_id')));
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	$this->_helper->layout->setLayout('iframe');
    	$fechado = Fechado::findByPK($this->getRequest()->getParam('id'));
    	Fechado::delete($fechado);
    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del fechado.');
    	return $this->_redirect(getControllerUrl('fechado','index',array('yacimiento_id'=> +$this->getRequest()->getParam('yacimiento_id'))));
    }

} 