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

$acl = new Zend_Acl();

$acl->add(new Zend_Acl_Resource('usuario'));
$acl->add(new Zend_Acl_Resource('usuario_self'));
$acl->add(new Zend_Acl_Resource('usuario_user'));
$acl->add(new Zend_Acl_Resource('usuario_super'));
$acl->add(new Zend_Acl_Resource('usuario_admin'));
$acl->add(new Zend_Acl_Resource('rol'));
$acl->add(new Zend_Acl_Resource('mensaje_self'));
$acl->add(new Zend_Acl_Resource('mensaje_others'));
$acl->add(new Zend_Acl_Resource('index'));
$acl->add(new Zend_Acl_Resource('tenedor'));
$acl->add(new Zend_Acl_Resource('coleccion'));
$acl->add(new Zend_Acl_Resource('coleccion-detalle'));
$acl->add(new Zend_Acl_Resource('deposito'));
$acl->add(new Zend_Acl_Resource('yacimiento'));
$acl->add(new Zend_Acl_Resource('lote'));
$acl->add(new Zend_Acl_Resource('objeto'));
$acl->add(new Zend_Acl_Resource('fotografia'));
$acl->add(new Zend_Acl_Resource('investigacion'));
$acl->add(new Zend_Acl_Resource('citabibliografica'));
$acl->add(new Zend_Acl_Resource('fechado'));
$acl->add(new Zend_Acl_Resource('pais'));
$acl->add(new Zend_Acl_Resource('provincia'));
$acl->add(new Zend_Acl_Resource('localidad'));
$acl->add(new Zend_Acl_Resource('departamento'));
$acl->add(new Zend_Acl_Resource('adscripcioncultural'));
$acl->add(new Zend_Acl_Resource('tipomaterial'));
$acl->add(new Zend_Acl_Resource('completador'));
$acl->add(new Zend_Acl_Resource('seguridad'));
$acl->add(new Zend_Acl_Resource('contrasena'));

$acl->addRole(new Zend_Acl_Role('user'));
$acl->addRole(new Zend_Acl_Role('super'));
$acl->addRole(new Zend_Acl_Role('admin'));

/**USER**/

//globales
$acl->allow('user', null, null);
$acl->deny('user', null, 'edit');
$acl->deny('user', null, 'delete');


//Mensajes
$acl->allow('user','mensaje_self','delete');
$acl->allow('user', 'index','edit');
$acl->allow('user', 'index','delete');

//Adsc. Culturales
$acl->deny('user', 'adscripcioncultural', null);
//Tipos de Material
$acl->deny('user', 'tipomaterial', null);

//Países
$acl->deny('user', 'pais', null);

//Usuarios
$acl->deny('user', 'usuario', null);

//Seguridad
$acl->deny('user', 'seguridad', null);


/** SUPER **/

//globales
$acl->allow('super', null, null);

//Mensajes
$acl->deny('super', 'mensaje_others', 'delete');

//Usuarios
$acl->deny('super', 'usuario_self', 'delete');
$acl->deny('super', 'usuario_super', 'delete');
$acl->deny('super', 'usuario_super', 'edit');
$acl->deny('super', 'usuario_admin', 'delete');
$acl->deny('super', 'usuario_admin', 'edit');
$acl->deny('super', 'rol', null);

//Seguridad
$acl->deny('super', 'seguridad', null);

/** ADMIN **/

//globales
$acl->allow('admin', null, null);

//Usuarios
$acl->deny('admin', 'usuario_self', 'delete');
