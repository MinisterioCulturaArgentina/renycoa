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

class FormObjeto extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		
		$this->setName('Edición de Objeto');
		$this->setAttrib('id', 'form_objeto');	
		
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/objeto.phtml'))
		));

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$sigla = new Zend_Form_Element_Text('sigla');
		$sigla->setLabel('Sigla*')
			  ->addFilter('StringTrim')
			  ->setRequired(true)
			  ->addValidator('stringLength', false, array(0, 50))
			  ->addValidator(
			  	'regex', true, array(
			  		'pattern' => '/^([-a-z0-9 _\\/])*$/i',
			  		'messages' => array(
			  			Zend_Validate_Regex::NOT_MATCH => 'solo se admiten letras (sin ñ ni acentos), números y los separadores: / - _'
			  		)
			  	)
			  )
			  ->addValidator(new dotDev_Validate_UniqueKey('Objeto','sigla', 'id'));
				
		$nombre = new Zend_Form_Element_Text('nombre');
		$nombre->setLabel('Nombre descriptivo*')
			   ->setRequired(true)
			   ->addValidator('stringLength', false, array(0, 255));
		
		$nro_inventario = new Zend_Form_Element_Text('nro_inventario');
		$nro_inventario->setLabel('Nro. de Inventario original')
					   ->addValidator('stringLength', false, array(0, 50));
					
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
					
		$estados_fragmentacion = EstadoFragmentacion::findAll(true);
		$estado_fragmentacion_id = new Zend_Form_Element_Radio("estado_fragmentacion_id");
		$estado_fragmentacion_id->setLabel("Estado de Fragmentación")
					  ->setmultiOptions($estados_fragmentacion)
					  ->addValidator(new Zend_Validate_InArray(array_keys($estados_fragmentacion)));
		
		$forma = new Zend_Form_Element_Text('forma');
		$forma->setLabel('Forma')
			  ->addValidator('stringLength', false, array(0, 255));
		
		$alto = new Zend_Form_Element_Text('alto');
		$alto->setLabel('Alto')
			 ->addFilter(new dotDev_Filter_CommaToDot())
			 ->addFilter('StringTrim')
       ->addValidator('stringLength', false, array(0, 50))
       ->addValidator(
         'regex', true, array(
           'pattern' => '/^([0-9_,.+\\-\\/])*$/i',
           'messages' => array(
             Zend_Validate_Regex::NOT_MATCH => 'solo se admiten números y los caracteres: / + - _'
           )
         )
       );
				
		$ancho = new Zend_Form_Element_Text('ancho');
		$ancho->setLabel('Ancho/Diámetro')
			  ->addFilter(new dotDev_Filter_CommaToDot())
			   ->addFilter('StringTrim')
       ->addValidator('stringLength', false, array(0, 50))
       ->addValidator(
         'regex', true, array(
           'pattern' => '/^([0-9_,.+\\-\\/])*$/i',
           'messages' => array(
             Zend_Validate_Regex::NOT_MATCH => 'solo se admiten números y los caracteres: / + - _'
           )
         )
       );
		
		$espesor = new Zend_Form_Element_Text('espesor');
		$espesor->setLabel('Espesor')
				->addFilter(new dotDev_Filter_CommaToDot())
				 ->addFilter('StringTrim')
       ->addValidator('stringLength', false, array(0, 50))
       ->addValidator(
         'regex', true, array(
           'pattern' => '/^([0-9_,.+\\-\\/])*$/i',
           'messages' => array(
             Zend_Validate_Regex::NOT_MATCH => 'solo se admiten números y los caracteres: / + - _'
           )
         )
       );
		
		$decoracion = new Zend_Form_Element_Textarea('decoracion');
		$decoracion->setLabel('Decoración');
		
		$caracs_distintivas = new Zend_Form_Element_Textarea('caracs_distintivas');
		$caracs_distintivas->setLabel('Caracs. Distintivas');
		
		$adscripciones_culturales = array(''=>'--Seleccione--');
		foreach ( AdscripcionCultural::findAll(true) as $k=>$v)
		{
			$adscripciones_culturales[$k] = $v;
		}
		$adscripcion_cultural_id = new Zend_Form_Element_Select('adscripcion_cultural_id');
		$adscripcion_cultural_id->setLabel('Adscripción cultural')
								->setmultiOptions($adscripciones_culturales);
		
		$estados_completitud = EstadoCompletitud::findAll(true);
		$estado_completitud_id = new Zend_Form_Element_Radio("estado_completitud_id");
		$estado_completitud_id->setmultiOptions($estados_completitud)
								->addValidator(new Zend_Validate_InArray(array_keys($estados_completitud)));
		
		$estados_fragmentacion = EstadoFragmentacion::findAll(true);
		$estado_fragmentacion_id = new Zend_Form_Element_Radio("estado_fragmentacion_id");
		$estado_fragmentacion_id->setmultiOptions($estados_fragmentacion)
								->addValidator(new Zend_Validate_InArray(array_keys($estados_fragmentacion)));
								
							  
		$estados_estructurales_datos = EstadoEstructural::findAll(true);
		$estados_estructurales = new Zend_Form_Element_MultiCheckbox('estados_estructurales');
		$estados_estructurales->setmultiOptions($estados_estructurales_datos)
							  ->addValidator(new Zend_Validate_InArray(array_keys($estados_estructurales_datos)));			  
		
		$estados_superficie = EstadoSuperficie::findAll(true);
		$estado_superficie_id = new Zend_Form_Element_Radio("estado_superficie_id");
		$estado_superficie_id->setmultiOptions($estados_superficie)
							 ->addValidator(new Zend_Validate_InArray(array_keys($estados_superficie)));


		$estado_superficie_detalle = new Zend_Form_Element_Textarea('estado_superficie_detalle');
		$estado_superficie_detalle->setLabel('Descripción');
		
		$estado_conservacion = new Zend_Form_Element_Textarea('estado_conservacion');
    $estado_conservacion->setLabel('Estado de conservación');
		
		$decoracion->setLabel('Decoración');
		$deterioros_superficiales_datos = DeterioroSuperficial::findAll(true);
		$deterioros_superficiales = new Zend_Form_Element_MultiCheckbox('deterioros_superficiales');
		$deterioros_superficiales->setmultiOptions($deterioros_superficiales_datos)
							     ->addValidator(new Zend_Validate_InArray(array_keys($deterioros_superficiales_datos)));
		
		$deterioros_quimicos_datos = DeterioroQuimico::findAll(true);
		$deterioros_quimicos = new Zend_Form_Element_MultiCheckbox('deterioros_quimicos');
		$deterioros_quimicos->setLabel('Químico')
								 ->setmultiOptions($deterioros_quimicos_datos)
							     ->addValidator(new Zend_Validate_InArray(array_keys($deterioros_quimicos_datos)));
							     
		$deterioros_biologicos_datos = DeterioroBiologico::findAll(true);
		$deterioros_biologicos = new Zend_Form_Element_MultiCheckbox('deterioros_biologicos');
		$deterioros_biologicos->setLabel('Biológico')
								 ->setmultiOptions($deterioros_biologicos_datos)
							     ->addValidator(new Zend_Validate_InArray(array_keys($deterioros_biologicos_datos)));
							     
		$deterioros_mecanicos_datos = DeterioroMecanico::findAll(true);
		$deterioros_mecanicos = new Zend_Form_Element_MultiCheckbox('deterioros_mecanicos');
		$deterioros_mecanicos->setLabel('Mecánico')
								 ->setmultiOptions($deterioros_mecanicos_datos)
							     ->addValidator(new Zend_Validate_InArray(array_keys($deterioros_mecanicos_datos)));

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
				$nro_inventario,
				$coleccion_id,
				$tipo_material_id,
				$pais_id,
				$provincia_id,
				$hidden_provincia_id,
				$departamento_id,
				$hidden_departamento_id,
				$localidad_id,
				$hidden_localidad_id,
				$pais_detalle,
				$yacimiento_id,
				$forma,
				$alto,
				$ancho,
				$espesor,
				$decoracion,
				$caracs_distintivas,
				$adscripcion_cultural_id,
				$estado_completitud_id,
				$estado_fragmentacion_id,
				$estados_estructurales,
				$estado_superficie_id,
				$estado_superficie_detalle,
				$estado_conservacion,
				$deterioros_superficiales,
				$deterioros_biologicos,
				$deterioros_quimicos,
				$deterioros_mecanicos,
				$completado_por,
				$tipo_doc_completador_id,
        $completador_nro_doc,
				$fecha,
				$submit,
				$new
			)
		);
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
}