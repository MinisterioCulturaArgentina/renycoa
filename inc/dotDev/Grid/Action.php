<?php
/**
 * Copyright (C) 2009 Marcelo Costanzi - www.dotdev.com.ar
 * 
 * This file is part of JDA Building Manager
 *
 * JDA Building Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * JDA Building Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with JDA Building Manager.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

class dotDev_Grid_Action
{
	var $actionId;
	var $destUrl;
	var $callbackFunc;
	var $argId = 'id';
	var $extraArgs = '';
	var $confirm = false;
	var $confirmText = 'Do you confirm the record deletion?';
	var $tooltip;
	var $hideable = false;
	
	public function __construct($actionId, $destUrl, $params = null)
	{
		$this->actionId = $actionId;
		$this->destUrl = $destUrl;
		
		if (is_array($params))
		{
			if (isset($params['argId']))
				$this->argId = $params['argId'];
				
			if (isset($params['extraArgs']))
				$this->extraArgs = $params['extraArgs'];
				
			if (isset($params['confirm']))
				$this->confirm = $params['confirm'];
				
			if (isset($params['confirmText']))
				$this->confirmText = $params['confirmText'];
				
			if (isset($params['tooltip']))
				$this->tooltip = $params['tooltip'];
				
			if (isset($params['hideable']))
				$this->hideable = $params['hideable'];
				
			if (isset($params['callbackFunc']))
				$this->callbackFunc = $params['callbackFunc'];
				
		}
	}
	
	public function getDefintionScript()
	{
		$ttString = isset($this->tooltip) ? "tooltip:'$this->tooltip'," : '';
		$hideString = ($this->hideable) ? "hideIndex:'hide_".$this->actionId."'," : '';    
		$funcName = $this->actionId.'_handler';
		
		if (!isset($this->callbackFunc))
		{
			$redirectString="";
			
			if (Zend_Registry::get('config')->url_rewrite)
			{
				$redirectString = "
					var url = '{$this->destUrl}' + '{$this->argId}/'+record.id+'/{$this->extraArgs}';
					window.location = url;
				";
			}
			else
			{
				$redirectString = "
					var url = '{$this->destUrl}';
					if (url.indexOf('?') == -1)
					{
						url += '?{$this->argId}='+record.id+'&{$this->extraArgs}';
					} 
					else
					{
						url += '&{$this->argId}='+record.id+'&{$this->extraArgs}';
					}
					window.location = url;
				";
			}
				
			$callbackString = $redirectString;
			if ($this->confirm)
			{	
				$callbackString ="
				
					Ext.Msg.show({
					title:'Delete',
					msg: '{$this->confirmText}',
					buttons: Ext.Msg.YESNO,
					fn: function(btn, text){
						if (btn =='yes')
						{
							{$redirectString}
						}
					}
				});";	

			}
		}
		else
		{
			$callbackString = $this->callbackFunc.'(grid, record, action, row, col);';
		}
			
		$script =<<<SCRIPT
		
						{
							iconCls:'{$this->actionId}',
							{$ttString}
							{$hideString}
							callback:function(grid, record, action, row, col) {
								{$callbackString}
							}
						}
SCRIPT;

		return $script;
	}
	
}
?>