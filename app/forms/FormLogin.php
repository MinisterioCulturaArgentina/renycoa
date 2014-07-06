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

class FormLogin extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Entrada de usuarios');
		$this->setAttrib('id', 'form_login');
		
		$hash = new Zend_Form_Element_Hash('hash');
		$hash->setSalt('form_login')
			 ->setDecorators(array('ViewHelper'));

		$usuario = new Zend_Form_Element_Text('usuario');
		$usuario->setLabel('Usuario')
				->setRequired(true);
					
		$contrasena = new Zend_Form_Element_Password('contrasena');
		$contrasena->setLabel('Contraseña')
			 	   ->setRequired(true);
			
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Ingresar');

		$this->addElements(
			array(	
				$hash,
				$usuario, 
				$contrasena, 
				$submit
			)
		);
	}
}