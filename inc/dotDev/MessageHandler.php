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

class MessageHandler
{
	var $ERROR = 'ERROR';
	var $INFO = 'INFO';
	
	function add($type, $message)
	{
		if (!isset ($_SESSION['MSG']) || !is_array($_SESSION['MSG']))
		{
			$_SESSION['MSG'] = array();
		}
		array_push($_SESSION['MSG'], array("TYPE" => $type, "MSG" => $message));
	}
	
	function hasMessages()
	{
		if (isset($_SESSION['MSG']) && is_array($_SESSION['MSG']) && count($_SESSION['MSG'])>0 )
		{
			return true;
		}
	}
	
	function get()
	{	
		if ($this->hasMessages())
		{
			$this->printed = true;
			$messages = $_SESSION['MSG'];
			$_SESSION['MSG'] = array();
			return $messages;
		}
	}
}
