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

class SeguridadController extends CustomController 
{   
 
	public function indexAction() 
    { 
    	$this->view->pageTitle = "Edición de parámetros de seguridad";
    	$confseguridad = ConfSeguridad::findCurrent();
    	$form = new FormConfSeguridad();
    	if ($this->getRequest()->isPost()) 
    	{
			if ($form->isValid($_POST)) 
			{
				$confseguridad->setAll($form->getValues());
				
				//TODO: Esto de normalizar a 0 debaría ser hecho con un filtro en los elem del form.
				$numericAttrs = array (
					'min_letras', 
					'min_numeros', 
					'min_simbolos', 
					'largo_min',
					'largo_max', 
					'dias_caducidad'
				);
				
				foreach ($numericAttrs as $attr)
				{
					if ($confseguridad->$attr == '')
						$confseguridad->$attr = 0;
				}
				
				$confSeguridadOrig = ConfSeguridad::findCurrent();
				
				if (!$confSeguridadOrig->isEqual($confseguridad))
				{
					$confseguridad->id = '';
					$confseguridad->save();
					Zend_Registry::get('messagehandler')->add('INFO', 'Se actualizaron los parámetros de seguridad.');
				}
				else
				{
					Zend_Registry::get('messagehandler')->add('INFO', 'No se actualizaron los parámetros de seguridad.');
				}
				
				return $this->_redirect(getControllerUrl('seguridad'));
			}
    	}
    	else
    	{
    		$form->populate((array)$confseguridad);
    	}
		$this->view->form = $form;
    }
   
} 