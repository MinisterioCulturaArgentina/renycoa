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

class InvestigacionController extends CustomController  
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
    	$this->view->pageTitle = "Investigaciones"; 	
		$this->_helper->layout->setLayout('iframe');
		
		$this->getHelper('dataGridManager')->initGrid('investigacion',
							'investigacion_id', 
							array ( 
								"investigacion:investigacion_investigadores",
								"investigacion:investigacion_institucion_interviniente",
								"investigacion:investigacion_institucion_depositaria",
								"investigacion:investigacion_ano_inicio",
								"investigacion:investigacion_ano_fin",
								"yacimiento:yacimiento_id"
							),
							array (
								"yacimiento:yacimiento_id" => $this->getRequest()->getParam('yacimiento_id')
							)
		);
		$this->view->years = getYearsArray();
		
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Investigación";
    	$this->_helper->layout->setLayout('iframe');
    	$this->view->investigacion = Investigacion::findByPK($this->getRequest()->getParam('id'));
    }
    
    
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Investigación";
    	$this->_helper->layout->setLayout('iframe');
    	$investigacion = Investigacion::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormInvestigacion();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$investigacion->setAll($form->getValues());
				$investigacion->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos de la investigación.');
				return $this->_redirect(getControllerUrl('investigacion','index',array('yacimiento_id'=> +$this->getRequest()->getParam('yacimiento_id'))));
			}
    	}
    	else
    	{	
    		$form->populate((array)$investigacion);
    		$form->populate(array('yacimiento_id' => $this->getRequest()->getParam('yacimiento_id')));
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	$this->_helper->layout->setLayout('iframe');
    	$investigacion = Investigacion::findByPK($this->getRequest()->getParam('id'));
    	Investigacion::delete($investigacion);
    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos de la investigación.');
    	return $this->_redirect(getControllerUrl('investigacion','index',array('yacimiento_id'=> +$this->getRequest()->getParam('yacimiento_id'))));
    }

} 