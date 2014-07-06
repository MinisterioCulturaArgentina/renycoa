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

require_once 'phpThumbnailer/class.Thumbnail.php';

class Fotografia extends PersistentModel 
{
	var $id;	
	var $descripcion;
	var $nombre_archivo; 	
	var $tipo_mime;
	var $tamano;
	var $tamano_legible; 	
	
	var $_data_table = 'fotografia';
	
	public function __toString()
	{
		return $this->nombre;
	}

	public static function findByPK($pk)
	{
		$db = Zend_Registry::get('db');
		
		$sql =	'SELECT * FROM fotografia WHERE id = '."'".$db->escape($pk)."'";
		
		$rs = $db->get_row($sql);
		return self::hydrate($rs, get_class());
	}
	
	public static function findAllFromLote($lote_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql = 'SELECT DISTINCT
					fotografia.* 
				FROM 
					fotografia 
					INNER JOIN lote_fotografia ON lote_fotografia.fotografia_id = fotografia.id  
				WHERE 
					ISNULL(fotografia.deleted_at) AND 
					lote_fotografia.lote_id = '."'".$db->escape($lote_id)."'".' 
				ORDER 
					BY fotografia.created_at';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromObjeto($objeto_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql ='SELECT DISTINCT
					fotografia.* 
				FROM 
					fotografia
					INNER JOIN objeto_fotografia ON objeto_fotografia.fotografia_id = fotografia.id
				WHERE 
					ISNULL(fotografia.deleted_at) AND 
					objeto_fotografia.objeto_id = '."'".$db->escape($objeto_id)."'".' 
				ORDER 
					BY fotografia.created_at';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function findAllFromYacimiento($yacimiento_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql ='SELECT DISTINCT
					fotografia.* 
				FROM 
					fotografia
					INNER JOIN yacimiento_fotografia ON yacimiento_fotografia.fotografia_id = fotografia.id
				WHERE 
					ISNULL(fotografia.deleted_at) AND 
					yacimiento_fotografia.yacimiento_id = '."'".$db->escape($yacimiento_id)."'".' 
				ORDER 
					BY fotografia.created_at';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public function upload($file_handler, $generateThumbnail = true, $img_max_width = null, $thumb_max_width = null)
	{
		$db = Zend_Registry::get('db');
		$config = Zend_Registry::get('config');
		
		if ($img_max_width == null)
		{
			$img_max_width = $config->img_max_width;
		}
		if ($thumb_max_width == null)
		{
			$thumb_max_width = $config->thumb_max_width;
		}
		
		$this->tamano = $file_handler['size'];
		$this->tamano_legible = getSize($this->tamano);
		$this->tipo_mime = $file_handler['type'];
	
		$typeParts = explode('/',$this->tipo_mime);
		$ext = $typeParts[1];
		if ($ext == 'pjpeg')
		{
			 $ext = 'jpeg';
		}
		
		$this->nombre_archivo = time() . '.' . $ext;
			
		$full_path = $config->base_path.$config->img_folder.$this->nombre_archivo;
		move_uploaded_file($file_handler['tmp_name'], $full_path);
		chmod($full_path ,0777);
		$resized = new Thumbnail( $full_path, $img_max_width, $img_max_width);
		$resized->save($full_path);
		
		if($generateThumbnail)
		{
			$full_path_thumb = $config->base_path.$config->img_folder.'t'.$this->nombre_archivo;
			$thumbnail = new Thumbnail( $full_path, $thumb_max_width, $thumb_max_width);
			$thumbnail->save($full_path_thumb);
			chmod($full_path_thumb ,0777);
		}
		
		$error = false;

		if ($resized->error)
			$error = true;

		if ($generateThumbnail && $thumbnail->error)
			$error = true;
		
		if (!$error)
		{		
			parent::save();
		}
		else
		{
			@unlink($full_path);
			if ($generateThumbnail)
			{
			@unlink($full_path_thumb);
			}
			throw new ErrorException();
		}
	}
	
	public static function delete($instance)
	{
		/*
		$config = Zend_Registry::get('config');
		$full_path = $config->base_path.$config->img_folder.$instance->nombre_archivo;
		$full_path_thumb = $config->base_path.$config->img_folder.'t'.$instance->nombre_archivo;
		@unlink($full_path);
		@unlink($full_path_thumb);
		*/
		parent::delete($instance);
	}
	
}