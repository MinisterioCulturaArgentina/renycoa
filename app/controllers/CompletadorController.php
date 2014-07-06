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

class CompletadorController extends CustomController 
{   
		public function getAction() 
    { 
    	$this->_helper->layout->setLayout('ajax');
    	$this->getHelper("ViewRenderer")->setNoRender();

    	$completador = Completador::findByTipoDocCompletadorAndNroDoc(
    	  $this->getRequest()->getParam('tipo_doc_completador_id'),
    	  $this->getRequest()->getParam('nro_doc')
    	);
    	if (is_object($completador) && $completador->id)
    	{
    		echo $completador->nombre_completo;
    	}
    }

} 