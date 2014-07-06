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

class dotDev_Validate_CharCount extends Zend_Validate_Abstract
{

   const LESS_THAN_CHAR_COUNT = 'lessThanCharCount';

    protected $_messageTemplates = array(
        self::LESS_THAN_CHAR_COUNT => "debe tener al menos '%min%' %type%"
    );

    protected $_messageVariables = array(
        'min' => '_min',
    	'type' => '_type'
    );

	protected $_min;
    protected $_type;
    protected $_chars;

    public function __construct($min, $chars = array())
    {
       	$this->setMin($min);
       	$this->setChars($chars);
    }
    
	public function getMin()
    {
        return $this->_min;
    }

    public function setMin($min)
    {
        $this->_min = $min;
        return $this;
    }

	public function getType()
    {
        return $this->_type;
    }

    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }
    
	public function getChars()
    {
        return $this->_chars;
    }

    public function setChars($chars)
    {
        $this->_chars = $chars;
        return $this;
    }
    
 
    public function isValid($value)
    { 
        if ($this->getMin() > 0)
        {
        	$sum = 0;
   			foreach (unistr_to_ords($value) as $chr)
   			{
   				if (in_array($chr, $this->getChars()))
        		{
        			$sum++;
        		}
        	}
        	if ($sum < $this->getMin())
        	{
        		$this->_error();
            	return false;
        	}
        }
        return true;
    }
}
