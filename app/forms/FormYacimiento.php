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

class FormYacimiento extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
	
		$this->setName('Edición de Yacimiento');
		$this->setAttrib('id', 'form_yacimiento');	
		
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/yacimiento.phtml'))
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
			  ->addValidator(new dotDev_Validate_UniqueKey('Yacimiento','sigla', 'id'));

		$denominacion_sitio = new Zend_Form_Element_Text('denominacion_sitio');
		$denominacion_sitio->setLabel('Denominación del Sitio*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
				
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
        
		$lugar_paraje = new Zend_Form_Element_Text('lugar_paraje');
		$lugar_paraje->setLabel('Lugar / Paraje')
				->addValidator('stringLength', false, array(0, 255));
		
		$altitud = new Zend_Form_Element_Text('altitud');
		$altitud->setLabel('Altitud')
				->addValidator('stringLength', false, array(0, 255));
		
		$longitud = new Zend_Form_Element_Text('longitud');
		$longitud->setLabel('Longitud')
				->addFilter('StringTrim')
				 ->addValidator('stringLength', false, array(0, 255))
				 ->addValidator(
					'regex', true, array( 
						'pattern' => '((\d{1,2})° ?(\d{1,2})'."'".' ?(\d{1,2})(\.(\d{1,3}))?")',
						'messages' => array(
                			Zend_Validate_Regex::NOT_MATCH => 'no cumple con el formato gg° mm\' ss.sss"'
            			)
            		)
            	);
				
		$latitud = new Zend_Form_Element_Text('latitud');
		$latitud->setLabel('Latitud')
				->addFilter('StringTrim')
				->addValidator('stringLength', false, array(0, 255))
				->addValidator(
					'regex', true, array( 
						'pattern' => '((\d{1,2})° ?(\d{1,2})'."'".' ?(\d{1,2})(\.(\d{1,3}))?")',
						'messages' => array(
                			Zend_Validate_Regex::NOT_MATCH => 'no cumple con el formato gg° mm\' ss.sss"'
            			)
            		)
            	);
						
		$datum = new Zend_Form_Element_Text('datum');
		$datum->setLabel('Datum')
			->addValidator('stringLength', false, array(0, 255));
		
		$carta_igm = new Zend_Form_Element_Text('carta_igm');
		$carta_igm->setLabel('Carta IGM N°')
				  ->addValidator('stringLength', false, array(0, 255));
				
		$plano_catastral = new Zend_Form_Element_Text('plano_catastral');
		$plano_catastral->setLabel('Plano Catastral')
						->addValidator('stringLength', false, array(0, 255));
		
		$croquis = new Zend_Form_Element_Checkbox('croquis');
		$croquis->setLabel('Croquis de Ubicación');
		
		$tipos_sitio = TipoSitio::findAll(true);
		$tipo_sitio_id = new Zend_Form_Element_Radio("tipo_sitio_id");
		$tipo_sitio_id->setLabel("Tipo de sitio")
					  ->setmultiOptions($tipos_sitio)
					  ->addValidator(new Zend_Validate_InArray(array_keys($tipos_sitio)));
		
		$plano_sitio = new Zend_Form_Element_Checkbox('plano_sitio');
		$plano_sitio->setLabel('Plano del Sitio');
		
		$superficie = new Zend_Form_Element_Text('superficie');
		$superficie->setLabel('Superficie Estimada en M2')
				   ->addValidator('float')
				   ->addValidator('stringLength', false, array(0, 255));
				   
		$provisorio = new Zend_Form_Element_Checkbox('provisorio');
		$provisorio->setLabel('Provisorio');

		$fur = new Zend_Form_Element_Checkbox('fur');
		$fur->setLabel('FUR');
		
		$representaciones_rupestres_datos = RepresentacionRupestre::findAll(true);
		$representaciones_rupestres = new Zend_Form_Element_MultiCheckbox('representaciones_rupestres');
		$representaciones_rupestres->setLabel('Representaciones Rupestres')
						->setmultiOptions($representaciones_rupestres_datos)
						->addValidator(new Zend_Validate_InArray(array_keys($representaciones_rupestres_datos)));
	
		$tareas_realizadas_datos = TareaRealizada::findAll(true);
		$tareas_realizadas = new Zend_Form_Element_MultiCheckbox('tareas_realizadas');
		$tareas_realizadas->setLabel('Tarea Realizada')
						->setmultiOptions($tareas_realizadas_datos)
						->addValidator(new Zend_Validate_InArray(array_keys($tareas_realizadas_datos)));
			
		$funciones_inferidas_datos = FuncionInferida::findAll(true);
		$funciones_inferidas = new Zend_Form_Element_MultiCheckbox('funciones_inferidas');
		$funciones_inferidas->setLabel('Función Inferida')
						->setmultiOptions($funciones_inferidas_datos)
						->addValidator(new Zend_Validate_InArray(array_keys($funciones_inferidas_datos)));
					  
		$riesgos_impacto = RiesgoImpacto::findAll(true);
		$riesgo_impacto_cultural_id = new Zend_Form_Element_Radio('riesgo_impacto_cultural_id');
		$riesgo_impacto_cultural_id->setLabel("Riesgo de Impacto Cultural")
					  ->setmultiOptions($riesgos_impacto)
					  ->addValidator(new Zend_Validate_InArray(array_keys($riesgos_impacto)));
					  
		$riesgos_impacto = RiesgoImpacto::findAll(true);
		$riesgo_impacto_ambiental_id = new Zend_Form_Element_Radio('riesgo_impacto_ambiental_id');
		$riesgo_impacto_ambiental_id->setLabel("Riesgo de Impacto Ambiental")
					  ->setmultiOptions($riesgos_impacto)
					  ->addValidator(new Zend_Validate_InArray(array_keys($riesgos_impacto)));
	
	
		$legislaciones_proteccion_datos = LegislacionProteccion::findAll(true);
		$legislaciones_proteccion = new Zend_Form_Element_MultiCheckbox('legislaciones_proteccion');
		$legislaciones_proteccion->setLabel('Legislación de Protección')
						->setmultiOptions($legislaciones_proteccion_datos)
						->addValidator(new Zend_Validate_InArray(array_keys($legislaciones_proteccion_datos)));
	
		$danos_antropicos = new Zend_Form_Element_Checkbox('danos_antropicos');
		$danos_antropicos->setLabel('Daños Antrópicos');
			
		$danos_antropicos_desc = new Zend_Form_Element_Textarea('danos_antropicos_desc');
		$danos_antropicos_desc->setLabel('Daños Antrópicos');
		
		$danos_naturales = new Zend_Form_Element_Checkbox('danos_naturales');
		$danos_naturales->setLabel('Daños Naturales');
		
		$danos_naturales_desc = new Zend_Form_Element_Textarea('danos_naturales_desc');
		$danos_naturales_desc->setLabel('Daños Naturales');
		
		$intervenciones = new Zend_Form_Element_Checkbox('intervenciones');
		$intervenciones->setLabel('Intervenciones');
		
		$intervenciones_desc = new Zend_Form_Element_Textarea('intervenciones_desc');
		$intervenciones_desc->setLabel('Intervenciones');
		
		$observaciones = new Zend_Form_Element_Textarea('observaciones');
		$observaciones->setLabel('Observaciones Generales');

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
		$tipos_material->setLabel('Tipo de vestigio');
		//TODO: agregar validador!!!

		
		$dominios = Dominio::findAll(true);
		$dominio_id = new Zend_Form_Element_Radio('dominio_id');
		$dominio_id->setLabel('Dominio')
					  ->setmultiOptions($dominios)
					  ->addValidator(new Zend_Validate_InArray(array_keys($dominios)));
		
		
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

		$this->addElements(
			array(
				$id,
				$sigla,
				$denominacion_sitio,
				$pais_id,
				$provincia_id,
				$hidden_provincia_id,
				$departamento_id,
				$hidden_departamento_id,
				$localidad_id,
				$hidden_localidad_id,
				$pais_detalle,
				$lugar_paraje,
				$altitud,
				$longitud,
				$latitud, 
				$datum,
				$carta_igm, 
				$plano_catastral,
				$croquis,
				$tipo_sitio_id,
				$plano_sitio,
				$superficie, 
				$provisorio,
				$fur,
				$representaciones_rupestres,
				$tareas_realizadas,
				$funciones_inferidas,
				$riesgo_impacto_cultural_id, 
				$riesgo_impacto_ambiental_id,
				$legislaciones_proteccion,
				$danos_antropicos,
				$danos_antropicos_desc,
				$danos_naturales, 
				$danos_naturales_desc,
				$intervenciones,
				$intervenciones_desc, 
				$observaciones,
				$tipos_material_all,
				$tipos_material_selected,
				$tipos_material,
				$dominio_id, 
				$adscripciones_all,
				$adscripciones_selected,
				$adscripciones_culturales,
				$tipo_doc_completador_id,
        $completador_nro_doc,
				$completado_por,
				$fecha,
				$submit
			)
		);		
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
}