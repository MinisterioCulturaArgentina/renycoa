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

class FormUsuario extends Zend_Form
{
	var $enableRol;
	
	public function __construct($isNewRecord, $enableRol, $options = null)
	{
		$this->enableRol = $enableRol;	
		
		$this->addElementPrefixPath('dotDev', 'dotDev/');
		parent::__construct($options);
	
		$this->setName('Edición de Usuario');
		$this->setAttrib('id', 'form_usuario');	

		$id = new Zend_Form_Element_Hidden('id');
		$id->setDecorators(array('ViewHelper'));
		
		$roles = array(''=>'--Seleccione--');
		$roles_datos = Rol::findAll(true); 
		foreach ( $roles_datos as $k=>$v)
		{
			$roles[$k] = $v;
		}
		$rol_id = new Zend_Form_Element_Select('rol_id');
		$rol_id->setLabel('Rol*')
				->setmultiOptions($roles);
		
		if($enableRol)
		{
			$rol_id->setRequired(true)
				    ->addValidator(new Zend_Validate_InArray(array_keys($roles_datos)));
		}
		
		$usuario = new Zend_Form_Element_Text('usuario');
		$usuario_desc = 'Largo mín.: 4 / Largo máx.: 20<br />';
		$usuario_desc .= 'caracteres permitidos: <br />';
		$usuario_desc .= '- letras a-z (sin ñ ni acentos)<br />';
		$usuario_desc .= '- números 0-9';
		$usuario->setLabel('Usuario*')
			  ->setRequired(true)
			  ->addValidator('stringLength', false, array(4, 20))
			  ->addValidator('Alnum')
			  ->addValidator(new dotDev_Validate_UniqueKey('Usuario','usuario', 'id'))
			  ->setDescription($usuario_desc);

		
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
		
				   
		if ($isNewRecord)
		{
			$contrasena->setLabel('Contraseña*');
			$contrasena->setRequired(true)
					   ->addValidator('notEmpty',true);
		}
		else
		{
			$contrasena->setLabel('Nueva contraseña*');
			$contrasena->addValidator(new dotDev_Validate_PasswordEqual('contrasena_confirm'));
		}
		
		$contrasena_confirm = new Zend_Form_Element_Password('contrasena_confirm');
		if ($isNewRecord)
		{
			$contrasena_confirm->setLabel('Confirmar contraseña*');
			$contrasena_confirm->setRequired(true)
							   ->addValidator(new dotDev_Validate_PasswordEqual('contrasena'));
		}
		else
		{
			$contrasena_confirm->setLabel('Confirmar nueva contraseña*');
		}
	
		$nombre = new Zend_Form_Element_Text('nombre');
		$nombre->setLabel('Nombres*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
				
		$apellido = new Zend_Form_Element_Text('apellido');
		$apellido->setLabel('Apellido*')
				->setRequired(true)
				->addValidator('stringLength', false, array(0, 255));
		
		
		$cargo = new Zend_Form_Element_Text('cargo');
		$cargo->setLabel('Cargo*')
			  ->setRequired(true)
			  ->addValidator('stringLength', false, array(0, 255));
		
		$observaciones = new Zend_Form_Element_Textarea('observaciones');
		$observaciones->setLabel('Observaciones');
		
		$fotografia_id = new Zend_Form_Element_Hidden('fotografia_id');
		$fotografia_id->setDecorators(array('ViewHelper'));
		
		$fotografia = new dotDev_Form_Element_File('fotografia');
        $fotografia->setLabel('Fotografía');

		if ($isNewRecord)
		{
			$this->setDecorators(array(
			    array('ViewScript', array('viewScript' => '_forms/usuario_new.phtml'))
			));
		}
		else
		{
			$this->setDecorators(array(
			    array('ViewScript', array('viewScript' => '_forms/usuario_edit.phtml'))
			));
		}
		

		$this->addElements(
			array(
				$id,
				$rol_id,
				$usuario,
				$contrasena,
				$contrasena_confirm,
				$nombre,
				$apellido,
				$cargo,
				$observaciones,
				$fotografia_id,
				$fotografia
			)
		);
		
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
}