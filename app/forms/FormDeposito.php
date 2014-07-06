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

class FormDeposito extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Depósito');
		$this->setAttrib('id', 'form_deposito');	

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$nombre = new Zend_Form_Element_Text('nombre');
		$nombre->setLabel('Nombre*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
	
		$domicilio = new Zend_Form_Element_Textarea('domicilio');
		$domicilio->setLabel('Domicilio');
				
		$paises = array(''=>'--Seleccione--');
		$paises_datos = Pais::findAll(true); 
		foreach ( $paises_datos as $k=>$v)
		{
			$paises[$k] = $v;
		}
		$pais_id = new Zend_Form_Element_Select('pais_id');
		$pais_id->setLabel('País')
				->setmultiOptions($paises)
				->addValidator(new Zend_Validate_InArray(array_keys($paises_datos)));

		$provincias_datos = Provincia::findAll(true); 
		$provincia_id = new Zend_Form_Element_Select('provincia_id');
		$provincia_id->setLabel('Provincia')
					 ->addValidator(new Zend_Validate_InArray(array_keys($provincias_datos)));
		
		$hidden_provincia_id = new Zend_Form_Element_Hidden('hidden_provincia_id');
		$hidden_provincia_id->setDecorators(array('ViewHelper'));
		
		$departamentos_datos = Departamento::findAll(true); 		
		$departamento_id = new Zend_Form_Element_Select('departamento_id');
		$departamento_id->setLabel('Departamento')
						->addValidator(new Zend_Validate_InArray(array_keys($departamentos_datos)));
		
		$hidden_departamento_id = new Zend_Form_Element_Hidden('hidden_departamento_id');
		$hidden_departamento_id->setDecorators(array('ViewHelper'));

		$localidades_datos = Localidad::findAll(true); 
		$localidad_id = new Zend_Form_Element_Select('localidad_id');
		$localidad_id->setLabel('Localidad')
					 ->addValidator(new Zend_Validate_InArray(array_keys($localidades_datos)));
					 
		$hidden_localidad_id = new Zend_Form_Element_Hidden('hidden_localidad_id');
		$hidden_localidad_id->setDecorators(array('ViewHelper'));

		$telefono = new Zend_Form_Element_Text('telefono');
		$telefono->setLabel('Tel.')
				 ->addValidator('stringLength', false, array(0, 255));
		
		$fax = new Zend_Form_Element_Text('fax');
		$fax->setLabel('Fax')
			->addValidator('stringLength', false, array(0, 255));
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('E-mail')
			  ->addValidator('EmailAddress')
			  ->addValidator('stringLength', false, array(0, 255));
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		$this->addElements(
			array(
				$id, 
				$nombre, 
				$domicilio, 
				$pais_id, 
				$provincia_id,
				$hidden_provincia_id, 
				$departamento_id,
				$hidden_departamento_id,
				$localidad_id, 
				$hidden_localidad_id,
				$telefono,
				$fax,
				$email,
				$submit
			)
		);
	}
}