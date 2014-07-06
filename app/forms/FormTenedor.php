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

class FormTenedor extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Tenedor');
		$this->setAttrib('id', 'form_tenedor');	

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$nombres = new Zend_Form_Element_Text('nombres');
		$nombres->setLabel('Nombres*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
	
		$apellido = new Zend_Form_Element_Text('apellido');
		$apellido->setLabel('Apellido*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
		
		$tipo_docs = array(''=>'--Seleccione--');
		$tipo_docs_datos = TipoDoc::findAll(true);
		foreach ( $tipo_docs_datos as $k=>$v)
		{
			$tipo_docs[$k] = $v;
		}
		$tipo_doc_id = new Zend_Form_Element_Select('tipo_doc_id');
		$tipo_doc_id->setLabel('Tipo doc.*')
					->setmultiOptions($tipo_docs)
					->setRequired(true)
					->addValidator(new Zend_Validate_InArray(array_keys($tipo_docs_datos)));
					
		$nro_doc = new Zend_Form_Element_Text('nro_doc');
		$nro_doc->setLabel('Nro. doc. / Sigla*')
				->setRequired(true)
				->addValidator('Digits')
				->addValidator('stringLength', false, array(7, 11));
		
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
			  
		$temporario = new Zend_Form_Element_Checkbox('temporario');
		$temporario->setLabel('Temporario');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		$this->addElements(
			array(
				$id, 
				$nombres, 
				$apellido, 
				$tipo_doc_id, 
				$nro_doc, 
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
				$temporario,
				$submit
			)
		);
		/*$this->addDisplayGroup(array('nombres', 'apellido'), 'datos');
		$this->getDisplayGroup('datos')->setLegend('Datos Personales:');
		
		$this->addDisplayGroup(array('submit'), 'actions');
		$this->setDisplayGroupDecorators(array('FormElements'));*/
	}
	
	public function isValid($data)
	{

		if (isset($_REQUEST['tipo_doc_id']) && $_REQUEST['tipo_doc_id'] == '4' ) //SIG
		{
			$this->getElement('nro_doc')->removeValidator('Digits');
			$this->getElement('nro_doc')->removeValidator('stringLength');
			$this->getElement('nro_doc')
					 ->addValidator('stringLength', false, array(0, 50))
				   ->addValidator(
				     'regex', false, array(
				  	 	 'pattern' => '/^([-a-z0-9 _\\/])*$/i',
				  	 	 'messages' => array(
				  		   Zend_Validate_Regex::NOT_MATCH => 'solo se admiten letras (sin ñ ni acentos), números y los separadores: / - _'
				  	   )
				     )
					 );
		}
		return parent::isValid($data);
	}
}