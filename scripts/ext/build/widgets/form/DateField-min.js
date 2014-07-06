/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.DateField=Ext.extend(Ext.form.TriggerField,{format:"m/d/Y",altFormats:"m/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d",disabledDaysText:"Disabled",disabledDatesText:"Disabled",minText:"The date in this field must be equal to or after {0}",maxText:"The date in this field must be equal to or before {0}",invalidText:"{0} is not a valid date - it must be in the format {1}",triggerClass:"x-form-date-trigger",showToday:true,defaultAutoCreate:{tag:"input",type:"text",size:"10",autocomplete:"off"},initComponent:function(){Ext.form.DateField.superclass.initComponent.call(this);if(typeof this.minValue=="string"){this.minValue=this.parseDate(this.minValue)}if(typeof this.maxValue=="string"){this.maxValue=this.parseDate(this.maxValue)}this.ddMatch=null;this.initDisabledDays()},initDisabledDays:function(){if(this.disabledDates){var A=this.disabledDates;var C="(?:";for(var B=0;B<A.length;B++){C+=A[B];if(B!=A.length-1){C+="|"}}this.disabledDatesRE=new RegExp(C+")")}},setDisabledDates:function(A){this.disabledDates=A;this.initDisabledDays();if(this.menu){this.menu.picker.setDisabledDates(this.disabledDatesRE)}},setDisabledDays:function(A){this.disabledDays=A;if(this.menu){this.menu.picker.setDisabledDays(A)}},setMinValue:function(A){this.minValue=(typeof A=="string"?this.parseDate(A):A);if(this.menu){this.menu.picker.setMinDate(this.minValue)}},setMaxValue:function(A){this.maxValue=(typeof A=="string"?this.parseDate(A):A);if(this.menu){this.menu.picker.setMaxDate(this.maxValue)}},validateValue:function(E){E=this.formatDate(E);if(!Ext.form.DateField.superclass.validateValue.call(this,E)){return false}if(E.length<1){return true}var C=E;E=this.parseDate(E);if(!E){this.markInvalid(String.format(this.invalidText,C,this.format));return false}var F=E.getTime();if(this.minValue&&F<this.minValue.getTime()){this.markInvalid(String.format(this.minText,this.formatDate(this.minValue)));return false}if(this.maxValue&&F>this.maxValue.getTime()){this.markInvalid(String.format(this.maxText,this.formatDate(this.maxValue)));return false}if(this.disabledDays){var A=E.getDay();for(var B=0;B<this.disabledDays.length;B++){if(A===this.disabledDays[B]){this.markInvalid(this.disabledDaysText);return false}}}var D=this.formatDate(E);if(this.ddMatch&&this.ddMatch.test(D)){this.markInvalid(String.format(this.disabledDatesText,D));return false}return true},validateBlur:function(){return !this.menu||!this.menu.isVisible()},getValue:function(){return this.parseDate(Ext.form.DateField.superclass.getValue.call(this))||""},setValue:function(A){Ext.form.DateField.superclass.setValue.call(this,this.formatDate(this.parseDate(A)))},parseDate:function(D){if(!D||Ext.isDate(D)){return D}var B=Date.parseDate(D,this.format);if(!B&&this.altFormats){if(!this.altFormatsArray){this.altFormatsArray=this.altFormats.split("|")}for(var C=0,A=this.altFormatsArray.length;C<A&&!B;C++){B=Date.parseDate(D,this.altFormatsArray[C])}}return B},onDestroy:function(){if(this.menu){this.menu.destroy()}if(this.wrap){this.wrap.remove()}Ext.form.DateField.superclass.onDestroy.call(this)},formatDate:function(A){return Ext.isDate(A)?A.dateFormat(this.format):A},menuListeners:{select:function(A,B){this.setValue(B)},show:function(){this.onFocus()},hide:function(){this.focus.defer(10,this);var A=this.menuListeners;this.menu.un("select",A.select,this);this.menu.un("show",A.show,this);this.menu.un("hide",A.hide,this)}},onTriggerClick:function(){if(this.disabled){return }if(this.menu==null){this.menu=new Ext.menu.DateMenu()}Ext.apply(this.menu.picker,{minDate:this.minValue,maxDate:this.maxValue,disabledDatesRE:this.ddMatch,disabledDatesText:this.disabledDatesText,disabledDays:this.disabledDays,disabledDaysText:this.disabledDaysText,format:this.format,showToday:this.showToday,minText:String.format(this.minText,this.formatDate(this.minValue)),maxText:String.format(this.maxText,this.formatDate(this.maxValue))});this.menu.on(Ext.apply({},this.menuListeners,{scope:this}));this.menu.picker.setValue(this.getValue()||new Date());this.menu.show(this.el,"tl-bl?")},beforeBlur:function(){var A=this.parseDate(this.getRawValue());if(A){this.setValue(A)}}});Ext.reg("datefield",Ext.form.DateField);