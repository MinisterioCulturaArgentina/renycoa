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

class dotDev_Grid_AdapterExt extends dotDev_Grid
{
	var $gridTitle;
	var $gridHeight = 0; // 0 = autoHeight
	var $containerElementId = 'grid-container';
	var $autoExpandColumn;
	var $dataFeedUrl;
	var $groupingActive = false;
	var $groupingField;
	var $removeScroll = false;
	var $dataId;
	var $actions;
	var $ajaxListFilters = false;
	var $selectionCriteria;
	
	//Formatos fecha EXT
	var $DATA_DATEFORMAT;
	var $PRINT_DATEFORMAT; 
	var $QUERY_DATEFORMAT;
	
	
	public function __construct($dbQuery, $fields, $params = null)
	{
		parent::__construct($dbQuery, $fields, $params);
		
		$this->DATA_DATEFORMAT = Zend_Registry::get('config')->grid->dateformat_data;
		$this->PRINT_DATEFORMAT = Zend_Registry::get('config')->grid->dateformat_print;
		$this->QUERY_DATEFORMAT = Zend_Registry::get('config')->grid->dateformat_query;
		
		if (is_array($params))
		{
			if (!isset($params['dataFeedUrl']))
			{
				throw new Exception('Falta param dataFeedUrl');
			}
			else
			{
				$this->dataFeedUrl = $params['dataFeedUrl'];
			}
				

			if (isset($params['gridTitle']))
				$this->gridTitle = $params['gridTitle'];
				
			if (isset($params['gridHeight']))
				$this->gridHeight = $params['gridHeight'];
				
			if (isset($params['containerElementId']))
				$this->containerElementId = $params['containerElementId'];
								
			if (isset($params['autoExpandColumn']))
				$this->autoExpandColumn = $params['autoExpandColumn'];
			else
				$this->autoExpandColumn = $this->fields[0]->fieldId;

			if (isset($params['groupingActive']))
				$this->groupingActive = $params['groupingActive'];
				
		
			if (isset($params['groupingField']))
			{
				$this->groupingField = $params['groupingField'];
				$this->groupingActive = true;
			}
			
			if (!isset($this->orderField) && !isset($dbQuery->initialOrder) && 
				( $this->groupingActive ||  $this->dbQuery instanceof dotDev_Grid_DbQuerySqlServer) 
				)
			{
				$this->orderField = $this->fields[0]->fieldId;
				$this->orderDir = 'ASC';
			}
			
			if (isset($params['removeScroll']))
				$this->removeScroll = $params['removeScroll'];
			
			if (isset($params['actions']))
			{
				$this->actions = $params['actions'];
			}
			
			if (isset($params['dataId']))
			{
				$this->dataId = $params['dataId'];
			}
			
			if (isset($params['ajaxListFilters']))
			{
				$this->ajaxListFilters = $params['ajaxListFilters'];
			}
			
			if (isset($params['selectionCriteria']))
			{
				$this->selectionCriteria = $params['selectionCriteria'];
			}
		}
	}
	
	public function fetchArgsFromRequest($persistSession = false)
	{
		if (isset($_REQUEST['start']))
			$this->startRowIndex = $_REQUEST['start'];
		
		if (isset($_REQUEST['sort']))
			$this->orderField = $_REQUEST['sort'];
			
		if (isset($_REQUEST['dir']))
			$this->orderDir = $_REQUEST['dir'];

		if (isset($_REQUEST['filter']) && count((array)$_REQUEST['filter']))
		{
			$filters = array();
			foreach ((array)$_REQUEST['filter'] as $reqParam)
			{
				$filter = $this->getField($reqParam['field']);
				$filter->value = $reqParam['data']['value'];

				if (isset($reqParam['data']['comparison']))
				{
					if ($reqParam['data']['comparison'] == 'gt')
					{
						$filter->filterType = dotDev_Grid_FilterField::FILTER_GT;
					}
					elseif ($reqParam['data']['comparison'] == 'lt')
					{
						$filter->filterType = dotDev_Grid_FilterField::FILTER_LT;
					}
					elseif ($reqParam['data']['comparison'] == 'eq')
					{
						$filter->filterType = dotDev_Grid_FilterField::FILTER_EQ;
					}
				}
				elseif ($filter->valueType == dotDev_Grid_FilterField::VALUE_STRING)
				{
					$filter->filterType = dotDev_Grid_FilterField::FILTER_LIKE;
				}
				elseif ($filter->valueType == dotDev_Grid_FilterField::VALUE_LIST)
				{
					//Reset $filter
					$filter = $filter->applyFilterOn;
					$filter->value = $reqParam['data']['value'];
					$filter->filterType = dotDev_Grid_FilterField::FILTER_IN;
				}
				elseif ($filter->valueType == dotDev_Grid_FilterField::VALUE_BOOLEAN)
				{
					$filter->filterType = dotDev_Grid_FilterField::FILTER_EQ;
				}

				array_push($filters, $filter);
			}
			
			$this->dbQuery->addFilterArg(new dotDev_Grid_FilterGroup($filters, dotDev_Grid_FilterGroup::OPERATOR_AND));
		}
		if ($persistSession)
		{
			$session = new Zend_Session_Namespace('grids');
	    $session->{$this->id} = $this;
		}
	}
	
	
	public function getGridScript()
	{
		
		$dataStoreFieldsString = $this->processFields('getDataStoreStringFromField');
		foreach ((array)$this->actions as $action)
		{
			if ($action->hideable)
			{
				if ($dataStoreFieldsString != '')
					$dataStoreFieldsString .= ',';
					
				$dataStoreFieldsString .=" 
				 	{name:'hide_$action->actionId'}";
				 	
			}	
		}
		
		$dataStoreActionsString = "";
		if (count((array)$this->actions))
		{
			$dataStoreActionsString =",
					{name:'actions'}";
		}
		$dataStoreSortInfo = isset($this->orderField) ? "sortInfo: {field: '$this->orderField', direction: '$this->orderDir'}," : '';
		$dataStoreGroupingField = isset($this->groupingField) ? "groupField: '$this->groupingField'," : '';
		
		$dataStoreDeclaration = "var ds = new Ext.data.JsonStore(dsSetup);"; //non grouping DS
		if ($this->groupingActive)
		{
			$dataStoreDeclaration = "
			var ds = new Ext.data.GroupingStore({
				reader:new Ext.data.JsonReader(dsSetup),
				proxy:new Ext.data.HttpProxy({url:dsSetup.url}),
				sortInfo: dsSetup.sortInfo,
				groupField: dsSetup.groupField,
				remoteSort: dsSetup.remoteSort
			});";
		}

			
		$filtersString = $this->processFields('getFilterStringFromField');
		
		$actionsString = '';
		foreach ((array)$this->actions as $action)
		{
			if ($actionsString != '')
				$actionsString .= ',';
				
			$actionsString .= $action->getDefintionScript();
		}
		$actionsColumnStyle = '';
		if (count((array)$this->actions))
		{
			$actionsColumnStyle = "
			//Centrar text columna acciones
			$('.x-grid3-hd-inner:last').css('text-align','center');
			";
		}
		
		$columnModelString = $this->processFields('getColumnModelStringFromField');
		$columnModelActionString = "";
		if (count((array)$this->actions))
		{
			$columnModelActionString =",
				actions";
		}
		
		$groupingView = '';
		if ($this->groupingActive)
		{
			$groupingView = "
				/* grouping */
				view: new Ext.grid.GroupingView({
					forceFit:true,
					groupTextTpl:'{text} ({[values.rs.length]} {[values.rs.length > 1 ? ".'"Items" : "Item"'."]})'";
			if ($this->removeScroll)
			{
				$groupingView .=",
					scrollOffset: 2";
			}
			$groupingView.="
				}),";
		}
		
		$gridHeight = "autoHeight:true,";
		if ($this->gridHeight > 0)
		{
			$gridHeight = "height: $this->gridHeight,"; 
		}
		
		$tbar = "";
		$tbarXls = "";
		$tbarPrint = "";
		$exportXls= "";
		$print="";
		if($this->exportXlsUrl || $this->printUrl)
		{
			if($this->exportXlsUrl)
			{
				$tbarXls = "{
		            	text:'Exportar',
		            	tooltip:'Exportar a un archivo CSV',
		            	iconCls:'export-xls',
		            	handler: exportXls
		        	}";
				
				$exportXls = "
					function exportXls()
					{
						window.location = '".$this->exportXlsUrl."';
					}";
			}
			if($this->printUrl)
			{
				$tbarPrint = "{
		            	text:'Imprimir',
		            	tooltip:'Imprimir',
		            	iconCls:'print',
		            	handler: print
	        		}";
				
				$print = "
					function print()
					{
						window.open('$this->printUrl','','height=720,width=960');
					}";
			}
			
			$tbar = ",
				tbar:[".
				( $this->exportXlsUrl ? $tbarXls : '').
				(($this->exportXlsUrl && $this->printUrl) ? ',' : '').
				( $this->printUrl ? $tbarPrint : '').
				"]";
        }
        

		$scroll = "";
		if ($this->removeScroll)
		{
			$scroll =",
				viewConfig: { //Remove vertical scrollbar space
					scrollOffset: 2
				}";
		}
		$baseUrl = Zend_Registry::get('config')->base_url;
			
		$rowSelection = "";
		if ($this->selectionCriteria)
		{
			if (is_array($this->selectionCriteria))
			{
				$rowSelection ="
        grid.getView().getRowClass = function(record, index){
        ";
				    $firstCriteria = true;
				    foreach ($this->selectionCriteria as $criteria=>$class)
				    {
				    	if (!$firstCriteria)
				    	{
				    		$rowSelection .="
				    		else ";
				    	}
				    	$rowSelection .=" if ($criteria)
				    	{
				    	 return '$class';
				    	}
				    	";
				    	$firstCriteria = false;
				    } 
          $rowSelection .="};";
			}
			else
			{
				$rowSelection ="
				grid.getView().getRowClass = function(record, index){ 
		      		return ({$this->selectionCriteria} ? 'selected-row' : '');
		    	};";
			}
		}
		
		$script =<<<SCRIPT
		
		/* State Fix */
		Ext.override(Ext.Component, {
		    saveState : function(){
		        if(Ext.state.Manager && this.stateful !== false){
		            var state = this.getState();
		            if(this.fireEvent('beforestatesave', this, state) !== false){
		                Ext.state.Manager.set(this.stateId || this.id, state);
		                this.fireEvent('statesave', this, state);	
		            }
		        }
		    },
		    stateful : false
		}); 
				
		/* Grid Setup */
		Ext.onReady(function(){
			
			/* STATE PROVIDER (Tiene un bug) 
			Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
			/**/
			
			/* DATA SETUP */
			var dsSetup = {
				url:'{$this->dataFeedUrl}',
				id: '{$this->dataId}',
				totalProperty: 'total'
			
			,
				root: 'data',
				fields: [
					{$dataStoreFieldsString}
					{$dataStoreActionsString}
				],
				{$dataStoreSortInfo}
				{$dataStoreGroupingField}
				remoteSort: true
			};
			
			/* DATA STORE */
			{$dataStoreDeclaration}

			/* GRID FILTERING */
			
			Ext.menu.RangeMenu.prototype.icons = {
				gt: '{$baseUrl}scripts/ext-grid-filtering/img/greater_then.png', 
				lt: '{$baseUrl}scripts/ext-grid-filtering/img/less_then.png',
				eq: '{$baseUrl}scripts/ext-grid-filtering/img/equals.png'
			};
			Ext.grid.filter.StringFilter.prototype.icon = '{$baseUrl}scripts/ext-grid-filtering/img/find.png';
			
			var filters = new Ext.grid.GridFilters({
				filtersText: 'Filtros',
				filters:[
					{$filtersString}		
				]
			});
			
			
			/* ROW ACTIONS */
			Ext.QuickTips.init();
		
		    var actions = new Ext.ux.grid.RowActions({
				header:'&#8227',
				actions:[
				 	{$actionsString}
				]
				/* grouping 
				,groupActions:[
					{
						iconCls:'icon-del-table',
						qtip:'Remove Table'
					},
					{
						iconCls:'icon-add-table',
						qtip:'Add Table'
					},
					{
						iconCls:'icon-graph',
						qtip:'View Graph',
						align:'left',
						callback:function(grid, records, action, groupId) {
							alert ("aaaEvent: groupaction, Group: "+groupId+", action: "+action+", records: "+records.length );
						}
					}
				]
				*/
			});
		
			/* COLUMN MODEL */
			var cm = new Ext.grid.ColumnModel([
				{$columnModelString}
				{$columnModelActionString}
			]);
			cm.defaultSortable = true;
			
			/* INITIALIZATION */

			var grid = new Ext.grid.GridPanel({
				id: '{$this->id}',
				title: '{$this->gridTitle}',
				iconCls: 'icon-grid',
				ds: ds,
				cm: cm,
				enableColLock: false,
				loadMask: true,
				plugins: [filters, actions],
				{$groupingView}
				{$gridHeight}
				el: 'grid-container',
				autoExpandColumn: '{$this->autoExpandColumn}',
				viewConfig:{
					forceFit:true
				},
				disableSelection: true,
				bbar: new Ext.PagingToolbar({
					store: ds,
					pageSize: {$this->pageSize},
					plugins: filters
				})
				{$tbar}
				{$scroll}
			});
			grid.render();
			
			{$rowSelection}
	    	
			ds.load({params:{start: 0, limit: {$this->pageSize}}});
			
			{$actionsColumnStyle}
		});
				

		function gridDateRenderer(v)
		{
			if (v)
			{
				var str = v.replace(/^\s+|\s+$/g,'').replace(/\s+/g,' ');
				if (Date.isValid(str,'{$this->DATA_DATEFORMAT}'))
				{
				  var d = Date.parseString(str,'{$this->DATA_DATEFORMAT}');
					return d.format('{$this->PRINT_DATEFORMAT}');
				}
			}
			return v;
		} 
		
		function gridBooleanRenderer(v)
		{
			if (v == 1)
			{
				return 'Si';
			}
			else
			{
				return 'No';
			}
		}
		
		function gridCurrencyRenderer(v) 
		{
		    v = (Math.round((v-0)*100))/100;
		    v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
		    v = String(v);
		    var ps = v.split('.');
		    var whole = ps[0];
		    var sub = ps[1] ? ','+ ps[1] : ',00';
		    var r = /(\d+)(\d{3})/;
		    while (r.test(whole)) {
		        whole = whole.replace(r, '$1' + '.' + '$2');
		    }
		    v = whole + sub;
		    /*
		    if(v.charAt(0) == '-'){
		        return '-$' + v.substr(1);
		    }
		    return "$" +  v;
		    */
		    return v;
		}
		
		{$exportXls}
		{$print}
SCRIPT;

		return $script;
	}
	
	private function processFields($function)
	{
		$res = '';
		if (count((array)$this->fields))
		{
			foreach ((array)$this->fields as $arg)
			{
				if ($res != '')
					$res .= ",";
					
				if ($arg instanceof dotDev_Grid_FilterGroup)
				{
					$res .= $this->processFields($arg->filterParams, $function);
				}
				elseif ($arg instanceof dotDev_Grid_FilterField)
				{
					$res .= $this->$function($arg);
				}
			}
		}
		return $res;
	}
	
	
	private function getDataStoreStringFromField($field)
	{
		return	" 
				 	{name:'$field->fieldId'}";
				//(($field->valueType == dotDev_Grid_FilterField::VALUE_DATE)?", type: 'date', dateFormat: '".$this->DATA_DATEFORMAT."'}":"}");
				//deshabilitado por bug? en Ext para el parseo de fechas del tipo Nov 16 2008 12:00AM
	}
	
	
	private function getFilterStringFromField($field)
	{
		$res = "
				 	{type: '$field->valueType', dataIndex: '$field->fieldId'";
		
		switch ($field->valueType)
		{
			case dotDev_Grid_FilterField::VALUE_LIST:
				
				if ($this->ajaxListFilters)
				{
					$res .=<<<RES
,  
					 		store: new Ext.data.JsonStore({
								url:'{$this->dataFeedUrl}',
								baseParams:{get_list_{$field->fieldId}: "true"},
								id: 'id',
								totalProperty: 'total',
								root: 'data',
								fields: [
									{name:'value'},
									{name:'text'}
								]
							}), 
					 		phpMode: true
RES;
				}
				else
				{
					$res .=<<<RES
,  
					 		options: {$field->listFilterOptions}, 
					 		phpMode: true
RES;
					
				}
				break;
				
			case dotDev_Grid_FilterField::VALUE_DATE:
				$res .= ", beforeText: 'anterior a', afterText: 'posterior a', onText:'fecha exacta', dateFormat: '".$this->QUERY_DATEFORMAT."'";
				break;
			
			case dotDev_Grid_FilterField::VALUE_BOOLEAN:
				$res .= ", yesText: 'si', noText: 'No'";
				break;
		}
		
		$res .= "}";
		
		return $res;
	}
		
	
	private function getColumnModelStringFromField($field)
	{
		$res =	"
				{dataIndex: '$field->fieldId', header: '$field->label', id: '$field->fieldId' ";
		
			
		if ($field->hidden == 'true')
		{
			$res .= ", hidden: true";
		}
		
		if ($field->width)
		{
			$res .= ", width: ".$field->width;
		}

		if ($field->renderer)
		{
			$res .= ", renderer: {$field->renderer}}";
		}
		else
		{
			switch ($field->valueType)
			{
				case dotDev_Grid_FilterField::VALUE_DATE:
					$res .= ", renderer: gridDateRenderer}";
					break;
					
				case dotDev_Grid_FilterField::VALUE_BOOLEAN:
					$res .= ", renderer: gridBooleanRenderer}";
					break;
					
				default:
					$res .= "}";
					break;
			}
		}
		
		return $res;
	}
	
}