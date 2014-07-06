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

class dotDev_Validate_ElementCharCount extends Zend_Validate_Abstract
{

   const NOT_CHAR_COUNT = 'notCharCount';

    protected $_messageTemplates = array(
        self::NOT_CHAR_COUNT => "'%elem%' está vacío"
    );

    protected $_messageVariables = array(
        'elem' => '_elem'
    );

    protected $_elem;

    public function __construct($element)
    {
       	$this->setElem($element);
       	
    }

	public function getElem()
    {
        return $this->_elem;
    }

    public function setElem($elem)
    {
        $this->_elem = $elem;
        return $this;
    }
    
 
    public function isValid($value, $context = null)
    { 
        if (is_numeric($value) && $value > 0  && 
        	(!isset($context[$this->getElem()]) || strlen($context[$this->getElem()]) < 1) )
        {
        	$this->_error();
            return false;
        }
        return true;
    }
}
