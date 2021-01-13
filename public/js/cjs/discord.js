var domain = '';
var cjsToken = '';
var cjsId = '';
var actionId = '';
var updatedNumber = [];

setTimeout(function(){
    dynamicallyLoadScript("https://code.jquery.com/jquery-3.4.1.min.js");
}, 0);
function dynamicallyLoadScript(url) {
    var script = document.createElement("script");
    script.src = url;

    document.head.appendChild(script);
}

// (function() {
	var x = document.cookie;
    if (x != "") {
        var y = x.split("; ");
        var len = y.length;
        var i;
        for (i = 0; i < len; i ++) {
            cookieParam = y[i].split("=");
            cookieKey = cookieParam[0];
            cookieVal = cookieParam[1];
            if (cookieKey == "domain") {
                domain = cookieVal;
                document.cookie = "domain=" + domain + "; path=/";
            } else if (cookieKey == "cjs-token") {
            	cjsToken = cookieVal;
            	document.cookie = "cjs-token=" + cjsToken + "; path=/";
            } else if (cookieKey == "cjs-id") {
            	cjsId = cookieVal;
            	document.cookie = "cjs-id=" + cjsId + "; path=/";
            }
        }
    }    
// });

init();

function init() {
	if (typeof window.jQuery === 'undefined') {
		setTimeout(function() {
			init();
		}, 300);
	} else {
		setTimeout(function() {
		    getContent();
			var keepConnectionInterval = setInterval(function() {
			    getContent();
			}, 3000);

			getAction();
			var getActionInterval = setInterval(function() {
				getAction();
			}, 3000);
		}, 500);
	}
}

function getContent() {
    var eleChat = document.querySelector("[id*=\'chat-messages\']");
    if (eleChat == null) {
        setTimeout(function() {
            getContent();
        }, 0);
    } else {
        var ele = document.querySelectorAll("[id*=\'chat-messages\'] div");
        var len = ele.length;
        var i = 0;
        var contents = [];
        var filteredContents = [];
        for (i = 0; i < len; i ++) {
            var v = ele[i];
            if (v.className.indexOf("messageContent") > -1) {
                contents.push(v.textContent);
            }
        }
        
        var len2 = contents.length;
        var j;
        for (j = 0; j < len2; j ++) {
            if (typeof contents[j] !== "undefined" && contents[j].indexOf("Highest for") > -1) {
                var t = contents[j].split(", ");
                var text = "";
                if (typeof t[1] !== "undefined") {
                    text = t[1];
                }
                if (text != "") {
                    // console.log(text);
                    var textArr = text.replace("Highest for ", "").split(" is ");
                    var obj = { number : textArr[0], price : textArr[1] };
                    filteredContents.push(obj);
                }
            }
        }

        var ele = document.querySelectorAll("[class*=\'embedWrapper\']");
        var len = ele.length;
        var i;
        for (i = 0; i < len; i ++) {
            if (ele[i].innerHTML.indexOf("SINGLE DIGITS") > -1) {
                var a = ele[i].querySelectorAll("[class*=\'embedFieldName\']");
                var len2 = a.length;
                var j;
                for (j = 0; j < len2; j ++) {
                    var number = a[j].innerText;
                    var price = a[j].nextSibling.innerText;
                    if (number !== "TOTAL AMOUNT") {
                        var obj = { number : number, price : price };
                        console.log(obj);
                        filteredContents.push(obj);
                    }
                }
            }
        }
        if (filteredContents.length > 0) {
            create(filteredContents);
        }
    }
}

function create(filteredContents) {
    var url = domain + "/api/number-price-public/create";
    var method = "POST";
    var request = new XMLHttpRequest();
    var data = new FormData();
    var stringifyData = JSON.stringify(filteredContents);
    data.append("token", cjsToken);
    data.append("cjs_id", cjsId);
    data.append("data", stringifyData);
    request.onload = function () {
        var status = request.status; // HTTP response status, e.g., 200 for "200 OK"
        var data = request.data; // Returned data, e.g., an HTML document.
        if (status == 200) {
            console.log("Status: " + status + " | data: " + JSON.parse(request.response).data);
            updatedNumber = data;
        } else if (status == 422) {
            console.log("Status: " + status + " | data: " + JSON.parse(request.response));
        } else {
            console.log(request);
        }
    };
    request.open(method, url, true);
    // request.setRequestHeader("Content-Type", "application/json");
    request.send(data);
}

function searchNumber(number, tries = 0) {
    console.log("search number tries: " + tries);
    if (tries >= 10) {
        updateAction(actionId, 'discord_search', 'completed');
    }
	var a = number;
	var first = a.substr(0, 1);
	var rest = a.substr(1);
	document.querySelector('span[data-slate-leaf="true"]').innerHTML = ('<span data-slate-string="true">' + rest + '<br></span>');
	setTimeout(function() {
		$(document.querySelector('div[role="textbox"][class*=TextArea]')).focus();
		setTimeout(function() {
			$('.aftan-keyboard-key:contains(' + first + ')').click();
			setTimeout(function() {
				const ke = new KeyboardEvent('keydown', {
				    bubbles: true, cancelable: true, keyCode: 13
				});
				document.body.dispatchEvent(ke);
				if (document.querySelector('span[data-slate-string="true"]') != null && document.querySelector('span[data-slate-string="true"]').innerHTML.length > 5) {
					console.log('length: ' + $('span[data-slate-string="true"]').length);
                    setTimeout(function() {
                        if (document.querySelector('span[data-slate-string="true"]') === null) {
                            setTimeout(function() {
                                searchNumber(number, (tries + 1));
                            }, 100);
                        } else if (document.querySelector('span[data-slate-string="true"]').innerHTML !== rest + '<br>') {
                            updateAction(actionId, 'discord_search', 'completed');
                            document.querySelector('div[role="textbox"][class*=TextArea]').dispatchEvent(ke);
                        }
                    }, 100);
				}
			}, 100);
		}, 100);
	}, 500);
}

function getAction() {
	$.ajax({
        url: domain + "/api/action-public/get-action",
        method: "GET",
        data: {
        	token: cjsToken,
        	cjs_id: cjsId,
        	connection_id: 'discord_search'
        },
        dataType: "json",
        success: function (response) {
        	if (response) {
        		if (response.status == true) {
        			var data = response.data;
        			if (data == null) {
        				// updateBiddingNumber
        				if ($('p:contains("Masa Tamat")').length > 0 || $('p:contains("Time Left")').length > 0) {
        					var prefix = window.location.pathname.split("/")[6];
        					var number = window.location.pathname.split("/")[4];
        					var stateCode = window.location.pathname.split("/")[5];
        					var html = $('#tdRankId').html();        					
        					updateBiddingNumber(prefix, number, stateCode, html);
        				}
        				return false;
        			}
        			// console.log(response);
        			// return false;
        			var action = data.action;
        			var details = JSON.parse(data.details);
        			if (action == 'discord_search') {
        				var prefix = details['prefix'];
        				var number = details['number'];
        				var search = prefix + ' ' + number;
        				actionId = data.id;
        				searchNumber(search);
        			}
        		}
        	}
        },
        error: function(response) {
        	var error = '';
        	console.log(response.responseJSON);
            if (response.responseJSON) {
                if (response.responseJSON.status && response.responseJSON.status == 'error') {
                    if (response.responseJSON.data.error) {
                        error = response.responseJSON.data.error;
                    }
                    if (error != '') {
                        alert(error);
                    }
                }
            } else {
                alert('Something had happaned, please try again later.');
            }
        }
    });
}

function updateAction(id, status, extra = null) {
	$.ajax({
        url: domain + "/api/action-public/update",
        method: "POST",
        data: {
        	token: cjsToken,
            status: status,
            id: id,
            extra: extra,
            cjs_id: cjsId,
        },
        dataType: "json",
        success: function (data) {
        	if (data) {
        		if (data.status == true) {
        		}
        	}
        },
        error: function(response) {
        	var error = '';
        	console.log(response.responseJSON);
            if (response.responseJSON) {
                if (response.responseJSON.status && response.responseJSON.status == 'error') {
                    if (response.responseJSON.data.error) {
                        error = response.responseJSON.data.error;
                    }
                    if (error != '') {
                        alert(error);
                    }
                }
            } else {
                alert('Something had happaned, please try again later.');
            }
        }
    });
}