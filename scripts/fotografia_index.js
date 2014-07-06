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
 
$(document).ready(function() {

	$('#pager').change(function(){
		window.location =  getUrl('fotografia','index', 'page='+$(this).val()) + '&' + URL_ARGS ;
	});
	  
});

function confirmDelete() 
{
	var agree = confirm ('¿Confirma la eliminación de la fotografía?');
	if (agree)
	{
		return true ;
	}
	else
	{
		return false ;
	}
}