/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.HtmlEditor=Ext.extend(Ext.form.Field,{enableFormat:true,enableFontSize:true,enableColors:true,enableAlignments:true,enableLists:true,enableSourceEdit:true,enableLinks:true,enableFont:true,createLinkText:"Please enter the URL for the link:",defaultLinkValue:"http:/"+"/",fontFamilies:["Arial","Courier New","Tahoma","Times New Roman","Verdana"],defaultFont:"tahoma",validationEvent:false,deferHeight:true,initialized:false,activated:false,sourceEditMode:false,onFocus:Ext.emptyFn,iframePad:3,hideMode:"offsets",defaultAutoCreate:{tag:"textarea",style:"width:500px;height:300px;",autocomplete:"off"},initComponent:function(){this.addEvents("initialize","activate","beforesync","beforepush","sync","push","editmodechange")},createFontOptions:function(){var D=[],B=this.fontFamilies,C,F;for(var E=0,A=B.length;E<A;E++){C=B[E];F=C.toLowerCase();D.push("<option value=\"",F,"\" style=\"font-family:",C,";\"",(this.defaultFont==F?" selected=\"true\">":">"),C,"</option>")}return D.join("")},createToolbar:function(D){var A=Ext.QuickTips&&Ext.QuickTips.isEnabled();function C(G,E,F){return{itemId:G,cls:"x-btn-icon x-edit-"+G,enableToggle:E!==false,scope:D,handler:F||D.relayBtnCmd,clickEvent:"mousedown",tooltip:A?D.buttonTips[G]||undefined:undefined,tabIndex:-1}}var B=new Ext.Toolbar({renderTo:this.wrap.dom.firstChild});B.el.on("click",function(E){E.preventDefault()});if(this.enableFont&&!Ext.isSafari2){this.fontSelect=B.el.createChild({tag:"select",cls:"x-font-select",html:this.createFontOptions()});this.fontSelect.on("change",function(){var E=this.fontSelect.dom.value;this.relayCmd("fontname",E);this.deferFocus()},this);B.add(this.fontSelect.dom,"-")}if(this.enableFormat){B.add(C("bold"),C("italic"),C("underline"))}if(this.enableFontSize){B.add("-",C("increasefontsize",false,this.adjustFont),C("decreasefontsize",false,this.adjustFont))}if(this.enableColors){B.add("-",{itemId:"forecolor",cls:"x-btn-icon x-edit-forecolor",clickEvent:"mousedown",tooltip:A?D.buttonTips["forecolor"]||undefined:undefined,tabIndex:-1,menu:new Ext.menu.ColorMenu({allowReselect:true,focus:Ext.emptyFn,value:"000000",plain:true,selectHandler:function(F,E){this.execCmd("forecolor",Ext.isSafari||Ext.isIE?"#"+E:E);this.deferFocus()},scope:this,clickEvent:"mousedown"})},{itemId:"backcolor",cls:"x-btn-icon x-edit-backcolor",clickEvent:"mousedown",tooltip:A?D.buttonTips["backcolor"]||undefined:undefined,tabIndex:-1,menu:new Ext.menu.ColorMenu({focus:Ext.emptyFn,value:"FFFFFF",plain:true,allowReselect:true,selectHandler:function(F,E){if(Ext.isGecko){this.execCmd("useCSS",false);this.execCmd("hilitecolor",E);this.execCmd("useCSS",true);this.deferFocus()}else{this.execCmd(Ext.isOpera?"hilitecolor":"backcolor",Ext.isSafari||Ext.isIE?"#"+E:E);this.deferFocus()}},scope:this,clickEvent:"mousedown"})})}if(this.enableAlignments){B.add("-",C("justifyleft"),C("justifycenter"),C("justifyright"))}if(!Ext.isSafari2){if(this.enableLinks){B.add("-",C("createlink",false,this.createLink))}if(this.enableLists){B.add("-",C("insertorderedlist"),C("insertunorderedlist"))}if(this.enableSourceEdit){B.add("-",C("sourceedit",true,function(E){this.toggleSourceEdit(E.pressed)}))}}this.tb=B},getDocMarkup:function(){return"<html><head><style type=\"text/css\">body{border:0;margin:0;padding:3px;height:98%;cursor:text;}</style></head><body></body></html>"},getEditorBody:function(){return this.doc.body||this.doc.documentElement},getDoc:function(){return Ext.isIE?this.getWin().document:(this.iframe.contentDocument||this.getWin().document)},getWin:function(){return Ext.isIE?this.iframe.contentWindow:window.frames[this.iframe.name]},onRender:function(B,A){Ext.form.HtmlEditor.superclass.onRender.call(this,B,A);this.el.dom.style.border="0 none";this.el.dom.setAttribute("tabIndex",-1);this.el.addClass("x-hidden");if(Ext.isIE){this.el.applyStyles("margin-top:-1px;margin-bottom:-1px;")}this.wrap=this.el.wrap({cls:"x-html-editor-wrap",cn:{cls:"x-html-editor-tb"}});this.createToolbar(this);this.tb.items.each(function(E){if(E.itemId!="sourceedit"){E.disable()}});var C=document.createElement("iframe");C.name=Ext.id();C.frameBorder="0";C.src=Ext.isIE?Ext.SSL_SECURE_URL:"javascript:;";this.wrap.dom.appendChild(C);this.iframe=C;this.initFrame();if(this.autoMonitorDesignMode!==false){this.monitorTask=Ext.TaskMgr.start({run:this.checkDesignMode,scope:this,interval:100})}if(!this.width){var D=this.el.getSize();this.setSize(D.width,this.height||D.height)}},initFrame:function(){this.doc=this.getDoc();this.win=this.getWin();this.doc.open();this.doc.write(this.getDocMarkup());this.doc.close();var A={run:function(){if(this.doc.body||this.doc.readyState=="complete"){Ext.TaskMgr.stop(A);this.doc.designMode="on";this.initEditor.defer(10,this)}},interval:10,duration:10000,scope:this};Ext.TaskMgr.start(A)},checkDesignMode:function(){if(this.wrap&&this.wrap.dom.offsetWidth){var A=this.getDoc();if(!A){return }if(!A.editorInitialized||String(A.designMode).toLowerCase()!="on"){this.initFrame()}}},onResize:function(B,C){Ext.form.HtmlEditor.superclass.onResize.apply(this,arguments);if(this.el&&this.iframe){if(typeof B=="number"){var D=B-this.wrap.getFrameWidth("lr");this.el.setWidth(this.adjustWidth("textarea",D));this.iframe.style.width=D+"px"}if(typeof C=="number"){var A=C-this.wrap.getFrameWidth("tb")-this.tb.el.getHeight();this.el.setHeight(this.adjustWidth("textarea",A));this.iframe.style.height=A+"px";if(this.doc){this.getEditorBody().style.height=(A-(this.iframePad*2))+"px"}}}},toggleSourceEdit:function(A){if(A===undefined){A=!this.sourceEditMode}this.sourceEditMode=A===true;var C=this.tb.items.get("sourceedit");if(C.pressed!==this.sourceEditMode){C.toggle(this.sourceEditMode);return }if(this.sourceEditMode){this.tb.items.each(function(D){if(D.itemId!="sourceedit"){D.disable()}});this.syncValue();this.iframe.className="x-hidden";this.el.removeClass("x-hidden");this.el.dom.removeAttribute("tabIndex");this.el.focus()}else{if(this.initialized){this.tb.items.each(function(D){D.enable()})}this.pushValue();this.iframe.className="";this.el.addClass("x-hidden");this.el.dom.setAttribute("tabIndex",-1);this.deferFocus()}var B=this.lastSize;if(B){delete this.lastSize;this.setSize(B)}this.fireEvent("editmodechange",this,this.sourceEditMode)},createLink:function(){var A=prompt(this.createLinkText,this.defaultLinkValue);if(A&&A!="http:/"+"/"){this.relayCmd("createlink",A)}},adjustSize:Ext.BoxComponent.prototype.adjustSize,getResizeEl:function(){return this.wrap},getPositionEl:function(){return this.wrap},initEvents:function(){this.originalValue=this.getValue()},markInvalid:Ext.emptyFn,clearInvalid:Ext.emptyFn,setValue:function(A){Ext.form.HtmlEditor.superclass.setValue.call(this,A);this.pushValue()},cleanHtml:function(A){A=String(A);if(A.length>5){if(Ext.isSafari){A=A.replace(/\sclass="(?:Apple-style-span|khtml-block-placeholder)"/gi,"")}}if(A=="&nbsp;"){A=""}return A},syncValue:function(){if(this.initialized){var D=this.getEditorBody();var C=D.innerHTML;if(Ext.isSafari){var B=D.getAttribute("style");var A=B.match(/text-align:(.*?);/i);if(A&&A[1]){C="<div style=\""+A[0]+"\">"+C+"</div>"}}C=this.cleanHtml(C);if(this.fireEvent("beforesync",this,C)!==false){this.el.dom.value=C;this.fireEvent("sync",this,C)}}},pushValue:function(){if(this.initialized){var A=this.el.dom.value;if(!this.activated&&A.length<1){A="&nbsp;"}if(this.fireEvent("beforepush",this,A)!==false){this.getEditorBody().innerHTML=A;this.fireEvent("push",this,A)}}},deferFocus:function(){this.focus.defer(10,this)},focus:function(){if(this.win&&!this.sourceEditMode){this.win.focus()}else{this.el.focus()}},initEditor:function(){var B=this.getEditorBody();var A=this.el.getStyles("font-size","font-family","background-image","background-repeat");A["background-attachment"]="fixed";B.bgProperties="fixed";Ext.DomHelper.applyStyles(B,A);if(this.doc){try{Ext.EventManager.removeAll(this.doc)}catch(C){}}this.doc=this.getDoc();Ext.EventManager.on(this.doc,{"mousedown":this.onEditorEvent,"dblclick":this.onEditorEvent,"click":this.onEditorEvent,"keyup":this.onEditorEvent,buffer:100,scope:this});if(Ext.isGecko){Ext.EventManager.on(this.doc,"keypress",this.applyCommand,this)}if(Ext.isIE||Ext.isSafari||Ext.isOpera){Ext.EventManager.on(this.doc,"keydown",this.fixKeys,this)}this.initialized=true;this.fireEvent("initialize",this);this.doc.editorInitialized=true;this.pushValue()},onDestroy:function(){if(this.monitorTask){Ext.TaskMgr.stop(this.monitorTask)}if(this.rendered){this.tb.items.each(function(A){if(A.menu){A.menu.removeAll();if(A.menu.el){A.menu.el.destroy()}}A.destroy()});this.wrap.dom.innerHTML="";this.wrap.remove()}},onFirstFocus:function(){this.activated=true;this.tb.items.each(function(D){D.enable()});if(Ext.isGecko){this.win.focus();var A=this.win.getSelection();if(!A.focusNode||A.focusNode.nodeType!=3){var B=A.getRangeAt(0);B.selectNodeContents(this.getEditorBody());B.collapse(true);this.deferFocus()}try{this.execCmd("useCSS",true);this.execCmd("styleWithCSS",false)}catch(C){}}this.fireEvent("activate",this)},adjustFont:function(B){var C=B.itemId=="increasefontsize"?1:-1;var A=parseInt(this.doc.queryCommandValue("FontSize")||2,10);if(Ext.isSafari3||Ext.isAir){if(A<=10){A=1+C}else{if(A<=13){A=2+C}else{if(A<=16){A=3+C}else{if(A<=18){A=4+C}else{if(A<=24){A=5+C}else{A=6+C}}}}}A=A.constrain(1,6)}else{if(Ext.isSafari){C*=2}A=Math.max(1,A+C)+(Ext.isSafari?"px":0)}this.execCmd("FontSize",A)},onEditorEvent:function(A){this.updateToolbar()},updateToolbar:function(){if(!this.activated){this.onFirstFocus();return }var B=this.tb.items.map,C=this.doc;if(this.enableFont&&!Ext.isSafari2){var A=(this.doc.queryCommandValue("FontName")||this.defaultFont).toLowerCase();if(A!=this.fontSelect.dom.value){this.fontSelect.dom.value=A}}if(this.enableFormat){B.bold.toggle(C.queryCommandState("bold"));B.italic.toggle(C.queryCommandState("italic"));B.underline.toggle(C.queryCommandState("underline"))}if(this.enableAlignments){B.justifyleft.toggle(C.queryCommandState("justifyleft"));B.justifycenter.toggle(C.queryCommandState("justifycenter"));B.justifyright.toggle(C.queryCommandState("justifyright"))}if(!Ext.isSafari2&&this.enableLists){B.insertorderedlist.toggle(C.queryCommandState("insertorderedlist"));B.insertunorderedlist.toggle(C.queryCommandState("insertunorderedlist"))}Ext.menu.MenuMgr.hideAll();this.syncValue()},relayBtnCmd:function(A){this.relayCmd(A.itemId)},relayCmd:function(B,A){(function(){this.focus();this.execCmd(B,A);this.updateToolbar()}).defer(10,this)},execCmd:function(B,A){this.doc.execCommand(B,false,A===undefined?null:A);this.syncValue()},applyCommand:function(B){if(B.ctrlKey){var C=B.getCharCode(),A;if(C>0){C=String.fromCharCode(C);switch(C){case"b":A="bold";break;case"i":A="italic";break;case"u":A="underline";break}if(A){this.win.focus();this.execCmd(A);this.deferFocus();B.preventDefault()}}}},insertAtCursor:function(B){if(!this.activated){return }if(Ext.isIE){this.win.focus();var A=this.doc.selection.createRange();if(A){A.collapse(true);A.pasteHTML(B);this.syncValue();this.deferFocus()}}else{if(Ext.isGecko||Ext.isOpera){this.win.focus();this.execCmd("InsertHTML",B);this.deferFocus()}else{if(Ext.isSafari){this.execCmd("InsertText",B);this.deferFocus()}}}},fixKeys:function(){if(Ext.isIE){return function(D){var A=D.getKey(),B;if(A==D.TAB){D.stopEvent();B=this.doc.selection.createRange();if(B){B.collapse(true);B.pasteHTML("&nbsp;&nbsp;&nbsp;&nbsp;");this.deferFocus()}}else{if(A==D.ENTER){B=this.doc.selection.createRange();if(B){var C=B.parentElement();if(!C||C.tagName.toLowerCase()!="li"){D.stopEvent();B.pasteHTML("<br />");B.collapse(false);B.select()}}}}}}else{if(Ext.isOpera){return function(B){var A=B.getKey();if(A==B.TAB){B.stopEvent();this.win.focus();this.execCmd("InsertHTML","&nbsp;&nbsp;&nbsp;&nbsp;");this.deferFocus()}}}else{if(Ext.isSafari){return function(B){var A=B.getKey();if(A==B.TAB){B.stopEvent();this.execCmd("InsertText","\t");this.deferFocus()}}}}}}(),getToolbar:function(){return this.tb},buttonTips:{bold:{title:"Bold (Ctrl+B)",text:"Make the selected text bold.",cls:"x-html-editor-tip"},italic:{title:"Italic (Ctrl+I)",text:"Make the selected text italic.",cls:"x-html-editor-tip"},underline:{title:"Underline (Ctrl+U)",text:"Underline the selected text.",cls:"x-html-editor-tip"},increasefontsize:{title:"Grow Text",text:"Increase the font size.",cls:"x-html-editor-tip"},decreasefontsize:{title:"Shrink Text",text:"Decrease the font size.",cls:"x-html-editor-tip"},backcolor:{title:"Text Highlight Color",text:"Change the background color of the selected text.",cls:"x-html-editor-tip"},forecolor:{title:"Font Color",text:"Change the color of the selected text.",cls:"x-html-editor-tip"},justifyleft:{title:"Align Text Left",text:"Align text to the left.",cls:"x-html-editor-tip"},justifycenter:{title:"Center Text",text:"Center text in the editor.",cls:"x-html-editor-tip"},justifyright:{title:"Align Text Right",text:"Align text to the right.",cls:"x-html-editor-tip"},insertunorderedlist:{title:"Bullet List",text:"Start a bulleted list.",cls:"x-html-editor-tip"},insertorderedlist:{title:"Numbered List",text:"Start a numbered list.",cls:"x-html-editor-tip"},createlink:{title:"Hyperlink",text:"Make the selected text a hyperlink.",cls:"x-html-editor-tip"},sourceedit:{title:"Source Edit",text:"Switch to source editing mode.",cls:"x-html-editor-tip"}}});Ext.reg("htmleditor",Ext.form.HtmlEditor);