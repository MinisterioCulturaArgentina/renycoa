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

class FormLote extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Lote');
		$this->setAttrib('id', 'form_lote');	
		
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/lote.phtml'))
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
			  ->addValidator(new dotDev_Validate_UniqueKey('Lote','sigla','id'));
	
		$nombre = new Zend_Form_Element_Text('nombre');
		$nombre->setLabel('Nombre*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
				
		$colecciones = array(''=>'--Seleccione--');
		foreach ( Coleccion::findAll(true) as $k=>$v)
		{
			$colecciones[$k] = $v;
		}
		$coleccion_id = new Zend_Form_Element_Select('coleccion_id');
		$coleccion_id->setLabel('Colección (sigla)*')
					->setRequired(true)
					->setmultiOptions($colecciones);
		
		$tipos_material = array(''=>'--Seleccione--');
		foreach ( TipoMaterial::findAll(true) as $k=>$v)
		{
			$tipos_material[$k] = $v;
		}
		$tipo_material_id = new Zend_Form_Element_Select('tipo_material_id');
		$tipo_material_id->setLabel('Tipo de Material*')
					->setRequired(true)
					->setmultiOptions($tipos_material);
					
		$descripcion = new Zend_Form_Element_Textarea('descripcion');
		$descripcion->setLabel('Descripción');
				
		$cantidad_objetos = new Zend_Form_Element_Text('cantidad_objetos');
		$cantidad_objetos->setLabel('Cantidad de Objetos*')
						  ->setRequired(true)
						  ->addValidator('Digits');
		
		$paises = array(''=>'--Seleccione--');
		$paises_datos = Pais::findAll(true); 
		foreach ( $paises_datos as $k=>$v)
		{
			$paises[$k] = $v;
		}
		$pais_id = new Zend_Form_Element_Select('pais_id');
		$pais_id->setLabel('País*')
				->setmultiOptions($paises)
				->setRequired(true)
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
		
		$pais_detalle = new Zend_Form_Element_Text('pais_detalle');
    $pais_detalle->setLabel('Detalle')
        ->addFilter('StringTrim')
        ->addValidator('stringLength', false, array(0, 256));
		
		$yacimientos = array(''=>'--Seleccione--');
		foreach ( Yacimiento::findAll(true) as $k=>$v)
		{
			$yacimientos[$k] = $v;
		}
		$yacimiento_id = new Zend_Form_Element_Select('yacimiento_id');
		$yacimiento_id->setLabel('Sitio arqueológico')
					  ->setmultiOptions($yacimientos);
		
		$adscripciones_datos = array();  
		foreach ( AdscripcionCultural::findAll(true) as $k=>$v)
		{
			$adscripciones_datos[$k] = $v;
		}
		$adscripciones_all = new Zend_Form_Element_Multiselect('adscripciones_all');
		$adscripciones_all->setLabel('Posibles')
				->setmultiOptions($adscripciones_datos);
		
		$adscripciones_selected = new Zend_Form_Element_Multiselect('adscripciones_selected');
		$adscripciones_selected->setLabel('Asignadas');
					  
		$adscripciones_culturales = new Zend_Form_Element_Hidden('adscripciones_culturales');
		$adscripciones_culturales->setLabel('Adscripciones Culturales');
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
		
		$new = new Zend_Form_Element_Checkbox('new');
		$new->setLabel('y cargar otro');
		

		$this->addElements(
			array(
				$id,
				$sigla,
				$nombre,
				$coleccion_id,
				$tipo_material_id,
				$yacimiento_id,
				$descripcion,
				$cantidad_objetos,
				$pais_id,
				$provincia_id,
				$hidden_provincia_id,
				$departamento_id,
				$hidden_departamento_id,
				$localidad_id,
				$hidden_localidad_id,
				$pais_detalle,
				$adscripciones_all,
				$adscripciones_selected,
				$adscripciones_culturales,
				$tipo_doc_completador_id,
        $completador_nro_doc,
				$completado_por,
				$fecha,
				$submit,
				$new
			)
		);
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
		
	}
}