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

class FormColeccion extends Zend_Form
{
	public function __construct($options = null)
	{
		$this->addElementPrefixPath('dotDev', 'dotDev/');
		parent::__construct($options);
	
		$this->setName('Edición de Colección');
		$this->setAttrib('id', 'form_coleccion');	
		
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/coleccion.phtml'))
		));

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$sigla = new Zend_Form_Element_Text('sigla');
		$sigla->setLabel('Sigla*')
			  ->addFilter('StringTrim')
			  ->setRequired(true)
			  ->addValidator('stringLength', false, array(0, 50))
			  ->addValidator(
			  	'regex', false, array(
			  		'pattern' => '/^([-a-z0-9 _\\/])*$/i',
			  		'messages' => array(
			  			Zend_Validate_Regex::NOT_MATCH => 'solo se admiten letras (sin ñ ni acentos), números y los separadores: / - _'
			  		)
			  	)
			  )
			  ->addValidator(new dotDev_Validate_UniqueKey('Coleccion','sigla','id'));
	
		$nombre = new Zend_Form_Element_Text('nombre');
		$nombre->setLabel('Nombre descriptivo*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
		
		$sitio_arqueologico = new Zend_Form_Element_Text('sitio_arqueologico');
		$sitio_arqueologico->setLabel('Sitio Arqueológico');
		
		$observaciones = new Zend_Form_Element_Textarea('observaciones');
		$observaciones->setLabel('Observaciones');
		
		$paises_datos = array();  
		foreach ( Pais::findAll(true) as $k=>$v)
		{
			$paises_datos[$k] = $v;
		}
		$paises_all = new Zend_Form_Element_Multiselect('paises_all');
		$paises_all->setLabel('Posibles')
				->setmultiOptions($paises_datos);
		
		$paises_selected = new Zend_Form_Element_Multiselect('paises_selected');
		$paises_selected->setLabel('Asignados');
					  
		$paises = new Zend_Form_Element_Hidden('paises');
		$paises->setLabel('Países*')
			   ->setRequired(true);
		//TODO: agregar validador!!!


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

		$tenedores = array(''=>'--Seleccione--');
		foreach ( Tenedor::findAll(true) as $k=>$v)
		{
			$tenedores[$k] = $v;
		}
		$tenedor_id = new Zend_Form_Element_Select('tenedor_id');
		$tenedor_id->setLabel('Tenedor*')
					->setmultiOptions($tenedores)
					->setRequired(true);
					
		$depositos = array(''=>'--Seleccione--');
		foreach ( Deposito::findAll(true) as $k=>$v)
		{
			$depositos[$k] = $v;
		}
		$deposito_id = new Zend_Form_Element_Select('deposito_id');
		$deposito_id->setLabel('Depósito')
					->setmultiOptions($depositos);
					
		$deposito_temporario = new Zend_Form_Element_Checkbox('deposito_temporario');
		$deposito_temporario->setLabel('Temporario');
		
		$provisorio = new Zend_Form_Element_Checkbox('provisorio');
		$provisorio->setLabel('Provisorio');
		
		$fur = new Zend_Form_Element_Checkbox('fur');
		$fur->setLabel('FUR');
		
		$tipos_material_datos = array();  
		foreach ( TipoMaterial::findAll(true) as $k=>$v)
		{
			$tipos_material_datos[$k] = $v;
		}
		$tipos_material_all = new Zend_Form_Element_Multiselect('tipos_material_all');
		$tipos_material_all->setLabel('Posibles')
				->setmultiOptions($tipos_material_datos);
		
		$tipos_material_selected = new Zend_Form_Element_Multiselect('tipos_material_selected');
		$tipos_material_selected->setLabel('Asignados');
					  
		$tipos_material = new Zend_Form_Element_Hidden('tipos_material');
		$tipos_material->setLabel('Tipo de material');
		//TODO: agregar validador!!!
		
		$tipo_docs = array(''=>'--Seleccione--');
    $tipo_docs_datos = TipoDocCompletador::findAll(true);
    foreach ( $tipo_docs_datos as $k=>$v)
    {
      $tipo_docs[$k] = $v;
    }
    $tipo_doc_completador_id = new Zend_Form_Element_Select('tipo_doc_completador_id');
    $tipo_doc_completador_id->setLabel('Tipo doc.')
          ->setmultiOptions($tipo_docs)
          ->addValidator(new Zend_Validate_InArray(array_keys($tipo_docs_datos)));
          
    $completador_nro_doc = new Zend_Form_Element_Text('completador_nro_doc');
    $completador_nro_doc->setLabel('Nro. doc.')
        ->addValidator('Digits')
        ->addValidator('stringLength', false, array(7, 8));
        
		$completado_por = new Zend_Form_Element_Text('completado_por');
		$completado_por->setLabel('Completado por')
					->addValidator('stringLength', false, array(0, 255));
		
		$fecha = new Zend_Form_Element_Text('fecha');
		$fecha->setLabel('Fecha')
					->addValidator(new Zend_Validate_Date('DD/MM/YYYY'));
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Guardar');

		$this->addElements(
			array(
				$id,
				$sigla,
				$nombre,
				$sitio_arqueologico,
				$observaciones,
				$paises_all,
				$paises_selected,
				$paises,
				$provincia_id,
				$hidden_provincia_id,
				$departamento_id,
				$hidden_departamento_id,
				$localidad_id,
				$hidden_localidad_id,
				$tenedor_id,
				$deposito_id,
				$deposito_temporario,
				$provisorio,
				$fur,
				$tipos_material_all,
				$tipos_material_selected,
				$tipos_material,
				$completado_por,
				$tipo_doc_completador_id,
				$completador_nro_doc,
				$fecha,
				$submit
			)
		);
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
}