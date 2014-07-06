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

class dotDev_Validate_CharSumLessThanMax extends Zend_Validate_Abstract
{

   const MAX_EXCEEDED = 'maxExceeded';

    protected $_messageTemplates = array(
        self::MAX_EXCEEDED => "por la suma de los 'mínimos' establecidos, debe ser mayor o igual a '%sum%'"
    );

    protected $_messageVariables = array(
        'sum' => '_sum'
    );
    
    protected $_elems;
    protected $_sum;

    public function __construct($elements = array())
    {
    	$this->setElems($elements);
    }
 
	public function getElems()
    {
        return $this->_elems;
    }

    public function setElems($elems)
    {
        $this->_elems = $elems;
        return $this;
    }
    
	public function getSum()
    {
        return $this->_sum;
    }

    public function setSum($sum)
    {
        $this->_sum = $sum;
        return $this;
    }
    
 
    public function isValid($value, $context = null)
    { 
       	$elems = $this->getElems();
        $total = 0;
        foreach ($elems as $el)
        {
        	 $total += ( is_numeric($context[$el]) ) ? $context[$el] : 0;
        }
        
        if ($value < $total)
        {
        	$this->setSum($total);
        	$this->_error();
            return false;
        }
        return true;
    }
}
