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


class FormFechado extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		
		$this->setName('Edición de Investigación');
		$this->setAttrib('id', 'form_fechado');

		$tipos_fecha = array();
		$tipos_fecha_datos = TipoFecha::findAll(true); 
		foreach ( $tipos_fecha_datos as $k=>$v)
		{
			$tipos_fecha[$k] = $v;
		}

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$yacimiento_id = new Zend_Form_Element_Hidden('yacimiento_id');
		$yacimiento_id->setDecorators(array('ViewHelper'))
					  ->setRequired(true);
		
		$cod_laboratorio = new Zend_Form_Element_Text('cod_laboratorio');
		$cod_laboratorio->setLabel('Cód. Laboratorio')
				->addFilter('StringTrim')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 20))
				->addValidator(
					'regex', true, array( 
						'pattern' => '/^([a-zA-Z0-9\-\s\/]*)$/',
						'messages' => array(
                			Zend_Validate_Regex::NOT_MATCH => 'solo se permiten letras a-z (sin ñ ni acentos), números 0-9, - y /'
            			)
            		)
            	);
            	
        $fecha_c14 = new Zend_Form_Element_Text('fecha_c14');
		$fecha_c14->setLabel('Fecha C14')
				->addFilter('StringTrim')
				->addValidator('digits');
            	
        $fecha_c14_desvio = new Zend_Form_Element_Text('fecha_c14_desvio');
		$fecha_c14_desvio->setLabel('Fecha C14 desvío')
				->addFilter('StringTrim')
				->addValidator('digits');
		
        $fecha_calibrada_2s = new Zend_Form_Element_Text('fecha_calibrada_2s');
		$fecha_calibrada_2s->setLabel('Fecha Calibrada 2ς')
				->addFilter('StringTrim')
				->addValidator('digits');
				
		$fecha_calibrada_2s_tipo_fecha_id = new Zend_Form_Element_Select('fecha_calibrada_2s_tipo_fecha_id');
		$fecha_calibrada_2s_tipo_fecha_id->setLabel('Tipo')
					  ->setmultiOptions($tipos_fecha)
					  ->addValidator(new Zend_Validate_InArray(array_keys($tipos_fecha)));
					  
		$fecha_calibrada_2s_desvio = new Zend_Form_Element_Text('fecha_calibrada_2s_desvio');
		$fecha_calibrada_2s_desvio->setLabel('Fecha Calibrada 2Σ desvío')
				->addFilter('StringTrim')
				->addValidator('digits');
        
        
		$fecha_calibrada_2s_desvio_tipo_fecha_id = new Zend_Form_Element_Select('fecha_calibrada_2s_desvio_tipo_fecha_id');
		$fecha_calibrada_2s_desvio_tipo_fecha_id->setLabel('Tipo')
					  ->setmultiOptions($tipos_fecha)
					  ->addValidator(new Zend_Validate_InArray(array_keys($tipos_fecha)));
        
        $fecha_calendarica = new Zend_Form_Element_Text('fecha_calendarica');
		$fecha_calendarica->setLabel('Fecha Calendárica')
				->addFilter('StringTrim')
				->addValidator('digits');
		
		$fecha_calendarica_tipo_fecha_id = new Zend_Form_Element_Select('fecha_calendarica_tipo_fecha_id');
		$fecha_calendarica_tipo_fecha_id->setLabel('Tipo')
					  ->setmultiOptions($tipos_fecha)
					  ->addValidator(new Zend_Validate_InArray(array_keys($tipos_fecha)));


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/fechado.phtml'))
		));
		
		$this->addElements(
			array(
				$id,
				$yacimiento_id,
				$cod_laboratorio,
				$fecha_c14,
				$fecha_c14_desvio,
				$fecha_calibrada_2s,
				$fecha_calibrada_2s_tipo_fecha_id,
				$fecha_calibrada_2s_desvio,
				$fecha_calibrada_2s_desvio_tipo_fecha_id,
				$fecha_calendarica,
				$fecha_calendarica_tipo_fecha_id,
				$submit
			)
		);
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
		
	}
}