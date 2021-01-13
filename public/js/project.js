var ladda;
var laddaId;

function startLadda(id, isId = true) {
	var selector = isId ? document.getElementById(id) : document.querySelector(id);
	if (selector != null) {
	    ladda = Ladda.create(selector);
	    ladda.start();
	}
}

function stopLadda() {
	if (typeof ladda !== 'undefined')
    	ladda.stop();
}

function notify(message, type) {
	$.notify({ 
		message : message
	}, {
		type : type,
		delay : 1,
		template : '<div id="notify-alert" data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'
	});
}