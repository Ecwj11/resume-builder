var domain = '';
var cjsToken = '';
var cjsId = '';
var expiryDateLatest = '';
var updatedSeriesNumber = false;
var connectionId = '';
var afk = 0;
var bidNumberActionIds = '';
var autoLoginGettingDataLoaded = false;
var autoLoginCatpchaUpdate = false;

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
        } else if (cookieKey == "bid_number_action_ids") {
        	bidNumberActionIds = cookieVal;
        	document.cookie = "bid_number_action_ids=" + bidNumberActionIds + "; path=/";
        } else if (cookieKey == "connection_id") {
        	connectionId = cookieVal;
        	document.cookie = "connection_id=" + connectionId + "; path=/";
        }
    }
}

keepConnection();
var keepConnectionInterval = setInterval(function() {
	afk += 60;
	keepConnection();		
}, 60000);

// setTimeout(function(){
    dynamicallyLoadScript(domain + '/js/cjs/action.js');
    dynamicallyLoadScript(domain + '/js/html2canvas.min.js');
// }, 0);
function dynamicallyLoadScript(url) {
    var script = document.createElement("script");
    script.src = url;
    document.head.appendChild(script);
}
$(function() {	
    $('body').click(function() {
    	afk = 0;
    });
});

function keepConnection() {	
	if ($('a[data-target="#logout"]').length > 0) {
		console.log(afk);
		if (afk >= 120) {
			window.location.reload();
			return true;
		}
		if (typeof $('h5:contains("Selamat Datang")').html() !== 'undefined') {
			var name = $('h5:contains("Selamat Datang")').html().split(", ")[1];	
		} else {
			var name = $('h5:contains("Welcome")').html().split(", ")[1];
		}
		
		if (window.location.search.substr(1).indexOf('action=failed') > -1) {
			updateAction(null, 'failed_payment');
		}
		$.ajax({
	        url: domain + "/api/connect-public/initialize",
	        method: "POST",
	        data: {
	        	token: cjsToken,
	            name: name,
	            cjs_id: cjsId
	        },
	        dataType: "json",
	        success: function (response) {
	        	if (response) {
	        		if (response.status == true) {
	        			connectionId = response.data['id'];
	        			var cjsId = response.data['cjs_id'];
	        			document.cookie = "connection_id=" + connectionId + "; path=/";
	        			document.cookie = "cjs-id=" + cjsId + "; path=/";
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
	} else if (window.location.href.toLowerCase().indexOf('login') > -1) {
		var search = window.location.search.substr(1);
		if (search.indexOf('login=1&id=') > -1) {
			var accountId = search.replace("login=1&id=", "");
			setInterval(function() {
				if (autoLoginGettingDataLoaded == false)
					autoLogin(accountId);
			}, 1000);			
		}
	}

	if (($('table:contains("Awalan")').length > 0 || $('table:contains("Prefix")').length > 0) && updatedSeriesNumber == false) {
		var title = [];
		var content = [];
		var accRows = 0;
		if ($('table:contains("Awalan")').length > 0) {
			var table = $('table:contains("Awalan")');
		} else {
			var table = $('table:contains("Prefix")');
		}

		$.each(table.find('thead tr th'), function(k, v) {
			title.push(v.innerHTML);
		});
		var trs = table.find('tbody tr');
		$.each(trs, function(k, v) {
			var td = {};
			content[accRows] = {};
			var tds = $(v).find('td');
			$.each(tds, function(k2, v2){
				var val = v2.innerHTML;
				content[accRows][title[k2]] = val;
			});
			accRows ++;
		});

		$.ajax({
	        url: domain + "/api/latest-series-number-public/create",
	        method: "POST",
	        data: {
	        	token: cjsToken,
	        	cjs_id: cjsId,
	            contents: content
	        },
	        dataType: "json",
	        success: function (data) {
	        	if (data) {
	        		if (data.status == true) {
	        			expiryDateLatest = data.data;
	        			updatedSeriesNumber = true;
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

	if ($('div.card-footer:contains(Pembayaran anda tidak berjaya!)').length > 0) {
		if ($('h5:contains(Caj Perkhidmatan)').length == 0) {
			updateAction(null, 'bid_number_payment_failed');
		}
		setTimeout(function() {
			$('button.buttonPay').click();
		}, 3000);
	} else if ($('div.card-footer:contains(Pembayaran anda telah diterima!)').length > 0) {
		if ($('h5:contains(Caj Perkhidmatan)').length == 0) {
			updateAction(null, 'bid_number_payment_success');
		}
		setTimeout(function() {
			$('button.buttonPay').click();
		}, 3000);
	}
}

function updateAction(id = null, status) {
	var price = 0;
	if (status == 'bid_number_payment_failed') {
		if ($('td:contains(Amaun Bidaan)').length > 0) {
			price = $('td:contains(Amaun Bidaan)').next('td').text().replace("RM ", "");
		} else {
			price = $('td:contains(Bid Amount)').next('td').text().replace("RM ", "");
		}
	} else if (status == 'bid_number_payment_success') {
		if ($('td:contains(Amaun Bidaan)').length > 0) {
			price = $('td:contains(Amaun Bidaan)').next('td').text().replace("RM ", "");
		} else {
			price = $('td:contains(Bid Amount)').next('td').text().replace("RM ", "");
		}
	}
	if (window.location.search.substr(1).indexOf('action=failed') > -1) {
		var windowData = window.location;
        if (windowData.search == "") {
            var data = windowData.hash;
        } else {
            var data = windowData.search;
        }
        var number = "";
        var j;
        var paramDatas = data.split("&");
        var paramDatasLen = paramDatas.length;
        for (j = 0; j < paramDatasLen; j++) {
            var params = paramDatas[j].split("=");
            var paramKey = params[0];
            var paramVal = params[1];
            if (paramKey == "number")
                number = paramVal;
        }
	}
	$.ajax({
        url: domain + "/api/action-public/update",
        method: "POST",
        data: {
        	token: cjsToken,
            status: status,
            id: id,
            price: price,
            ids: bidNumberActionIds,
            cjs_id: cjsId,
            number: number
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

function autoLogin(accountId) {
	autoLoginGettingDataLoaded = true;
	$.ajax({
        url: domain + "/api/account-public/get-account",
        method: "GET",
        data: {
        	id: accountId
        },
        dataType: "json",
        success: function (response) {
        	if (response) {
        		if (response.status == true) {
        			var data = response.data;
        			var idNo = data.id_no;
        			var password = data.password;
        			var captcha = data.captcha;
        			$('#username').val(idNo);
        			$('#password').val(password);
        			/*$('#captcha').val(captcha);
        			setTimeout(function() {
        				if (captcha != '')
        					$('button[type="submit"]').click();
        			}, 100);
        			if (autoLoginCatpchaUpdate === false) {
	        			setTimeout(function() {        				
	        				$('#captcha_image').wrap('<div id="capture"></div>');
		        			html2canvas(document.querySelector('#capture')).then(function(canvas) {
								var url = canvas.toDataURL();
								$.ajax({
							        url: domain + "/api/account-public/update",
							        method: "POST",
							        data: {
							        	id: accountId,
							        	url: url
							        },
							        dataType: "json",
							        success: function (data) {
							        	if (data) {
							        		if (data.status == true) {
							        			autoLoginCatpchaUpdate = true;
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
							});
						}, 500);
	        		}*/
					autoLoginGettingDataLoaded = false;
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