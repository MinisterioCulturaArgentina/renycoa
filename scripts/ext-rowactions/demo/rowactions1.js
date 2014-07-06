// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.grid.RowActions Plugin dotDev Application
 *
 * @author    Ing. Jozef Sakáloš
 * @date      22. March 2008
 * @version   $Id: rowactions1.js,v 1.1 2009-06-09 03:04:31 root Exp $
 *
 * @license rowactions.js is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */



// application entry point
Ext.onReady(function() {
    Ext.QuickTips.init();

    var actions = new Ext.ux.grid.RowActions({
		 header:'Actions'
		,actions:[{
			iconCls:'icon-open',
			tooltip:'Open',
			callback:function(grid, record, action, row, col) {
				alert("click! row: "+row+" / action: "+action);
			}
		},{
			iconCls:'icon-disk',
			tooltip:'Configure',
			hideIndex:'hide2',
			callback:function(grid, record, action, row, col) {
				alert("click! row: "+row+" / action: "+action);
			}
		},{
			iconCls:'icon-cross',
			tooltip:'Tooltip act3',
			callback:function(grid, record, action, row, col) {
				alert("click! row: "+row+" / action: "+action);
			}
		}]
		/* GROUPING 
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
		/**/
	});

	
	var dsSetup = {
		url:'get-grid-data.php',
		id: 'company',
		totalProperty: 'totalCount',
		root: 'rows',
		fields: [
			{name: 'company'}
		   ,{name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'}
		   ,{name: 'industry'}
		   ,{name: 'hide2', type: 'boolean'}
		],
		sortInfo:{field:'company', direction:'ASC'},
		remoteSort: false,
		groupField: 'industry'
	};
	
	/* NON GROUPING 
	var ds = new Ext.data.JsonStore(dsSetup);
	/**/
	/* GROUPING */
	var ds = new Ext.data.GroupingStore({
		reader:new Ext.data.JsonReader(dsSetup),
		proxy:new Ext.data.HttpProxy({url:dsSetup.url}),
		sortInfo: dsSetup.sortInfo,
		groupField: dsSetup.groupField,
		remoteSort: dsSetup.remoteSort
	})

	var cm = new Ext.grid.ColumnModel([
		{id:'company',header: "Company", width: 40, sortable: true, dataIndex: 'company'},
		{header: "Industry", width: 20, sortable: true, dataIndex: 'industry'},
		{header: "Last Updated", width: 20, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'},
		actions
	]);
	

	var grid = new Ext.grid.GridPanel({
		id: 'example',
		title: 'Grilla Loca Loca',
		iconCls: 'icon-grid',
		ds: ds,
		cm: cm,
		enableColLock: false,
		loadMask: true,
		plugins: actions,
		/* GROUPING */
		view: new Ext.grid.GroupingView({
			forceFit:true,
			groupTextTpl:'{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
		}),
		/**/
		height:400,		
		el: 'grid-container',
		bbar: new Ext.PagingToolbar({
			store: ds,
			pageSize: 15
		}),
		viewConfig: { //Remove vertical scrollbar space
			scrollOffset: 2
		}
	});
	grid.render();
	
	ds.load({params:{start: 0, limit: 10}});
});

