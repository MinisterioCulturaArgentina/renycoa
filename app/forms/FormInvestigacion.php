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

class FormInvestigacion extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Investigación');
		$this->setAttrib('id', 'form_investigacion');	

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$yacimiento_id = new Zend_Form_Element_Hidden('yacimiento_id');
		$yacimiento_id->setDecorators(array('ViewHelper'))
					  ->setRequired(true);
		
		$institucion_interviniente = new Zend_Form_Element_Text('institucion_interviniente');
		$institucion_interviniente->setLabel('Institución Interviniente');
		
		$institucion_depositaria = new Zend_Form_Element_Text('institucion_depositaria');
		$institucion_depositaria->setLabel('Institución Depositaria');
				
		$investigadores = new Zend_Form_Element_Text('investigadores');
		$investigadores->setLabel('Investigadores*')
					   ->setRequired(true);

					   
		$proyecto = new Zend_Form_Element_Text('proyecto');
		$proyecto->setLabel('Proyecto');
	
				
		$years = array(''=>'--Seleccione--');
		$years_datos = getYearsArray(); 
		foreach ( $years_datos as $k=>$v)
		{
			$years[$k] = $v;
		}
		$ano_inicio = new Zend_Form_Element_Select('ano_inicio');
		$ano_inicio->setLabel('Año de inicio')
				->setmultiOptions($years)
				->addValidator(new Zend_Validate_InArray(array_keys($years_datos)));
		
		$ano_fin = new Zend_Form_Element_Select('ano_fin');
		$ano_fin->setLabel('Año de fin')
				->setmultiOptions($years)
				->addValidator(new Zend_Validate_InArray(array_keys($years_datos)))
				->addValidator(new dotDev_Validate_GreaterThanEqualElement('ano_inicio'), true);


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		$this->addElements(
			array(
				$id,
				$yacimiento_id,
				$investigadores,
				$institucion_interviniente,
				$institucion_depositaria,
				$proyecto,
				$ano_inicio,
				$ano_fin,
				$submit
			)
		);
	}
}