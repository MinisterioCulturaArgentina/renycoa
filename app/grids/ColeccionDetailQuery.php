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

class ColeccionDetailQuery extends dotDev_Grid_DbQueryMySql  
{	

	
	public function __construct($coleccion_id)
  {
		
		
		$db = Zend_Registry::get('db');
		
	  $this->selectExpr = " data.id as id,
                        tipo,
                        sigla,
                        data.nombre,
                        coleccion_id, 
                        tipo_material_id, 
                        tipo_material, 
                        adscripcion_cultural_id,
                        adscripcion_cultural,
                        localidad.descripcion as localidad,
                        provincia.descripcion as provincia,
                        pais.descripcion as pais";

  $this->tableRefs  = " (
		                      SELECT * 
		                      FROM (
		                        SELECT 
	                            CONCAT('L',l.id )as id,
	                            'LOT' as tipo,
	                            l.sigla as sigla,
	                            l.nombre as nombre,
	                            l.coleccion_id as coleccion_id, 
	                            l.tipo_material_id as tipo_material_id, 
	                            tm.descripcion as tipo_material, 
	                            GROUP_CONCAT(ac.nombre) as adscripcion_cultural,
	                            l.localidad_id as localidad_id,
	                            l.provincia_id as provincia_id,
	                            l.pais_id as pais_id
	                          FROM lote as l
	                          LEFT JOIN  lote_adscripcion_cultural as lac ON lac.lote_id = l.id 
	                          INNER JOIN adscripcion_cultural as ac ON ac.id = lac.adscripcion_cultural_id
	                          LEFT JOIN  tipo_material as tm ON tm.id = l.tipo_material_id 
	                          WHERE ISNULL(lac.deleted_at)
	                          AND ISNULL(l.deleted_at)
	                          AND l.coleccion_id = ".$db->escape($coleccion_id)."
	                          GROUP BY l.id
	                        ) as sub
		                      UNION (
	                          SELECT 
	                            CONCAT('O',o.id )as id, 
	                            'OBJ' as tipo,
	                            o.sigla as sigla,
	                            o.nombre as nombre,
	                            o.coleccion_id as coleccion_id, 
	                            o.tipo_material_id as tipo_material_id, 
	                            tm.descripcion as tipo_material, 
	                            ac.nombre as adscripcion_cultural,
	                            o.localidad_id as localidad_id,
	                            o.provincia_id as provincia_id,
	                            o.pais_id as pais_id
	                          FROM objeto as o
	                          LEFT JOIN adscripcion_cultural as ac ON ac.id = o.adscripcion_cultural_id
	                          LEFT JOIN  tipo_material as tm ON tm.id = o.tipo_material_id 
	                          WHERE ISNULL(o.deleted_at)
	                          AND o.coleccion_id = ".$db->escape($coleccion_id)."
	                          GROUP BY o.id 
	                        ) 
	                      ) as data
	                      LEFT JOIN
                        (
	                        SELECT * 
	                        FROM (
                            SELECT 
                              CONCAT('L',l.id )as id, 
                              lac.adscripcion_cultural_id as adscripcion_cultural_id
                            FROM lote as l
                            INNER JOIN  lote_adscripcion_cultural as lac ON lac.lote_id = l.id 
                            WHERE ISNULL(lac.deleted_at)
                            AND ISNULL(l.deleted_at)
                          ) as sub
                          UNION (
                            SELECT 
                              CONCAT('O',o.id )as id, 
                              o.adscripcion_cultural_id as adscripcion_cultural_id
                            FROM objeto as o
                            WHERE ISNULL(o.deleted_at)
                          ) 
                        ) as ac ON data.id = ac.id
                        LEFT JOIN provincia ON provincia.id = data.provincia_id
                        LEFT JOIN localidad ON localidad.id = data.localidad_id
                        LEFT JOIN pais ON pais.id = data.pais_id";  
  
    $this->groupByExpr = 'id';
    
    parent::__construct();
	}
	
}