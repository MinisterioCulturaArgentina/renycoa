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

class dotDev_Validate_ValidChars extends Zend_Validate_Abstract
{

   const CONTAINS_INVALID_CHARS = 'containsInvalidChars';

    protected $_messageTemplates = array(
        self::CONTAINS_INVALID_CHARS => "contiene caracteres no válidos (%invalidChars%)"
    );

    protected $_messageVariables = array(
        'invalidChars' => '_invalidChars'
    );

	protected $_invalidChars;
    protected $_specialChars;

    public function __construct($specialChars = array())
    {
       	$this->setSpecialChars($specialChars);
    }
    
	public function getSpecialChars()
    {
        return $this->_specialChars;
    }

    public function setSpecialChars($specialChars)
    {
        $this->_specialChars = $specialChars;
        return $this;
    }
    
	public function getInvalidChars()
    {
        return $this->_invalidChars;
    }

    public function setInvalidChars($invalidChars)
    {
        $this->_invalidChars = $invalidChars;
        return $this;
    }
    
 
    public function isValid($value)
    { 
    	$letters = array_merge(range(65,90), range(97,122));
    	$numbers = range(48,57);
    	$simbols = unistr_to_ords($this->getSpecialChars());
    	
		$valid_chars = array_merge($letters, $numbers, $simbols);
		
		$invalid_chars = '';
		
		foreach (array_unique(unistr_to_ords($value)) as $test)
		{
			if (!in_array($test, $valid_chars))
			{
				$invalid_chars.=($invalid_chars != '')?', ':'';
				$invalid_chars.= ords_to_unistr(array($test));
			}
		}
        if ($invalid_chars != '')
        {
        	$this->setInvalidChars($invalid_chars);
        	$this->_error();
            return false;
        }
        return true;
    }
}
