function moveOptionCheck(option, jFrom, jTo) {
 if (option.selected) {
  var jOption = $(option);
  var jFromOptgroup = jOption.parent("optgroup");
  var jToOptgroup;
  if (jFromOptgroup.length > 0) {
   var label = jFromOptgroup.attr("label");
   jToOptgroup = jTo.children("optgroup[label='" + label + "']");
   if (jToOptgroup.length == 0) {
    var found = false;
    var jToOptgroups = jTo.children("optgroup");
    if (jToOptgroups.length > 0) {
     var last;
     jToOptgroups.each(function() {
      if (!found) {
       var jThis = $(this);
       if (jThis.attr("label") > label) {
        jThis.before("<optgroup label='" + label + "'></optgroup>");
        found = true;
       }
      }
      last = jThis;
     });
     if (!found) {
      last.after("<optgroup label='" + label + "'></optgroup>");
     }
    } else if (!found) {
     jTo.prepend("<optgroup label='" + label + "'></optgroup>");
    }
    jToOptgroup = jTo.children("optgroup[label='" + label + "']");
   }
  } else {
   jToOptgroup = jTo;
  }
  var jToOptions = jToOptgroup.children("option");
  var found = false;
  if (jToOptions.length > 0) {
   jToOptions.each(function() {
    if (!found) {
     var jThis = $(this);
     if (jThis.text() > jOption.text()) {
      jThis.before("<option value='" + jOption.val() + "'>" + jOption.text() + "</option>");
      jOption.remove();
      found = true;
     }
    }
   });
  }
  if (!found) {
   jOption.appendTo(jToOptgroup);
  }
  if (jFromOptgroup.children("option").length == 0) {
   jFromOptgroup.remove();
  }
 }
}
function moveOption(fromName, toName) {
 var jFrom = $("#" + fromName);
 var jTo = $("#" + toName);
 jFrom.children("option").each(function(){moveOptionCheck(this, jFrom, jTo);});
 jFrom.children("optgroup").children("option").each(function(){moveOptionCheck(this, jFrom, jTo);});
 jFrom.each(function(){this.selectedIndex = -1;});
 jTo.each(function(){this.selectedIndex = -1;});
}
