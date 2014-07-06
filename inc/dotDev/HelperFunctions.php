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

function superecho($var, $die = false)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	if ($die)
		die();
}

function getControllerUrl($controller, $action = 'index', $params = array())
{
	if (Zend_Registry::get('config')->url_rewrite)
	{
		$url = Zend_Registry::get('config')->base_url.$controller.'/'.$action;
		foreach ($params as $k=>$v)
		{
			$url .= '/'.$k.'/'.$v;
		}
		$url .= '/';
	}
	else
	{
		$url =  Zend_Registry::get('config')->base_url.'index.php?controller='.$controller.'&action='.$action;
		foreach ($params as $k=>$v)
		{
			$url .= '&'.$k.'='.$v;
		}
	}
	return $url;
}

function extractAttributeArray($arrayOfObjects, $attribute)
{
	$res = array();	
	foreach ((array)$arrayOfObjects as $obj)
	{
		array_push($res,$obj->$attribute);
	}
	return $res;
}

function findObjectInArray($arrayOfObjects, $attribute, $value)
{
	foreach ((array)$arrayOfObjects as $obj)
	{
		if ($obj->$attribute == $value)
			return $obj;
	}
}

function pairsToExtJson($arrayOfPairs)
{
	$res = '[';
	$first = true;	
	foreach ((array)$arrayOfPairs as $k=>$v)
	{
		if (!$first)
			$res .= ',';
			
		$res .= '{"id":'.json_encode($k).',"text":'.json_encode($v).'}';
		$first = false;
	}
	$res .= ']';
	return $res;
}

function pairsToExtArray($arrayOfPairs)
{
	$res = array();
	foreach ((array)$arrayOfPairs as $k=>$v)
	{
		array_push($res, array("id"=>$k, "text"=>$v));
	}

	return $res;
}

function getYearsArray()
{
	$res = array();
	for ($i = Zend_Date::now()->toValue(Zend_Date::YEAR); $i >= 1800; $i--)
	{
		$res[$i] = $i;
	}
	return $res;
}

function getSize ($intBytes)
{
	$size = $intBytes;
	$unit = "bytes";
	
	if ($size >= 1024)
	{
		$size = $size / 1024;
		$unit = "kb";
	}
	if ($size >= 1024)
	{
		$size = $size / 1024;
		$unit = "Mb";
		
	}
	return intval($size)." ".$unit;	
}

/****************************************************************
    By Darien Hager, Jan 2007... Use however you wish, but please
    please give credit in source comments.
   
    Change "UTF-8" to whichever encoding you are expecting to use.
 ****************************************************************/
function ords_to_unistr($ords, $encoding = 'UTF-8'){
    // Turns an array of ordinal values into a string of unicode characters
    $str = '';
    for($i = 0; $i < sizeof($ords); $i++){
        // Pack this number into a 4-byte string
        // (Or multiple one-byte strings, depending on context.)               
        $v = $ords[$i];
        $str .= pack("N",$v);
    }
    $str = mb_convert_encoding($str,$encoding,"UCS-4BE");
    return($str);           
}

function unistr_to_ords($str, $encoding = 'UTF-8'){       
    // Turns a string of unicode characters into an array of ordinal values,
    // Even if some of those characters are multibyte.
    $str = mb_convert_encoding($str,"UCS-4BE",$encoding);
    $ords = array();
   
    // Visit each unicode character
    for($i = 0; $i < mb_strlen($str,"UCS-4BE"); $i++){       
        // Now we have 4 bytes. Find their total
        // numeric value.
        $s2 = mb_substr($str,$i,1,"UCS-4BE");                   
        $val = unpack("N",$s2);           
        $ords[] = $val[1];               
    }       
    return($ords);
}
/*****************************************************************/