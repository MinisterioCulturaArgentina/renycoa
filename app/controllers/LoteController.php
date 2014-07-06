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

class LoteController extends CustomController
{
	public function indexAction()
	{
		$this->view->pageTitle = "Lotes";
		 
		$this->getHelper('dataGridManager')->initGrid('lote',
							'lote_id', 
		array (
								"coleccion:coleccion_id",
								"coleccion:coleccion_sigla",
								"coleccion:coleccion_nombre",
								"lote:lote_sigla",
								"lote:lote_nombre",
								"lote:lote_cantidad_objetos",
								"tipo_material:tipomaterial_id",
								"tipo_material:tipomaterial_descripcion"
								)
								);
								$this->view->tiposmaterial = TipoMaterial::findAllWithLote(true);
	}



	public function viewAction()
	{
		if ($this->getRequest()->getParam('print'))
		{
			$this->_helper->layout->setLayout('print');
		}
		$this->view->pageTitle = "Lote";
		$this->view->lote = Lote::findByPK($this->getRequest()->getParam('id'));
		$this->view->adscripciones_culturales = AdscripcionCultural::findAllFromLote($this->getRequest()->getParam('id'));
	}



	public function editAction()
	{
		$lote = Lote::findByPK($this->getRequest()->getParam('id'));
	  if ($lote->id > 0)
    {
      $this->view->pageTitle = "Edición de Lote {$lote}";
    }
    else
    {
      $this->view->pageTitle = "Nuevo Lote";
    }
		$form = new FormLote();
		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$lote->setAll($form->getValues());
				$lote->save();

				LoteAdscripcionCultural::deleteAllFromLote($lote->id);
				foreach (explode(',',$form->getValue('adscripciones_culturales')) as $adscripcion_cultural_id)
				{
					$lote_adscripcion_cultural = new LoteAdscripcionCultural();
					$lote_adscripcion_cultural->lote_id = $lote->id;
					$lote_adscripcion_cultural->adscripcion_cultural_id = $adscripcion_cultural_id;
					$lote_adscripcion_cultural->save();
				}
				
				$completador = new Completador();
        $completador->tipo_doc_completador_id = $lote->tipo_doc_completador_id;
        $completador->nro_doc = $lote->completador_nro_doc;
        $completador->nombre_completo = $lote->completado_por;
        $completador->save(); 
				
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del Lote.');
				if ($form->getValue('new'))
				{
					return $this->_redirect(getControllerUrl('lote','edit',array('new_id'=>$lote->id)));
				}
				else
				{
					return $this->_redirect(getControllerUrl('lote','edit',array('id'=>$lote->id)));
				}
			}
		}
		else
		{
			$lote_new = Lote::findByPK($this->getRequest()->new_id);
			if( $lote_new->id > 0)
			{
        $form = new FormLote();
        $form->populate(array(
          'sigla'=>$lote_new->sigla,
          'nombre'=>$lote_new->nombre,
          'tipo_material_id'=>$lote_new->tipo_material_id,
          'coleccion_id'=>$lote_new->coleccion_id,
          'pais_id'=>$lote_new->pais_id,
          'provincia_id'=>$lote_new->provincia_id,
          'hidden_provincia_id' => $lote_new->provincia_id,
          'departamento_id'=>$lote_new->departamento_id,
          'hidden_departamento_id' => $lote_new->departamento_id,
          'localidad_id'=>$lote_new->localidad_id,
          'hidden_localidad_id' => $lote_new->localidad_id,
          'yacimiento_id' => $lote_new->yacimiento_id,
          'new' => 1
        ));
			}
			else
			{
				$form->populate((array)$lote);
				if ($lote->id > 0)
				{
					$form->populate(array('adscripciones_culturales'=> implode(',', extractAttributeArray(LoteAdscripcionCultural::findAllFromLote($lote->id), 'adscripcion_cultural_id'))));
				}
				else
				{
					$form->populate(array('new'=> 1));
				}
	
				$form->populate(array('hidden_provincia_id' => $lote->provincia_id ));
				$form->populate(array('hidden_departamento_id' => $lote->departamento_id ));
				$form->populate(array('hidden_localidad_id' => $lote->localidad_id ));
			}
		}
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$lote = Lote::findByPK($this->getRequest()->getParam('id'));
		Lote::delete($lote);
		Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del Lote.');
		return $this->_redirect(getControllerUrl('lote'));
	}

}