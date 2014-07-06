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

class AdscripcionculturalController extends CustomController 
{   
	public function indexAction() 
    {    	
    	$this->view->pageTitle = "Adscripciones Culturales";

		$this->getHelper('dataGridManager')->initGrid('adscripcion_cultural',
							'adscripcioncultural_id', 
							array ( 
								"adscripcion_cultural:adscripcioncultural_nombre",
								"adscripcion_cultural:adscripcioncultural_descripcion"
							)
		);
    } 
    
    
    
	public function viewAction() 
    { 
    	$this->view->pageTitle = "Adscripción Cultural";
    	$this->view->adscripcioncultural = AdscripcionCultural::findByPK($this->getRequest()->getParam('id'));
    }
    
	public function editAction() 
    { 
    	$this->view->pageTitle = "Edición de Adscripción Cultural";
    	$adscripcioncultural = AdscripcionCultural::findByPK($this->getRequest()->getParam('id'));
    	$form = new FormAdscripcionCultural();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$adscripcioncultural->setAll($form->getValues());
				$adscripcioncultural->save();
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos de la adscripción cultural.');
				return $this->_redirect(getControllerUrl('adscripcioncultural'));
			}
    	}
    	else
    	{
    		$form->populate((array)$adscripcioncultural);
    	}
		$this->view->form = $form;
    }
    
	public function deleteAction() 
    { 
    	if (!AdscripcionCultural::isInUse($this->getRequest()->getParam('id')))
    	{
    		$adscripcioncultural = AdscripcionCultural::findByPK($this->getRequest()->getParam('id'));
	    	AdscripcionCultural::delete($adscripcioncultural);
	    	Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos de la Adscripción Cultural.');
    	}
    	else
    	{
    		Zend_Registry::get('messagehandler')->add('ERROR', 'No se puede eliminar la Adscripción Cultural porque aún está en uso.');
    	}
    	return $this->_redirect(getControllerUrl('adscripcioncultural'));
    }
   
} 