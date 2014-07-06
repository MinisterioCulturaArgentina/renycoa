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

class CitabibliograficaController extends CustomController  
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
    	$this->view->pageTitle = "Citas Bibliográficas"; 	
		$this->_helper->layout->setLayout('iframe');
		
		$this->getHelper('dataGridManager')->initGrid('cita_bibliografica',
							'citabibliografica_id', 
							array ( 
								"cita_bibliografica:citabibliografica_ano",
								"cita_bibliografica:citabibliografica_autores",
								"cita_bibliografica:citabibliografica_cita"
							),
							array (
								"yacimiento:yacimiento_id" => $this->getRequest()->getParam('yacimiento_id')
							)
		);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Cita Bibliográfica";
    	$this->_helper->layout->setLayout('iframe');
    	$this->view->cita_bibliografica = CitaBibliografica::findByPK($this->getRequest()->getParam('id'));
    }
    
    
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Cita Bibliográfica";
    	$this->_helper->layout->setLayout('iframe');
    	$cita_bibliografica = CitaBibliografica::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormCitaBibliografica();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$cita_bibliografica->setAll($form->getValues());
				$cita_bibliografica->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos de la cita bibliográfica.');
				return $this->_redirect(getControllerUrl('citabibliografica','index',array('yacimiento_id'=> +$this->getRequest()->getParam('yacimiento_id'))));
			}
    	}
    	else
    	{	
    		$form->populate((array)$cita_bibliografica);
    		$form->populate(array('yacimiento_id' => $this->getRequest()->getParam('yacimiento_id')));
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	$this->_helper->layout->setLayout('iframe');
    	$cita_bibliografica = CitaBibliografica::findByPK($this->getRequest()->getParam('id'));
    	CitaBibliografica::delete($cita_bibliografica);
    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos de la cita bibliográfica.');
    	return $this->_redirect(getControllerUrl('citabibliografica','index',array('yacimiento_id'=> +$this->getRequest()->getParam('yacimiento_id'))));
    }

} 