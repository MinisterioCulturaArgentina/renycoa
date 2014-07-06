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

class ColeccionController extends CustomController 
{   
	
  var $grid;

  public function reportAction()
  {
    $this->view->pageTitle = "Reporte de Colecciones";
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
    $this->grid = $session->colecciones;
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
    
//	  $first = true;
//		foreach ((array)$fields as $f) 
//		{
//		  if (!$first)  echo "\t";   
//		  echo $f->label;
//		  $first = false;
//		}
//		echo "\n";
//		
//		foreach ((array)$data as $r)
//		{
//		  $first = true;
//		  foreach ((array)$fields as $f)
//		  {
//		    if (!$first)  echo "\t";
//		    switch  ($f->valueType)
//		    {
//		      case (dotDev_Grid_FilterField::VALUE_NUMERIC):
//		        echo strtr(round($r->{$f->fieldId},2),'.',',');
//		        break;
//		      default:
//		        echo $r->{$f->fieldId};
//		        break;
//		    }
//		    $first = false;
//		  }
//		  echo "\n";
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
    $this->view->pageTitle = "Reporte de Colecciones - ".date('Y-m-d');
    $this->setGrid();
    $this->grid = $session->colecciones;
    $this->grid->pageSize = 0;
    $this->grid->fecthData();
    $this->view->fields = $this->grid->fields;
    $this->view->data = $this->grid->getData();
  }

	
  
	public function indexAction() 
  { 		
   	$this->view->pageTitle = "Colecciones";
    	
		$this->getHelper('dataGridManager')->initGrid('coleccion',
			'coleccion_id', 
  		array ( 
				"coleccion:coleccion_sigla",
				"coleccion:coleccion_nombre",
				"tenedor:tenedor_id",
				"tenedor:tenedor_nombrecompleto",
				"provincia:provincia_id",
				"provincia:provincia_descripcion"
			)
		);
		$this->view->tenedores = Tenedor::findAllWithColeccion(true);
		$this->view->provincias = Provincia::findAllWithColeccion(true);
  } 
    
    
    
	public function viewAction() 
  { 
  	if ($this->getRequest()->getParam('print'))
  	{
  		$this->_helper->layout->setLayout('print');
  	}
    $this->view->pageTitle = "Colección";
    $this->view->coleccion = Coleccion::findByPK($this->getRequest()->getParam('id'));
    $this->view->paises = Pais::findAllFromColeccion($this->getRequest()->getParam('id'));
    $this->view->tipos_material = TipoMaterial::findAllFromColeccion($this->getRequest()->getParam('id'));
  }
    
    
    
	public function editAction() 
  { 
    $this->view->pageTitle = "Edición de Colección";
    $coleccion = Coleccion::findByPK($this->getRequest()->getParam('id'));
    $form = new FormColeccion();
    if ($this->getRequest()->isPost()) 
    {
			if ($form->isValid($_POST)) 
			{
				$coleccion->setAll($form->getValues());
				$coleccion->save();
				
				ColeccionPais::deleteAllFromColeccion($coleccion->id);
				foreach (explode(',',$form->getValue('paises')) as $pais_id)
				{
					$coleccion_pais = new ColeccionPais();
					$coleccion_pais->coleccion_id = $coleccion->id;
					$coleccion_pais->pais_id = $pais_id;
					$coleccion_pais->save();
				}
				
				ColeccionTipoMaterial::deleteAllFromColeccion($coleccion->id);
				foreach (explode(',',$form->getValue('tipos_material')) as $tipo_material_id)
				{
					$coleccion_tipo_material = new ColeccionTipoMaterial();
					$coleccion_tipo_material->coleccion_id = $coleccion->id;
					$coleccion_tipo_material->tipo_material_id = $tipo_material_id;
					$coleccion_tipo_material->save();
				}
				
				$completador = new Completador();
				$completador->tipo_doc_completador_id = $coleccion->tipo_doc_completador_id;
				$completador->nro_doc = $coleccion->completador_nro_doc;
				$completador->nombre_completo = $coleccion->completado_por;
				$completador->save(); 
				
				
				Zend_Registry::get('messagehandler')->add('INFO', 'Se guardaron los datos de la Colección.');
				return $this->_redirect(getControllerUrl('coleccion'));
			}
    }
    else
    {
    	$form->populate((array)$coleccion);
    	if ($coleccion->id > 0)
    	{
    		$form->populate(array('paises'=> implode(',', extractAttributeArray(ColeccionPais::findAllFromColeccion($coleccion->id), 'pais_id'))));
    		$form->populate(array('tipos_material'=> implode(',', extractAttributeArray(ColeccionTipoMaterial::findAllFromColeccion($coleccion->id), 'tipo_material_id'))));
    	}
    	$form->populate(array('hidden_provincia_id' => $coleccion->provincia_id ));
    	$form->populate(array('hidden_departamento_id' => $coleccion->departamento_id ));
    	$form->populate(array('hidden_localidad_id' => $coleccion->localidad_id ));
    }
    
    $this->view->countChildObjeto = $coleccion->getCountChildObjeto();
    $this->view->countChildLote = $coleccion->getCountChildLote();
    $this->view->sumChildLoteCantidadObjetos = $coleccion->getSumChildLoteCantidadObjetos(); 
    $this->view->form = $form;
  }
    
	public function deleteAction() 
  { 
    $coleccion = Coleccion::findByPK($this->getRequest()->getParam('id'));
    Coleccion::delete($coleccion);
    Zend_Registry::get('messagehandler')->add('INFO', 'Se eliminaron los datos de la Colección.');
    return $this->_redirect(getControllerUrl('coleccion'));
  }
  
  private function setGrid()
  {
    $this->grid = new dotDev_Grid_AdapterExt(
      new ColeccionQuery(),
      array(
          new dotDev_Grid_FilterField(
           'c_sigla', 
            array(
              'label'=>'Sigla', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING
          )),
          new dotDev_Grid_FilterField(
           'c_nombre', 
            array(
              'label'=>'Nombre', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
          )),
          new dotDev_Grid_FilterField(
           'p_descripcion', 
            array(
              'label'=>'Provincia', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('p_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Provincia::findAll(true)),
              'hidden'=> false
           )),
          new dotDev_Grid_FilterField(
           'ac_nombre', 
            array(
              'label'=>'A. Culturales', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
	            'applyFilterOn'=>new dotDev_Grid_FilterField('ac_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
	            'listFilterOptions'=>pairsToExtJson(AdscripcionCultural::findAll(true)),
              'hidden'=> false
           )),
          new dotDev_Grid_FilterField(
           'tm_descripcion', 
            array(
              'label'=>'T. Material', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('tm_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(TipoMaterial::findAll(true)),
              'hidden'=> false
           )),
          new dotDev_Grid_FilterField(
           'count_l_id', 
            array(
              'label'=>'Cant. Lotes', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC,
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           'sum_l_cantidad_objetos', 
            array(
              'label'=>'Cant. Objs en Lotes', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC,
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           'count_o_id', 
            array(
              'label'=>'Cant. Objs', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC,
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           't_nombres', 
            array(
              'label'=>'Tenedor (nombre)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> false
           )),
           new dotDev_Grid_FilterField(
           't_apellido', 
            array(
              'label'=>'Tenedor (apellido)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> false
           )),
           new dotDev_Grid_FilterField(
           't_tipodoc', 
            array(
              'label'=>'Tenedor (tipo doc)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           't_nro_doc', 
            array(
              'label'=>'Tenedor (nro doc)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           't_localidad', 
            array(
              'label'=>'Tenedor (localidad)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           't_provincia', 
            array(
              'label'=>'Tenedor (provincia)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('t_provincia_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Provincia::findAll(true)),
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           't_pais', 
            array(
              'label'=>'Tenedor (pais)', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('t_pais_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Pais::findAll(true)),
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           'd_nombre', 
            array(
              'label'=>'Depósito', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           ))
      
           
      ),
      array(
        'id'=> 'colecciones',  
        'dataFeedUrl' => getControllerUrl('coleccion', 'grid'),
        'exportXlsUrl' => getControllerUrl('coleccion', 'gridcsv'),
        'printUrl' => getControllerUrl('coleccion', 'gridprint'),
        'dataId' => 'coleccion_id',
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