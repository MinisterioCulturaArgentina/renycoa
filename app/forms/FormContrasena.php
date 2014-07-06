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

class FormContrasena extends Zend_Form
{
	var $enableRol;
	
	public function __construct($options = null)
	{
		$this->addElementPrefixPath('dotDev', 'dotDev/');
		parent::__construct($options);
	
		$this->setName('Actualización de Contraseña');
		$this->setAttrib('id', 'form_contrasena');	


		
		$confSeguridad = ConfSeguridad::findCurrent();
		$contrasena = new Zend_Form_Element_Password('contrasena');
		$contrasena_desc = 'Largo mín.: '.$confSeguridad->largo_min.' / Largo máx.: '. $confSeguridad->largo_max.'<br />';
		$contrasena_desc .= 'caracteres permitidos: <br />';
		$contrasena_desc .= '- letras a-z (sin ñ ni acentos)'.(($confSeguridad->min_letras)?', mín. '.$confSeguridad->min_letras:'').'<br />';
		$contrasena_desc .= '- números 0-9'.(($confSeguridad->min_numeros)?', mín. '.$confSeguridad->min_numeros:'');
		if (strlen($confSeguridad->simbolos))
		{
			$contrasena_desc .= '<br />- símbolos: '.implode(' ',str_split($confSeguridad->simbolos)).(($confSeguridad->min_simbolos)?' mín. '.$confSeguridad->min_simbolos:'');			  
		}
		$contrasena->addValidator('stringLength', false, array($confSeguridad->largo_min, $confSeguridad->largo_max))
				   ->addValidator(new dotDev_Validate_CharCountLetters($confSeguridad->min_letras), false)
				   ->addValidator(new dotDev_Validate_CharCountNumbers($confSeguridad->min_numeros), false)
				   ->addValidator(new dotDev_Validate_CharCountSymbols($confSeguridad->min_simbolos, $confSeguridad->simbolos), false)
				   ->addValidator(new dotDev_Validate_ValidChars($confSeguridad->simbolos), false)
				   ->setDescription($contrasena_desc);

		$contrasena->setLabel('Nueva contraseña*');
		$contrasena->setRequired(true)
				   ->addValidator('notEmpty',true);

		$contrasena_confirm = new Zend_Form_Element_Password('contrasena_confirm');
		$contrasena_confirm->setLabel('Confirmar contraseña*');
		$contrasena_confirm->setRequired(true)
						   ->addValidator(new dotDev_Validate_PasswordEqual('contrasena'));
	
		$this->setDecorators(array(
		    array('ViewScript', array('viewScript' => '_forms/contrasena.phtml'))
		));
		

		$this->addElements(
			array(
				$contrasena,
				$contrasena_confirm
			)
		);
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
}