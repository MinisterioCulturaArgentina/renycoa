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

class LoteFotografia extends PersistentModel 
{
	var $id;
	var $lote_id;
	var $fotografia_id;
	
	var $_data_table = 'lote_fotografia';
	
	public static function findAllFromLote($lote_id, $in_pairs = false)
	{
		$db = Zend_Registry::get('db');
		$sql =	'SELECT * FROM lote_fotografia WHERE lote_id = '."'".$db->escape($lote_id)."'".' AND ISNULL(deleted_at)';
		return self::findMultiple($sql, get_class(), $in_pairs);
	}
	
	public static function deleteAllFromLote($lote_id, $in_pairs = false)
	{
		$collection = LoteFotografia::findAllFromLote($lote_id);
		foreach ($collection as $item)
		{
			LoteFotografia::delete($item);
		}
	}
}