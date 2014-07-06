/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.Store=function(A){this.data=new Ext.util.MixedCollection(false);this.data.getKey=function(B){return B.id};this.baseParams={};this.paramNames={"start":"start","limit":"limit","sort":"sort","dir":"dir"};if(A&&A.data){this.inlineData=A.data;delete A.data}Ext.apply(this,A);if(this.url&&!this.proxy){this.proxy=new Ext.data.HttpProxy({url:this.url})}if(this.reader){if(!this.recordType){this.recordType=this.reader.recordType}if(this.reader.onMetaChange){this.reader.onMetaChange=this.onMetaChange.createDelegate(this)}}if(this.recordType){this.fields=this.recordType.prototype.fields}this.modified=[];this.addEvents("datachanged","metachange","add","remove","update","clear","beforeload","load","loadexception");if(this.proxy){this.relayEvents(this.proxy,["loadexception"])}this.sortToggle={};if(this.sortInfo){this.setDefaultSort(this.sortInfo.field,this.sortInfo.direction)}Ext.data.Store.superclass.constructor.call(this);if(this.storeId||this.id){Ext.StoreMgr.register(this)}if(this.inlineData){this.loadData(this.inlineData);delete this.inlineData}else{if(this.autoLoad){this.load.defer(10,this,[typeof this.autoLoad=="object"?this.autoLoad:undefined])}}};Ext.extend(Ext.data.Store,Ext.util.Observable,{remoteSort:false,pruneModifiedRecords:false,lastOptions:null,destroy:function(){if(this.id){Ext.StoreMgr.unregister(this)}this.data=null;this.purgeListeners()},add:function(B){B=[].concat(B);if(B.length<1){return }for(var D=0,A=B.length;D<A;D++){B[D].join(this)}var C=this.data.length;this.data.addAll(B);if(this.snapshot){this.snapshot.addAll(B)}this.fireEvent("add",this,B,C)},addSorted:function(A){var B=this.findInsertIndex(A);this.insert(B,A)},remove:function(A){var B=this.data.indexOf(A);this.data.removeAt(B);if(this.pruneModifiedRecords){this.modified.remove(A)}if(this.snapshot){this.snapshot.remove(A)}this.fireEvent("remove",this,A,B)},removeAll:function(){this.data.clear();if(this.snapshot){this.snapshot.clear()}if(this.pruneModifiedRecords){this.modified=[]}this.fireEvent("clear",this)},insert:function(C,B){B=[].concat(B);for(var D=0,A=B.length;D<A;D++){this.data.insert(C,B[D]);B[D].join(this)}this.fireEvent("add",this,B,C)},indexOf:function(A){return this.data.indexOf(A)},indexOfId:function(A){return this.data.indexOfKey(A)},getById:function(A){return this.data.key(A)},getAt:function(A){return this.data.itemAt(A)},getRange:function(B,A){return this.data.getRange(B,A)},storeOptions:function(A){A=Ext.apply({},A);delete A.callback;delete A.scope;this.lastOptions=A},load:function(B){B=B||{};if(this.fireEvent("beforeload",this,B)!==false){this.storeOptions(B);var C=Ext.apply(B.params||{},this.baseParams);if(this.sortInfo&&this.remoteSort){var A=this.paramNames;C[A["sort"]]=this.sortInfo.field;C[A["dir"]]=this.sortInfo.direction}this.proxy.load(C,this.reader,this.loadRecords,this,B);return true}else{return false}},reload:function(A){this.load(Ext.applyIf(A||{},this.lastOptions))},loadRecords:function(G,B,F){if(!G||F===false){if(F!==false){this.fireEvent("load",this,[],B)}if(B.callback){B.callback.call(B.scope||this,[],B,false)}return }var E=G.records,D=G.totalRecords||E.length;if(!B||B.add!==true){if(this.pruneModifiedRecords){this.modified=[]}for(var C=0,A=E.length;C<A;C++){E[C].join(this)}if(this.snapshot){this.data=this.snapshot;delete this.snapshot}this.data.clear();this.data.addAll(E);this.totalLength=D;this.applySort();this.fireEvent("datachanged",this)}else{this.totalLength=Math.max(D,this.data.length+E.length);this.add(E)}this.fireEvent("load",this,E,B);if(B.callback){B.callback.call(B.scope||this,E,B,true)}},loadData:function(C,A){var B=this.reader.readRecords(C);this.loadRecords(B,{add:A},true)},getCount:function(){return this.data.length||0},getTotalCount:function(){return this.totalLength||0},getSortState:function(){return this.sortInfo},applySort:function(){if(this.sortInfo&&!this.remoteSort){var A=this.sortInfo,B=A.field;this.sortData(B,A.direction)}},sortData:function(C,D){D=D||"ASC";var A=this.fields.get(C).sortType;var B=function(F,E){var H=A(F.data[C]),G=A(E.data[C]);return H>G?1:(H<G?-1:0)};this.data.sort(D,B);if(this.snapshot&&this.snapshot!=this.data){this.snapshot.sort(D,B)}},setDefaultSort:function(B,A){A=A?A.toUpperCase():"ASC";this.sortInfo={field:B,direction:A};this.sortToggle[B]=A},sort:function(E,C){var D=this.fields.get(E);if(!D){return false}if(!C){if(this.sortInfo&&this.sortInfo.field==D.name){C=(this.sortToggle[D.name]||"ASC").toggle("ASC","DESC")}else{C=D.sortDir}}var B=(this.sortToggle)?this.sortToggle[D.name]:null;var A=(this.sortInfo)?this.sortInfo:null;this.sortToggle[D.name]=C;this.sortInfo={field:D.name,direction:C};if(!this.remoteSort){this.applySort();this.fireEvent("datachanged",this)}else{if(!this.load(this.lastOptions)){if(B){this.sortToggle[D.name]=B}if(A){this.sortInfo=A}}}},each:function(B,A){this.data.each(B,A)},getModifiedRecords:function(){return this.modified},createFilterFn:function(C,B,D,A){if(Ext.isEmpty(B,false)){return false}B=this.data.createValueMatcher(B,D,A);return function(E){return B.test(E.data[C])}},sum:function(E,F,A){var C=this.data.items,B=0;F=F||0;A=(A||A===0)?A:C.length-1;for(var D=F;D<=A;D++){B+=(C[D].data[E]||0)}return B},filter:function(D,C,E,A){var B=this.createFilterFn(D,C,E,A);return B?this.filterBy(B):this.clearFilter()},filterBy:function(B,A){this.snapshot=this.snapshot||this.data;this.data=this.queryBy(B,A||this);this.fireEvent("datachanged",this)},query:function(D,C,E,A){var B=this.createFilterFn(D,C,E,A);return B?this.queryBy(B):this.data.clone()},queryBy:function(B,A){var C=this.snapshot||this.data;return C.filterBy(B,A||this)},find:function(D,C,F,E,A){var B=this.createFilterFn(D,C,E,A);return B?this.data.findIndexBy(B,null,F):-1},findBy:function(B,A,C){return this.data.findIndexBy(B,A,C)},collect:function(G,H,B){var F=(B===true&&this.snapshot)?this.snapshot.items:this.data.items;var I,J,A=[],C={};for(var D=0,E=F.length;D<E;D++){I=F[D].data[G];J=String(I);if((H||!Ext.isEmpty(I))&&!C[J]){C[J]=true;A[A.length]=I}}return A},clearFilter:function(A){if(this.isFiltered()){this.data=this.snapshot;delete this.snapshot;if(A!==true){this.fireEvent("datachanged",this)}}},isFiltered:function(){return this.snapshot&&this.snapshot!=this.data},afterEdit:function(A){if(this.modified.indexOf(A)==-1){this.modified.push(A)}this.fireEvent("update",this,A,Ext.data.Record.EDIT)},afterReject:function(A){this.modified.remove(A);this.fireEvent("update",this,A,Ext.data.Record.REJECT)},afterCommit:function(A){this.modified.remove(A);this.fireEvent("update",this,A,Ext.data.Record.COMMIT)},commitChanges:function(){var B=this.modified.slice(0);this.modified=[];for(var C=0,A=B.length;C<A;C++){B[C].commit()}},rejectChanges:function(){var B=this.modified.slice(0);this.modified=[];for(var C=0,A=B.length;C<A;C++){B[C].reject()}},onMetaChange:function(B,A,C){this.recordType=A;this.fields=A.prototype.fields;delete this.snapshot;this.sortInfo=B.sortInfo;this.modified=[];this.fireEvent("metachange",this,this.reader.meta)},findInsertIndex:function(A){this.suspendEvents();var C=this.data.clone();this.data.add(A);this.applySort();var B=this.data.indexOf(A);this.data=C;this.resumeEvents();return B}});