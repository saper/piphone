

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

