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

class ColeccionQuery extends dotDev_Grid_DbQueryMySql  
{	
	var $selectExpr 	= "	col1.id as c_id,
										    col1.sigla as c_sigla,
										    col1.nombre as c_nombre,
										    col6.ac_id as ac_id,
										    col2.ac_nombre as ac_nombre,
										    tmat.id as tm_id,
										    col5.tm_descripcion as tm_descripcion, 
										    col3.count_l_id as count_l_id, 
										    col3.sum_l_cantidad_objetos as sum_l_cantidad_objetos, 
										    col4.count_o_id as count_o_id,
										    ten.nombres as t_nombres,
										    ten.apellido as t_apellido,
										    loc.descripcion as t_localidad,
										    pro2.id as t_provincia_id,
										    pro2.descripcion as t_provincia,
										    pai.id as t_pais_id,
										    pai.descripcion as t_pais,
										    ten.nro_doc as t_nro_doc,
										    tdoc.descripcion as t_tipodoc,
										    pro.id as p_id,
										    pro.descripcion as p_descripcion,
										    dep.nombre as d_nombre,
										    col1.created_at as c_created_at";

	var $tableRefs 	= '	coleccion as col1 
  
										  LEFT JOIN (
										    SELECT c_sub.c_id as c_id, GROUP_CONCAT(c_sub.ac_nombre) as ac_nombre
                        FROM (
                          SELECT c.id as c_id, ac.nombre as ac_nombre
                          FROM coleccion as c 
                          LEFT JOIN lote as l ON l.coleccion_id = c.id 
                          INNER JOIN lote_adscripcion_cultural as lac ON lac.lote_id= l.id
                          INNER JOIN adscripcion_cultural as ac ON ac.id = lac.adscripcion_cultural_id
                          WHERE  
                          ISNULL(l.deleted_at) AND 
                          ISNULL(lac.deleted_at)
                          GROUP BY ac.id
                        ) as c_sub
                        GROUP BY c_sub.c_id
										  ) as col2 ON col2.c_id = col1.id
										    
										  LEFT JOIN (
										    SELECT c.id as c_id, COUNT(l.id) as count_l_id, SUM(l.cantidad_objetos) as sum_l_cantidad_objetos
										    FROM coleccion as c
										    INNER JOIN lote as l ON l.coleccion_id = c.id 
										    WHERE ISNULL(l.deleted_at)
										    GROUP BY c.id
										  ) as col3 ON col3.c_id = col1.id
										  
										  LEFT JOIN (
										    SELECT c.id as c_id, COUNT(o.id) as count_o_id
										    FROM coleccion as c INNER JOIN objeto as o ON o.coleccion_id = c.id
										    WHERE ISNULL(o.deleted_at)
										    GROUP BY c.id
										  ) as col4 ON col4.c_id = col1.id
										  
										  LEFT JOIN (
										    SELECT c.id as c_id, GROUP_CONCAT(tm.descripcion) as tm_descripcion
										    FROM coleccion as c
										    INNER JOIN  coleccion_tipo_material as ctm ON ctm.coleccion_id = c.id 
										    INNER JOIN tipo_material as tm ON tm.id = ctm.tipo_material_id
										    WHERE ISNULL(ctm.deleted_at)
										    AND ISNULL(tm.deleted_at)
										    GROUP BY c.id
										  ) as col5 ON col5.c_id = col1.id
										  
										  LEFT JOIN (
                        SELECT c.id as c_id, ac.id as ac_id
                        FROM coleccion as c 
                        LEFT JOIN lote as l ON l.coleccion_id = c.id 
                        INNER JOIN lote_adscripcion_cultural as lac ON lac.lote_id= l.id
                        INNER JOIN adscripcion_cultural as ac ON ac.id = lac.adscripcion_cultural_id
                        WHERE  
                        ISNULL(l.deleted_at) AND 
                        ISNULL(lac.deleted_at)
                        GROUP BY ac.id
                      ) as col6 ON col6.c_id = col1.id
										  
										  LEFT JOIN tenedor as ten ON ten.id = col1.tenedor_id
										  LEFT JOIN tipo_doc as tdoc ON tdoc.id = ten.tipo_doc_id
										  LEFT JOIN localidad as loc ON loc.id = ten.localidad_id
										  LEFT JOIN provincia as pro2 ON pro2.id = ten.provincia_id
										  LEFT JOIN pais as pai ON pai.id = ten.pais_id
										  LEFT JOIN provincia as pro ON pro.id = col1.provincia_id
										  LEFT JOIN deposito as dep ON dep.id = col1.deposito_id 
										  LEFT JOIN coleccion_tipo_material as ctm ON ctm.coleccion_id = col1.id 
                      LEFT JOIN tipo_material as tmat ON tmat.id = ctm.tipo_material_id';	
	
	var $groupByExpr = 'c_id';
	
	public function __construct($filters = array())
  {
  	$session = new Zend_Session_Namespace();
		
		parent::__construct();
		$this->initialFilters = array_merge($filters,
		  array(
				new dotDev_Grid_FilterField(
					'col1.deleted_at', 
					array(
						'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
				)),
				new dotDev_Grid_FilterField(
          'ctm.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        )),
        new dotDev_Grid_FilterField(
          'tmat.deleted_at', 
          array(
            'filterType'=>dotDev_Grid_FilterField::FILTER_NULL
        ))
			)
		);
	}
	
}