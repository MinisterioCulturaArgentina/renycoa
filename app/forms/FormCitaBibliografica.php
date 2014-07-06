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

class FormCitaBibliografica extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Cita Bibliográfica');
		$this->setAttrib('id', 'form_citabibliografica');	

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$yacimiento_id = new Zend_Form_Element_Hidden('yacimiento_id');
		$yacimiento_id->setDecorators(array('ViewHelper'))
					  ->setRequired(true);
					  
		$years = array(''=>'--Seleccione--');
		$years_datos = getYearsArray(); 
		foreach ( $years_datos as $k=>$v)
		{
			$years[$k] = $v;
		}
		$ano = new Zend_Form_Element_Select('ano');
		$ano->setLabel('Año*')
			->setRequired(true)
			->setmultiOptions($years)
			->addValidator(new Zend_Validate_InArray(array_keys($years_datos)));
				
		$autores = new Zend_Form_Element_Text('autores');
		$autores->setLabel('Autor/es*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
					   
		$cita = new Zend_Form_Element_Textarea('cita');
		$cita->setLabel('Cita*')
			 ->setRequired(true);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		$this->addElements(
			array(
				$id,
				$yacimiento_id,
				$autores,
				$cita,
				$ano,
				$submit
			)
		);
	}
}