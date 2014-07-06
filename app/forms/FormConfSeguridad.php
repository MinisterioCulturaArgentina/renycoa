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

class FormConfSeguridad extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de parámetros de seguridad');
		$this->setAttrib('id', 'form_confseguridad');	

		$min_letras = new Zend_Form_Element_Text('min_letras');
		$min_letras->setLabel('Cantidad mínima de letras*')
				   ->addValidator('Digits', true);
		
		$min_numeros = new Zend_Form_Element_Text('min_numeros');
		$min_numeros->setLabel('Cantidad mínima de números*')
				   ->addValidator('Digits', true);
		
		$min_simbolos = new Zend_Form_Element_Text('min_simbolos');
		$min_simbolos->setLabel('Cantidad mínima de símbolos*')
				   ->addValidator('Digits', true)
				   ->addValidator(new dotDev_Validate_ElementCharCount('simbolos'));
		
		$simbolos = new Zend_Form_Element_Text('simbolos');
		$simbolos->setLabel('Símbolos permitidos')
				 ->addFilter(new dotDev_Filter_UnrepeatChar());
		
		$largo_min = new Zend_Form_Element_Text('largo_min');
		$largo_min->setLabel('Largo mínimo de contraseña*')
				   ->setRequired(true)
				   ->addValidator('Digits', true)
				   ->addValidator(new dotDev_Validate_CharSumLessThanMax(array('min_letras', 'min_numeros', 'min_simbolos')), true)
				   ->addValidator('GreaterThan',false, array(0));
		
		$largo_max = new Zend_Form_Element_Text('largo_max');
		$largo_max->setLabel('Largo máximo de contraseña*')
				   ->setRequired(true)
				   ->addValidator('Digits', true)
				   ->addValidator(new dotDev_Validate_GreaterThanEqualElement('largo_min'), true);	
		
		$dias_caducidad = new Zend_Form_Element_Text('dias_caducidad');
		$dias_caducidad->setLabel('Cantidad de días de validez*')
				   ->addValidator('Digits', true)
				   ->setDescription('0 para que las contraseñas no caduquen.');
		
			  
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/conf_seguridad.phtml'))
		));
		
		$this->addElements(
			array(
				$min_letras,
				$min_numeros,
				$min_simbolos,
				$simbolos,
				$largo_min,
				$largo_max,
				$dias_caducidad
			)
		);
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
}