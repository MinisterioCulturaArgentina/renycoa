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

class dotDev_Validate_UniqueKey extends Zend_Validate_Abstract
{

    const NOT_UNIQUE = 'notUnique';

    
    protected $_messageTemplates = array(
        self::NOT_UNIQUE => "nombre ya utilizado"
    );

    protected $_className;
    protected $_attribute;
    protected $_exceptionElement;

    public function __construct($className, $attribute, $exceptionElement)
    {
        $this->setClassName($className);
 		$this->setAttribute($attribute);
        $this->setExceptionElement($exceptionElement);
    }

   
    public function getClassName()
    {
        return $this->_className;
    }

    public function setClassName($className)
    {
        $this->_className = $className;
        return $this;
    }

    public function getAttribute()
    {
        return $this->_attribute;
    }

    public function setAttribute($attribute)
    {
        $this->_attribute = $attribute;
        return $this;
    }
    
 	public function getExceptionElement()
    {
        return $this->_exceptionElement;
    }

    public function setExceptionElement($exceptionElement)
    {
        $this->_exceptionElement = $exceptionElement;
        return $this;
    }

    
    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        
        $className = $this->getClassName();
        $obj = new $className();
        $db = Zend_Registry::get('db');
        $sql = 'SELECT '.$this->getAttribute().
        	   ' FROM '.$obj->_data_table.
        	   ' WHERE '.
        			$this->getAttribute().' =  '."'".$db->escape($value)."' AND ".
        			'ISNULL(deleted_at)';
       
        if (isset ($context[$this->getExceptionElement()]) && $context[$this->getExceptionElement()] != '' )
        {
        	$sql .= ' AND id != '."'".$db->escape($context[$this->getExceptionElement()])."'";
        }

        if ($db->get_results($sql))
        {
            $this->_error();
            return false;
        }
        return true;
    }

}
