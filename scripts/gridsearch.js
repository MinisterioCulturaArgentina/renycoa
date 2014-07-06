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
 
function confirmDelete() 
{
	var agree = confirm ('¿Confirma la eliminación del registro?');
	if (agree)
	{
		return true ;
	}
	else
	{
		return false ;
	}
}

function pagerSetFormValue(obj, sGridId)
{
	var oOpt = obj.options[obj.selectedIndex];

	var param_value = oOpt.value;
	var param = sGridId+'::'+'quantity';
	var refresh = true;
	setFormValue(param_value, param, refresh);
}

function setFormValue(param_value, param, refresh)
{			
	var oForm = document.getElementById('GridForm');
	
	if(oForm != null)
	{
		var oParam = document.getElementById(param);
		if(oParam != null)
		{	
			oParam.value = param_value;
		}
		if(refresh == true)
		{
			oForm.submit();
		}
	}
}

function gridSearch(sTrSearchParams, sGridId)
{
	var oForm = document.getElementById('GridForm');
	if(oForm != null)
	{
		var oTrSearch = document.getElementById(sTrSearchParams);
		if(oTrSearch != null)
		{
			var aInputs = oTrSearch.getElementsByTagName('input');
			for(var i = 0; i < aInputs.length; i++)
			{
				var oInput = aInputs[i];
				var sInputName = oInput.name;
				var sInputValue = oInput.value;
				var sInputNameForm =  sGridId + '::' +  sInputName.substring(7, sInputName.length);
				
				var oInputForm = document.getElementById(sInputNameForm);
				if(oInputForm != null)
				{
					if(oInput.value != "")
					{
						oInputForm.value = oInput.value;
					}
					else
					{
						remove(oInputForm, oForm);
					}
				}
				else
				{
					if(oInput.value != "")
					{
						var oInputNew = document.createElement('input');
						oInputNew.setAttribute('name' , sInputNameForm);
						oInputNew.setAttribute('id' , sInputNameForm);
						oInputNew.setAttribute('value' , sInputValue);
						oInputNew.setAttribute('type' , 'hidden');
						oForm.appendChild(oInputNew);
					}
				}
			}

			var aSelects = oTrSearch.getElementsByTagName('select');
			for(var i = 0; i < aSelects.length; i++)
			{
				var oSelect = aSelects[i];
				var sSelectName = oSelect.name;
				var sSelectValue = oSelect.value;
				var sSelectNameForm =  sGridId + '::' + sSelectName.substring(7, sSelectName.length);
				
				var oSelectForm = document.getElementById(sSelectNameForm);
				if(oSelectForm != null)
				{
					if(oSelect.options[oSelect.selectedIndex].value != "")
					{
						oSelectForm.value = oSelect.options[oSelect.selectedIndex].value;
					}
					else
					{
						remove(oSelectForm, oSelect);
					}
				}
				else
				{
					if(oSelect.options[oSelect.selectedIndex].value != "")
					{
						var oInputNew = document.createElement('input');
						oInputNew.setAttribute('name' , sSelectNameForm);
						oInputNew.setAttribute('id' , sSelectNameForm);
						oInputNew.setAttribute('value' , sSelectValue);
						oInputNew.setAttribute('type' , 'hidden');
						oForm.appendChild(oInputNew);
					}
				}
			}
		}
		setFormValue('true', sGridId+'::'+'dofilter', false);
		setFormValue(0, sGridId+'::'+'quantity', true);
	}
}


function gridNoSearch(sTrSearchParams, sGridId)
{
	var oForm = document.getElementById('GridForm');
	var oTrSearch = document.getElementById(sTrSearchParams);

	if(oForm != null)
	{
		if(oTrSearch != null)
		{
			var aInputs = oTrSearch.getElementsByTagName('input');
			for(var i = 0; i < aInputs.length; i++)
			{
				var oInput = aInputs[i];
				var sInputName = oInput.name;
				var sInputNameForm = sGridId+'::'+sInputName.substring(7, sInputName.length);
				
				var oInputForm = document.getElementById(sInputNameForm);
				if(oInputForm != null)
				{
					remove(oInputForm, oForm);
				}
			}
			
			var aSelects = oTrSearch.getElementsByTagName('select');
			for(var i = 0; i < aSelects.length; i++)
			{
				var oSelect = aSelects[i];
				var sSelectName = oSelect.name;
				var sSelectNameForm = sGridId+'::'+sSelectName.substring(7, sSelectName.length);
				
				var oSelectForm = document.getElementById(sSelectNameForm);
				if(oSelectForm != null)
				{
					remove(oSelectForm, oForm);
				}
			}
		}
	}
	setFormValue('false', sGridId+'::'+'dofilter', false);
	setFormValue(0, sGridId+'::'+'quantity', true);

}

function remove(oNode, oContainer)
{
	if ( oContainer != null )
	{
		try
		{
			oContainer.removeChild(oNode);
		}
		catch (e)
		{
			try
			{
				document.body.removeChild(oNode);
			}
			catch (e){/**/}
		}
	}
	else
	{
		try
		{
			document.body.removeChild(oNode);
		}
		catch (e){/**/}
	}
}
