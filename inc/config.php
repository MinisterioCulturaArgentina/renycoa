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

define ('PRODUCTION', false);

if ( PRODUCTION )
{
	return array(
		'base_path'	=>	'/var/www/renycoa/',
		'base_url'	=>	'http://renycoa.dotdev.com.ar/',
		'img_folder'=>	'fotos/',
		'img_max_width' => '600',
		'img_max_height' => '600',
		'thumb_max_width' => '100',
		'thumb_max_height' => '100',
		'img_folder'=>	'fotos/',
		'url_rewrite'	=>	true,
		'db' =>	array(
					'provider' => PersistentModel::PROVIDER_MYSQL,
					'params' => array(
						'host'	=>	'localhost',
						'dbname' => 'drumbit_renycoa',
						'username' => 'root',
						'password' => '',
				)
		),
		'grid_size' => 20,
		'grid' => array(
		'page_size' => 25,
		'dateformat_data'   => 'yyyy-MM-dd HH:mm:ss',
		'dateformat_print' => 'd/m/Y',
		'dateformat_query'  => 'Y-m-d'
    )
	);
}
else
{
	
	return array(
		'base_path'	=>	'/vhosts/renycoa/',
		'base_url'	=>	'http://renycoa.local/',
		'img_folder'=>	'fotos/',
		'img_max_width' => '600',
		'img_max_height' => '600',
		'thumb_max_width' => '100',
		'thumb_max_height' => '100',
		'url_rewrite'	=>	true,
		'db' =>	array(
					'provider' => PersistentModel::PROVIDER_MYSQL,
					'params' => array(
						'host'	=>	'localhost',
						'dbname' => 'dotdev_renycoa',
						'username' => 'root',
						'password' => '',
				)
		),
		'grid_size' => 10,
		'grid' => array(
		'page_size' => 25,
		'dateformat_data'   => 'yyyy-MM-dd HH:mm:ss',
		'dateformat_print' => 'd/m/Y',
		'dateformat_query'  => 'Y-m-d'
		)
	);
}
