/**
 *
 * @source: http://www.lduros.net/some-javascript-source.js
 *
 * @licstart  The following is the entire license notice for the 
 *  JavaScript code in this page.
 *
 * Copyright (C) 2012  Loic J. Duros
 *
 *
 * The JavaScript code in this page is free software: you can
 * redistribute it and/or modify it under the terms of the GNU
 * General Public License (GNU GPL) as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option)
 * any later version.  The code is distributed WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU GPL for more details.
 *
 * As additional permission under GNU GPL version 3 section 7, you
 * may distribute non-source (e.g., minimized or compacted) forms of
 * that code without the copy of the GNU GPL normally required by
 * section 4, provided you include this license notice and a URL
 * through which recipients can access the Corresponding Source.
 *
 * @licend  The above is the entire license notice
 * for the JavaScript code in this page.
 *
 */

function adddoc() {
    if ($("#doc"+$("#docadd").val()).length) {
	$("#docalready").fadeIn(400).delay(3000).fadeOut(400);
        $("#docadd").focus();
	return;
    } 
    $.ajax({
	url: "/release/addjs?id="+$("#id").val()+"&doc="+$("#docadd").val(), 
	data: {value: 1},
	method: 'get',
	error: function(XMLHttpRequest, textStatus, errorThrown){
            // alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
	    if (XMLHttpRequest.status==404) {
		$("#docnotfound").fadeIn(400).delay(3000).fadeOut(400);
		$("#docadd").focus();
	    }
	},
	success: function(data) {
            $('#doclist tr:last').after(data);
	}
    });
    $("#docadd").val("");
    $("#docadd").focus();
}



function deldoc(id) {
    $("#doc"+id).remove();
}


function adddocnow(doc,release) {
    $.ajax({
	url: "/release/adddoc?id="+release+"&doc="+doc, 
	data: {value: 1},
	method: 'get',
	error: function(XMLHttpRequest, textStatus, errorThrown){
            // alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
	    if (XMLHttpRequest.status==404) {
		$("#docerror").fadeIn(400).delay(3000).fadeOut(400);
	    }
	    if (XMLHttpRequest.status==403) {
		$("#docalready").fadeIn(400).delay(1000).fadeOut(400);
	    }
	},
	success: function(data) {
	    $("#docadded").fadeIn(400).delay(1000).fadeOut(400);
	}
    });
}

