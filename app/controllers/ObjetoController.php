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

class ObjetoController extends CustomController
{
	public function indexAction()
	{
		$this->view->pageTitle = "Objetos";
		 
		$this->getHelper('dataGridManager')->initGrid('objeto',
		  'objeto_id', 
		  array (
				"objeto:objeto_sigla",
				"objeto:objeto_nombre",
				"objeto:objeto_completado_por",
				"yacimiento:yacimiento_id",
				"yacimiento:yacimiento_sigla",
				"coleccion:coleccion_id",
				"coleccion:coleccion_nombre",
				"tipo_material:tipomaterial_id",
				"tipo_material:tipomaterial_descripcion",
				"adscripcion_cultural:adscripcioncultural_id",
				"adscripcion_cultural:adscripcioncultural_nombre"
		  )
		);
		$this->view->adscripcionesculturales = AdscripcionCultural::findAllWithObjeto(true);
		$this->view->tiposmaterial = TipoMaterial::findAllWithObjeto(true);
	}

	public function viewAction()
	{
		if ($this->getRequest()->getParam('print'))
		{
			$this->_helper->layout->setLayout('print');
		}
		$this->view->pageTitle = "Objeto";
		$this->view->objeto = Objeto::findByPK($this->getRequest()->getParam('id'));
		$this->view->estados_estructurales = EstadoEstructural::findAllFromObjeto($this->getRequest()->getParam('id'));
		$this->view->deterioros_superficiales = DeterioroSuperficial::findAllFromObjeto($this->getRequest()->getParam('id'));
		$this->view->deterioros_quimicos = DeterioroQuimico::findAllFromObjeto($this->getRequest()->getParam('id'));
		$this->view->deterioros_biologicos = DeterioroBiologico::findAllFromObjeto($this->getRequest()->getParam('id'));
		$this->view->deterioros_mecanicos = DeterioroMecanico::findAllFromObjeto($this->getRequest()->getParam('id'));
	}



	public function editAction()
	{
		$objeto = Objeto::findByPK($this->getRequest()->getParam('id'));
		if ($objeto->id > 0)
		{
		  $this->view->pageTitle = "Edición de Objeto {$objeto}";
		}
		else
		{
			$this->view->pageTitle = "Nuevo Objeto";
		}
		        
		$form = new FormObjeto();
		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$objeto->setAll($form->getValues());
				$objeto->save();


				ObjetoEstadoEstructural::deleteAllFromObjeto($objeto->id);
				foreach ($form->getValue('estados_estructurales') as $estado_estructural_id)
				{
					$objeto_estado_estructural = new ObjetoEstadoEstructural();
					$objeto_estado_estructural->objeto_id = $objeto->id;
					$objeto_estado_estructural->estado_estructural_id = $estado_estructural_id;
					$objeto_estado_estructural->save();
				}

				ObjetoDeterioroSuperficial::deleteAllFromObjeto($objeto->id);
				foreach ($form->getValue('deterioros_superficiales') as $deterioro_superficial_id)
				{
					$objeto_deterioro_superficial = new ObjetoDeterioroSuperficial();
					$objeto_deterioro_superficial->objeto_id = $objeto->id;
					$objeto_deterioro_superficial->deterioro_superficial_id = $deterioro_superficial_id;
					$objeto_deterioro_superficial->save();
				}

				ObjetoDeterioroQuimico::deleteAllFromObjeto($objeto->id);
				foreach ($form->getValue('deterioros_quimicos') as $deterioro_quimico_id)
				{
					$objeto_deterioro_quimico = new ObjetoDeterioroQuimico();
					$objeto_deterioro_quimico->objeto_id = $objeto->id;
					$objeto_deterioro_quimico->deterioro_quimico_id = $deterioro_quimico_id;
					$objeto_deterioro_quimico->save();
				}

				ObjetoDeterioroBiologico::deleteAllFromObjeto($objeto->id);
				foreach ($form->getValue('deterioros_biologicos') as $deterioro_biologico_id)
				{
					$objeto_deterioro_biologico = new ObjetoDeterioroBiologico();
					$objeto_deterioro_biologico->objeto_id = $objeto->id;
					$objeto_deterioro_biologico->deterioro_biologico_id = $deterioro_biologico_id;
					$objeto_deterioro_biologico->save();
				}

				ObjetoDeterioroMecanico::deleteAllFromObjeto($objeto->id);
				foreach ($form->getValue('deterioros_mecanicos') as $deterioro_mecanico_id)
				{
					$objeto_mecanico_superficial = new ObjetoDeterioroMecanico();
					$objeto_mecanico_superficial->objeto_id = $objeto->id;
					$objeto_mecanico_superficial->deterioro_mecanico_id = $deterioro_mecanico_id;
					$objeto_mecanico_superficial->save();
				}
				
				
				$completador = new Completador();
        $completador->tipo_doc_completador_id = $objeto->tipo_doc_completador_id;
        $completador->nro_doc = $objeto->completador_nro_doc;
        $completador->nombre_completo = $objeto->completado_por;
        $completador->save(); 

				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del Objeto.');
		  	if ($form->getValue('new'))
        {
          return $this->_redirect(getControllerUrl('objeto','edit',array('new_id'=>$objeto->id)));
        }
        else
        {
          return $this->_redirect(getControllerUrl('objeto','edit',array('id'=>$objeto->id)));
        }
			}
		}
		else
		{
			 $objeto_new = Objeto::findByPK($this->getRequest()->new_id);
      if( $objeto_new->id > 0)
      {
        $form = new FormObjeto();
        $form->populate(array(
          'sigla'=>$objeto_new->sigla,
          'nombre'=>$objeto_new->nombre,
          'tipo_material_id'=>$objeto_new->tipo_material_id,
          'coleccion_id'=>$objeto_new->coleccion_id,
          'nro_inventario'=>$objeto_new->nro_inventario,
          'pais_id'=>$objeto_new->pais_id,
          'provincia_id'=>$objeto_new->provincia_id,
          'hidden_provincia_id' => $objeto_new->provincia_id,
          'departamento_id'=>$objeto_new->departamento_id,
          'hidden_departamento_id' => $objeto_new->departamento_id,
          'localidad_id'=>$objeto_new->localidad_id,
          'hidden_localidad_id' => $objeto_new->localidad_id,
          'yacimiento_id' => $objeto_new->yacimiento_id,
          'new' => 1
        ));
      }
      else
      {
				$form->populate((array)$objeto);
				if ($objeto->id > 0)
				{
					$form->populate(array('estados_estructurales'=> extractAttributeArray(ObjetoEstadoEstructural::findAllFromObjeto($objeto->id), 'estado_estructural_id')));
					$form->populate(array('deterioros_superficiales'=> extractAttributeArray(ObjetoDeterioroSuperficial::findAllFromObjeto($objeto->id), 'deterioro_superficial_id')));
					$form->populate(array('deterioros_quimicos'=> extractAttributeArray(ObjetoDeterioroQuimico::findAllFromObjeto($objeto->id), 'deterioro_quimico_id')));
					$form->populate(array('deterioros_biologicos'=> extractAttributeArray(ObjetoDeterioroBiologico::findAllFromObjeto($objeto->id), 'deterioro_biologico_id')));
					$form->populate(array('deterioros_mecanicos'=> extractAttributeArray(ObjetoDeterioroMecanico::findAllFromObjeto($objeto->id), 'deterioro_mecanico_id')));
				}
        else
        {
          $form->populate(array('new'=> 1));
        }
				$form->populate(array('hidden_provincia_id' => $objeto->provincia_id ));
				$form->populate(array('hidden_departamento_id' => $objeto->departamento_id ));
				$form->populate(array('hidden_localidad_id' => $objeto->localidad_id ));
      }
		}
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$objeto = Objeto::findByPK($this->getRequest()->getParam('id'));
		Objeto::delete($objeto);
		Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del Objeto.');
		return $this->_redirect(getControllerUrl('objeto'));
	}

}