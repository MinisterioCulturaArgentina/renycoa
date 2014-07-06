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

class dotDev_Validate_GreaterThanEqualElement extends Zend_Validate_Abstract
{

   const NOT_GREATER = 'notGreaterThan';

    protected $_messageTemplates = array(
        self::NOT_GREATER => "debe ser mayor o igual a '%min%'"
    );

    protected $_messageVariables = array(
        'min' => '_min',
        'elem' => '_elem'
    );

    protected $_min;
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
    
    public function getMin()
    {
        return $this->_min;
    }

    public function setMin($min)
    {
        $this->_min = $min;
        return $this;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        if (isset($context[$this->getElem()]) && is_numeric($context[$this->getElem()]))
        {
        	$this->setMin($context[$this->getElem()]);
        }
        if ($this->_min > $value) {
            $this->_error();
            return false;
        }
        return true;
    }
}
