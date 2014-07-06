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

class FormAdscripcionCultural extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Adscripción Cultural');
		$this->setAttrib('id', 'form_adscripcioncultural');	

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$nombre = new Zend_Form_Element_Text('nombre');
		$nombre->setLabel('Nombre*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255))
				->addValidator(new dotDev_Validate_UniqueKey('AdscripcionCultural','nombre','id'));
				
		$descripcion = new Zend_Form_Element_Text('descripcion');
		$descripcion->setLabel('Descripción')
				->addValidator('stringLength', false, array(0, 255));
	
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		$this->addElements(
			array(
				$id,
				$nombre, 
				$descripcion, 
				$submit
			)
		);
	}
}