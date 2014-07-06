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

class ColeccionDetalleController extends CustomController 
{   
	
  var $grid;

  public function indexAction()
  {
    $this->view->pageTitle = "Reporte de Objetos y Lotes por Colección";
    $this->setGridScripts();
    if (!$coleccion_id = $this->getRequest()->getParam('coleccion_id',false))
    {
    	return $this->_redirect(getControllerUrl('coleccion'));
    } 
    $this->view->coleccion = Coleccion::findByPK($coleccion_id);
    $this->setGrid($coleccion_id);
    $this->view->headScript()->captureStart();
    echo $this->grid->getGridScript();
    $this->view->headScript()->captureEnd();
  }
  
  public function gridAction()
  {
  	if (!$coleccion_id = $this->getRequest()->getParam('coleccion_id',false))
    {
      return $this->_redirect(getControllerUrl('coleccion'));
    } 
    $this->setGrid($coleccion_id);
    $this->grid->fetchArgsFromRequest(true);
    $this->grid->fecthData();
    $this->_helper->json(array('total'=>$this->grid->getTotalRows(), 'data'=>$this->grid->getData()));
  }
  
  public function gridcsvAction()
  {
  	if (!$coleccion_id = $this->getRequest()->getParam('coleccion_id',false))
    {
      return $this->_redirect(getControllerUrl('coleccion'));
    }
    $coleccion = Coleccion::findByPK($coleccion_id);
     
    $session = new Zend_Session_Namespace('grids');
    $this->_helper->layout->setLayout('ajax');
    $this->getHelper("ViewRenderer")->setNoRender();
   // $this->setGrid();
    $this->grid = $session->coleccion_detalle;
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
    header("Content-Disposition: attachment; filename=reporte_detalle_coleccion-".$coleccion->sigla."-".date('Ymd').".csv");
    
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
  	if (!$coleccion_id = $this->getRequest()->getParam('coleccion_id',false))
    {
      return $this->_redirect(getControllerUrl('coleccion'));
    }
    $coleccion = Coleccion::findByPK($coleccion_id);
    $session = new Zend_Session_Namespace('grids');
      
    $this->_helper->layout->setLayout('print');
    $this->_helper->viewRenderer->setRender('grid-export',null,true);
    $this->view->pageTitle = "Reporte de Objetos y Lotes por Colección - ".$coleccion->sigla." - ".date('Y-m-d');
    //$this->setGrid();
    $this->grid = $session->coleccion_detalle;
    $this->grid->pageSize = 0;
    $this->grid->fecthData();
    $this->view->fields = $this->grid->fields;
    $this->view->data = $this->grid->getData();
  }

   
  private function setGrid($coleccion_id)
  {
    $this->grid = new dotDev_Grid_AdapterExt(
      new ColeccionDetailQuery($coleccion_id),
      array(
          new dotDev_Grid_FilterField(
           'tipo', 
            array(
              'label'=>'Tipo', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING
          )),
          new dotDev_Grid_FilterField(
           'sigla', 
            array(
              'label'=>'Sigla', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING
          )),
          new dotDev_Grid_FilterField(
           'nombre', 
            array(
              'label'=>'Nombre', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> false
          )),
          new dotDev_Grid_FilterField(
           'adscripcion_cultural', 
            array(
              'label'=>'A. Cultural', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
	            'applyFilterOn'=>new dotDev_Grid_FilterField('adscripcion_cultural_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
	            'listFilterOptions'=>pairsToExtJson(AdscripcionCultural::findAll(true)),
              'hidden'=> false
           )),
          new dotDev_Grid_FilterField(
           'tipo_material', 
            array(
              'label'=>'T. Material', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('tipo_material_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(TipoMaterial::findAll(true)),
              'hidden'=> false
           )),
           new dotDev_Grid_FilterField(
           'localidad', 
            array(
              'label'=>'Localidad', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_STRING,
              'hidden'=> true
           )),
           new dotDev_Grid_FilterField(
           'provincia', 
            array(
              'label'=>'Provincia', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('provincia_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Provincia::findAll(true)),
              'hidden'=> true
           )),
          new dotDev_Grid_FilterField(
           'pais', 
            array(
              'label'=>'Pais', 
              'valueType'=>dotDev_Grid_FilterField::VALUE_LIST,
              'applyFilterOn'=>new dotDev_Grid_FilterField('pais_id', array('valueType'=>dotDev_Grid_FilterField::VALUE_NUMERIC)),
              'listFilterOptions'=>pairsToExtJson(Pais::findAll(true)),
              'hidden'=> true
           ))
      
           
      ),
      array(
        'id'=> 'coleccion_detalle',  
        'dataFeedUrl' => getControllerUrl('coleccion-detalle', 'grid',array('coleccion_id'=>$coleccion_id)),
        'exportXlsUrl' => getControllerUrl('coleccion-detalle', 'gridcsv',array('coleccion_id'=>$coleccion_id)),
        'printUrl' => getControllerUrl('coleccion-detalle', 'gridprint',array('coleccion_id'=>$coleccion_id)),
        'dataId' => 'id',
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