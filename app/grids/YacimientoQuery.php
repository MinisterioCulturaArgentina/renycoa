<?php
/**
 * Copyright (C) 2009 Marcelo Costanzi - www.dotdev.com.ar
 * 
 * This file is part of JDA Building Manager
 *
 * JDA Building Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * JDA Building Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with JDA Building Manager.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

class YacimientoQuery extends dotDev_Grid_DbQueryMySql  
{	
	var $selectExpr 	= "	yac1.id as y_id,
										    yac1.sigla as y_sigla,
										    yac1.denominacion_sitio as y_denominacion,
										    yac1.superficie as y_superficie,
										    yac1.observaciones as y_observaciones,
										    tsit.id as y_tipo_sitio_id,
										    tsit.descripcion as y_tipo_sitio,
										    yac2.ac_nombre as ac_nombre,
										    yac3.fi_descripcion as fi_descripcion,
										    yac4.rr_descripcion as rr_descripcion,
										    yac5.tr_descripcion as tr_descripcion, 
										    loc.descripcion as y_localidad,
										    pro.id as y_provincia_id,
										    pro.descripcion as y_provincia,
										    pai.id as y_pais_id,
										    pai.descripcion as y_pais,
										    trea.id as trea_id,
										    finf.id as finf_id,
										    acul.id as acul_id,
										    rrup.id as rrup_id,
										    yac1.created_at as y_created_at";

	var $tableRefs 	= '	yacimiento as yac1 
  
										  LEFT JOIN (
										    SELECT y.id as y_id, GROUP_CONCAT(ac.nombre) as ac_nombre
                        FROM yacimiento as y
                        INNER JOIN  yacimiento_adscripcion_cultural as yac ON yac.yacimiento_id = y.id 
                        INNER JOIN adscripcion_cultural as ac ON ac.id = yac.adscripcion_cultural_id
                        WHERE ISNULL(yac.deleted_at)
                        AND ISNULL(ac.deleted_at)
                        GROUP BY y.id
										  ) as yac2 ON yac2.y_id = yac1.id
									  
										  LEFT JOIN (
										    SELECT y.id as y_id, GROUP_CONCAT(fi.descripcion) as fi_descripcion
                        FROM yacimiento as y
                        INNER JOIN  yacimiento_funcion_inferida as yfi ON yfi.yacimiento_id = y.id 
                        INNER JOIN funcion_inferida as fi ON fi.id = yfi.funcion_inferida_id
                        WHERE ISNULL(yfi.deleted_at)
                        AND ISNULL(fi.deleted_at)
                        GROUP BY y.id
                      ) as yac3 ON yac3.y_id = yac1.id
                      
                      LEFT JOIN (
                        SELECT y.id as y_id, GROUP_CONCAT(rr.descripcion) as rr_descripcion
                        FROM yacimiento as y
                        INNER JOIN  yacimiento_representacion_rupestre as yrr ON yrr.yacimiento_id = y.id 
                        INNER JOIN representacion_rupestre as rr ON rr.id = yrr.representacion_rupestre_id
                        WHERE ISNULL(yrr.deleted_at)
                        AND ISNULL(rr.deleted_at)
                        GROUP BY y.id
                      ) as yac4 ON yac4.y_id = yac1.id
                      
                      LEFT JOIN (
                        SELECT y.id as y_id, GROUP_CONCAT(tr.descripcion) as tr_descripcion
                        FROM yacimiento as y
                        INNER JOIN  yacimiento_tarea_realizada as ytr ON ytr.yacimiento_id = y.id 
                        INNER JOIN tarea_realizada as tr ON tr.id = ytr.tarea_realizada_id
                        WHERE ISNULL(ytr.deleted_at)
                        AND ISNULL(tr.deleted_at)
                        GROUP BY y.id
                      ) as yac5 ON yac5.y_id = yac1.id

										  LEFT JOIN tipo_sitio as tsit ON tsit.id = yac1.tipo_sitio_id
										  LEFT JOIN localidad as loc ON loc.id = yac1.localidad_id
										  LEFT JOIN provincia as pro ON pro.id = yac1.provincia_id
										  LEFT JOIN pais as pai ON pai.id = yac1.pais_id
										  
										  LEFT JOIN yacimiento_adscripcion_cultural as yacul ON yacul.yacimiento_id = yac1.id 
                      LEFT JOIN adscripcion_cultural as acul ON acul.id = yacul.adscripcion_cultural_id
                      
                      LEFT JOIN yacimiento_funcion_inferida as yfinf ON yfinf.yacimiento_id = yac1.id 
                      LEFT JOIN funcion_inferida as finf ON finf.id = yfinf.funcion_inferida_id
                      
                      LEFT JOIN yacimiento_tarea_realizada as ytrea ON ytrea.yacimiento_id = yac1.id 
                      LEFT JOIN tarea_realizada as trea ON trea.id = ytrea.tarea_realizada_id
                      
                      LEFT JOIN yacimiento_representacion_rupestre as yrrup ON yrrup.yacimiento_id = yac1.id 
                      LEFT JOIN representacion_rupestre as rrup ON rrup.id = yrrup.representacion_rupestre_id';	
	
	var $groupByExpr = 'y_id';
	
	public function __construct($filters = array())
  {
  	$session = new Zend_Session_Namespace();
		
		parent::__construct();
		$this->initialFilters = array_merge($filters,
		  array(
				new dotDev_Grid_FilterField(
					'yac1.deleted_at', 
					array(
						'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
				)),
				new dotDev_Grid_FilterField(
          'yacul.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'acul.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'yfinf.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'finf.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'ytrea.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'trea.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'yrrup.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'rrup.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        ))
			)
		);
	}
	
}