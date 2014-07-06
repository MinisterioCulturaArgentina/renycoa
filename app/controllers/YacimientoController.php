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

class YacimientoController extends CustomController
{
	var $grid;

	public function reportAction()
	{
		$this->view->pageTitle = "Reporte de Yacimientos";

		$session = new Zend_Session_Namespace('grid_Yacimientos');
		$filters = array();
		$session->filters = $filters;


		$this->setGridScripts();
		 
		$this->setGrid();
		$this->view->headScript()->captureStart();
		echo $this->grid->getGridScript();
		$this->view->headScript()->captureEnd();
	}

	public function gridAction()
	{
		$this->setGrid();
		$this->grid->fetchArgsFromRequest(true);
		$this->grid->fecthData();
		$this->_helper->json(array('total'=>$this->grid->getTotalRows(), 'data'=>$this->grid->getData()));
	}

	public function gridcsvAction()
	{
		$session = new Zend_Session_Namespace('grids');
		$this->_helper->layout->setLayout('ajax');
		$this->getHelper("ViewRenderer")->setNoRender();
		$this->setGrid();
		$this->grid = $session->yacimientos;
		$this->grid->pageSize = 0;
		$this->grid->fecthData();
		$fields = $this->grid->fields;
		$data = $this->grid->getData();
		header("Pragma: public");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: pre-check=0, post-check=0, max-age=0");
		header("Pragma: no-cache");
		header("Expires: 0");
		header("Content-Transfer-Encoding: none");
		header("Content-Type: text/comma-seperated-values");
		header("Content-Disposition: attachment; filename=reporte_c-".date('Ymd').".csv");

//		$first = true;
//		foreach ((array)$fields as $f)
//		{
//			if (!$first)  echo "\t";
//			echo $f->label;
//			$first = false;
//		}
//		echo "\n";
//
//		foreach ((array)$data as $r)
//		{
//			$first = true;
//			foreach ((array)$fields as $f)
//			{
//				if (!$first)  echo "\t";
//				switch  ($f->valueType)
//				{
//					case (dotDev_Grid_FilterField::VALUE_NUMERIC):
//						echo strtr(round($r->{$f->fieldId},2),'.',',');
//						break;
//					default:
//						echo $r->{$f->fieldId};
//						break;
//				}
//				$first = false;
//			}
//			echo "\n";
//		}

	  $first = true;
    foreach ((array)$fields as $f) 
    {
      if (!$first)  echo ";";   
      echo '"'.iconv("UTF-8", "ISO-8859-1//TRANSLIT", $f->label).'"';
      $first = false;
    }
    echo "\r\n";
    
    foreach ((array)$data as $r)
    {
      $first = true;
      foreach ((array)$fields as $f)
      {
        if (!$first)  echo ";";
        switch  ($f->valueType)
        {
          case (dotDev_Grid_FilterField::VALUE_NUMERIC):
            echo strtr(round($r->{$f->fieldId},2),'.',',');
            break;
          default:
            echo '"'.iconv("UTF-8", "ISO-8859-1//TRANSLIT", $r->{$f->fieldId}).'"';
            break;
        }
        $first = false;
      }
      echo "\r\n";
    }
	}

	public function gridprintAction()
	{
		$session = new Zend_Session_Namespace('grids');

		$this->_helper->layout->setLayout('print');
		$this->_helper->viewRenderer->setRender('grid-export',null,true);
		$this->view->pageTitle = "Reporte de Yacimientos - ".date('Y-m-d');
		$this->setGrid();
		$this->grid = $session->yacimientos;
		$this->grid->pageSize = 0;
		$this->grid->fecthData();
		$this->view->fields = $this->grid->fields;
		$this->view->data = $this->grid->getData();
	}

	public function indexAction()
	{
		$this->view->pageTitle = "Yacimientos";
		$this->getHelper('dataGridManager')->initGrid('yacimiento',
							'yacimiento_id', 
		array (
								"yacimiento:yacimiento_sigla",
								"yacimiento:yacimiento_denominacion_sitio",
								"yacimiento:yacimiento_completado_por",
								"yacimiento:yacimiento_adscripciones_culturales",
								"provincia:provincia_id",
								"provincia:provincia_descripcion",
								"adscripcion_cultural:adscripcioncultural_id"
								)
								);
								$this->view->provincias = Provincia::findAllWithYacimiento(true);
								$this->view->adscripcionesculturales = AdscripcionCultural::findAllWithYacimiento(true);
	}

	public function viewAction()
	{
	  if ($this->getRequest()->getParam('print'))
    {
      $this->_helper->layout->setLayout('print');
    }
		$this->view->pageTitle = "Yacimiento";
		$this->view->yacimiento = Yacimiento::findByPK($this->getRequest()->getParam('id'));
		$this->view->tipos_material = TipoMaterial::findAllFromYacimiento($this->getRequest()->getParam('id'));
		$this->view->representaciones_rupestres = RepresentacionRupestre::findAllFromYacimiento($this->getRequest()->getParam('id'));
		$this->view->funciones_inferidas = FuncionInferida::findAllFromYacimiento($this->getRequest()->getParam('id'));
		$this->view->legislaciones_proteccion = LegislacionProteccion::findAllFromYacimiento($this->getRequest()->getParam('id'));
		$this->view->tareas_realizadas = TareaRealizada::findAllFromYacimiento($this->getRequest()->getParam('id'));
		$this->view->adscripciones_culturales = AdscripcionCultural::findAllFromYacimiento($this->getRequest()->getParam('id'));
	}



	public function editAction()
	{
		$yacimiento = Yacimiento::findByPK($this->getRequest()->getParam('id'));
	  if ($yacimiento->id > 0)
    {
      $this->view->pageTitle = "Edición de Yacimiento {$yacimiento}";
    }
    else
    {
      $this->view->pageTitle = "Nuevo Yacimiento";
    }
		$form = new FormYacimiento();
		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$yacimiento->setAll($form->getValues());
				$yacimiento->save();

				YacimientoTipoMaterial::deleteAllFromYacimiento($yacimiento->id);
				foreach (explode(',',$form->getValue('tipos_material')) as $tipo_material_id)
				{
					$yacimiento_tipo_material = new YacimientoTipoMaterial();
					$yacimiento_tipo_material->yacimiento_id = $yacimiento->id;
					$yacimiento_tipo_material->tipo_material_id = $tipo_material_id;
					$yacimiento_tipo_material->save();
				}

				YacimientoRepresentacionRupestre::deleteAllFromYacimiento($yacimiento->id);
				foreach ($form->getValue('representaciones_rupestres') as $representacion_rupestre_id)
				{
					$yacimiento_representacion_rupestre = new YacimientoRepresentacionRupestre();
					$yacimiento_representacion_rupestre->yacimiento_id = $yacimiento->id;
					$yacimiento_representacion_rupestre->representacion_rupestre_id = $representacion_rupestre_id;
					$yacimiento_representacion_rupestre->save();
				}

				YacimientoTareaRealizada::deleteAllFromYacimiento($yacimiento->id);
				foreach ($form->getValue('tareas_realizadas') as $tarea_realizada_id)
				{
					$yacimiento_tarea_realizada = new YacimientoTareaRealizada();
					$yacimiento_tarea_realizada->yacimiento_id = $yacimiento->id;
					$yacimiento_tarea_realizada->tarea_realizada_id = $tarea_realizada_id;
					$yacimiento_tarea_realizada->save();
				}

				YacimientoFuncionInferida::deleteAllFromYacimiento($yacimiento->id);
				foreach ($form->getValue('funciones_inferidas') as $funcion_inferida_id)
				{
					$yacimiento_funcion_inferida = new YacimientoFuncionInferida();
					$yacimiento_funcion_inferida->yacimiento_id = $yacimiento->id;
					$yacimiento_funcion_inferida->funcion_inferida_id = $funcion_inferida_id;
					$yacimiento_funcion_inferida->save();
				}

				YacimientoLegislacionProteccion::deleteAllFromYacimiento($yacimiento->id);
				foreach ($form->getValue('legislaciones_proteccion') as $legislacion_proteccion_id)
				{
					$yacimiento_legislacion_proteccion = new YacimientoLegislacionProteccion();
					$yacimiento_legislacion_proteccion->yacimiento_id = $yacimiento->id;
					$yacimiento_legislacion_proteccion->legislacion_proteccion_id = $legislacion_proteccion_id;
					$yacimiento_legislacion_proteccion->save();
				}

				YacimientoAdscripcionCultural::deleteAllFromYacimiento($yacimiento->id);
				foreach (explode(',',$form->getValue('adscripciones_culturales')) as $adscripcion_cultural_id)
				{
					$yacimiento_adscripcion_cultural = new YacimientoAdscripcionCultural();
					$yacimiento_adscripcion_cultural->yacimiento_id = $yacimiento->id;
					$yacimiento_adscripcion_cultural->adscripcion_cultural_id = $adscripcion_cultural_id;
					$yacimiento_adscripcion_cultural->save();
				}
				
        $completador = new Completador();
        $completador->tipo_doc_completador_id = $yacimiento->tipo_doc_completador_id;
        $completador->nro_doc = $yacimiento->completador_nro_doc;
        $completador->nombre_completo = $yacimiento->completado_por;
        $completador->save(); 

				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos del Yacimiento.');
				return $this->_redirect(getControllerUrl('yacimiento','edit',array('id'=>$yacimiento->id)));
			}
		}
		else
		{
			$form->populate((array)$yacimiento);
			if ($yacimiento->id > 0)
			{
				$form->populate(array('tipos_material'=> implode(',', extractAttributeArray(YacimientoTipoMaterial::findAllFromYacimiento($yacimiento->id), 'tipo_material_id'))));
				$form->populate(array('representaciones_rupestres'=> extractAttributeArray(YacimientoRepresentacionRupestre::findAllFromYacimiento($yacimiento->id), 'representacion_rupestre_id')));
				$form->populate(array('tareas_realizadas'=> extractAttributeArray(YacimientoTareaRealizada::findAllFromYacimiento($yacimiento->id), 'tarea_realizada_id')));
				$form->populate(array('funciones_inferidas'=> extractAttributeArray(YacimientoFuncionInferida::findAllFromYacimiento($yacimiento->id), 'funcion_inferida_id')));
				$form->populate(array('legislaciones_proteccion'=> extractAttributeArray(YacimientoLegislacionProteccion::findAllFromYacimiento($yacimiento->id), 'legislacion_proteccion_id')));
				$form->populate(array('adscripciones_culturales'=> implode(',', extractAttributeArray(YacimientoAdscripcionCultural::findAllFromYacimiento($yacimiento->id), 'adscripcion_cultural_id'))));
			}
			$form->populate(array('hidden_provincia_id' => $yacimiento->provincia_id ));
			$form->populate(array('hidden_departamento_id' => $yacimiento->departamento_id ));
			$form->populate(array('hidden_localidad_id' => $yacimiento->localidad_id ));
		}
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$yacimiento = Yacimiento::findByPK($this->getRequest()->getParam('id'));
		Yacimiento::delete($yacimiento);
		 
		Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos del Yacimiento.');
		return $this->_redirect(getControllerUrl('yacimiento'));
	}
	
	private function setGrid()
  {
    $session = new Zend_Session_Namespace('grid_Yacimientos');

    $this->grid = new dotDev_Grid_AdapterExt(
      new YacimientoQuery(),
      array(
          new dotDev_Grid_FilterField(
           'y_sigla', 
            array(
              'label'=>'Sigla', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING
          )),
          new dotDev_Grid_FilterField(
           'y_denominacion', 
            array(
              'label'=>'Denominación', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
          )),
          new dotDev_Grid_FilterField(
           'y_tipo_sitio', 
            array(
              'label'=>'Tipo', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('y_tipo_sitio_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(TipoSitio::findAll(true)),
              'hidden'=> true
          )),
          new dotDev_Grid_FilterField(
          'y_localidad', 
            array(
              'label'=>'Localidad', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           'y_provincia', 
            array(
              'label'=>'Provincia', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('y_provincia_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Provincia::findAll(true)),
              'hidden'=> false
          )),
          new dotDev_Grid_FilterField(
           'y_pais', 
            array(
              'label'=>'País', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('y_pais_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Pais::findAll(true)),
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           'y_superficie', 
            array(
              'label'=>'Superficie', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           'y_observaciones', 
            array(
              'label'=>'Observaciones', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           'ac_nombre', 
            array(
              'label'=>'A. Culturales', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('acul_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(AdscripcionCultural::findAll(true)),
              'hidden'=> false
           )),
          new dotDev_Grid_FilterField(
           'rr_descripcion', 
            array(
              'label'=>'R. Rupestres', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('rrup_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(RepresentacionRupestre::findAll(true)),
              'hidden'=> false
           )),
          new dotDev_Grid_FilterField(
           'fi_descripcion', 
            array(
              'label'=>'F. Inferidas', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('finf_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(FuncionInferida::findAll(true)),
              'hidden'=> false
           )),
           new dotDev_Grid_FilterField(
           'tr_descripcion', 
            array(
              'label'=>'T. Realizadas', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('trea_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(TareaRealizada::findAll(true)),
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           'y_created_at', 
            array(
              'label'=>'Fecha Carga', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           ))
      
           
      ),
      array(
        'id'=> 'yacimientos',  
        'dataFeedUrl' => getControllerUrl('yacimiento', 'grid'),
        'exportXlsUrl' => getControllerUrl('yacimiento', 'gridcsv'),
        'printUrl' => getControllerUrl('yacimiento', 'gridprint'),
        'dataId' => 'y_id',
        'groupingActive' => false
//        'gridHeight' => 300,
//        'gridWidth' => 600,
//        'actions' => array (
//          new dotDev_Grid_Action('action_edit',getControllerUrl('amenity','edit'), array(
//            'tooltip'=>'Edit'
//          ))
//        )
     ));
  }
	

}