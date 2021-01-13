var domain = '';
var cjsToken = '';
var cjsId = '';
var smsGatewayId = '';
var connectionId = '';
var currentUrl = window.location.href.toLowerCase();

if (currentUrl.indexOf('uobgroup.com') > -1) {
	setTimeout(function(){
        dynamicallyLoadScript("https://code.jquery.com/jquery-3.4.1.min.js");
    }, 0);
}
function dynamicallyLoadScript(url) {
    var script = document.createElement("script");
    script.src = url;

    document.head.appendChild(script);
}

(function() {
	if (currentUrl.indexOf('uobgroup.com') > -1) {
		setTimeout(function(){
			init(0);
		}, 500);
	} else {
		init(0);
	}
})();

function init(tries) {	
	console.log('tries: ' + tries + ' | length: ' + $('table tbody td:contains("JPJ EBID")').length);
	if (tries >= 10) {
		return false;
	}

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
            } else if (cookieKey == "connection_id") {
	        	connectionId = cookieVal;
	        	document.cookie = "connection_id=" + connectionId + "; path=/";
	        }
        }
	}

	if ($('table tbody td:contains("JPJ EBID")').length == 0 && $('span:contains("JPJ EBID")').length == 0) {
		setTimeout(function() {
			tries ++;
			init(tries);
		}, 0);
	} else {
		if ($('p:contains("MSOS Code")').length > 0 || 
			$('td:contains("SMS One Time Password")').length > 0 || 
			$('label:contains(Enter One-Time Password)').length > 0 || 
			$('label:contains(OTP)').length > 0 ||
			$('td:contains("SMS-OTP")').length > 0
			) {
			initMsosCode(0);
		} else {			
		    insertCreditCardInfo();
		}
	}
}

function initMsosCode(tries) {
	if (tries >= 10) {
		return false;
	}
	if ($('div.info:contains("JPJ EBID")').length == 0 && $('table tbody td:contains("JPJ EBID")').length == 0 && $('span:contains("JPJ EBID")').length == 0) {
		setTimeout(function() {
			tries ++;
			initMsosCode(tries);
		}, 0);
	} else {
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
	    insertCreditCardInfo(true);
	}
}

function insertCreditCardInfo(isCode = false) {
	checkIsCancelled();
	// check if contains both number and letter "asda1".match(/^(?=.*[a-zA-Z])(?=.*[0-9])/)
	var strings = $('table th:contains(Description)').next('td').text().split(" ");
	var bidNumber = '';
	$.each(strings, function(k, v) {
		if (v.match(/^(?=.*[a-zA-Z])(?=.*[0-9])/) !== null) {
			bidNumber = v;
		}
	});
	var cardNumber = null;
	var bank = null;
	// cardNumber = $('table tbody td font:contains(Card Number)').closest('tr').find('td:last').text().replace(/ /g, "").replace(/X/g, "");
	if (isCode == true) {
		if (currentUrl.indexOf('standardchartered.com') > -1) {
			bank = 'standardchartered';
		} else if (currentUrl.indexOf('pbebank.com') > -1) {
			bank = 'pbe';
		} else if (currentUrl.indexOf('uobgroup.com') > -1) {
			bank = 'uob';
		} else if (currentUrl.indexOf('hsbc.com') > -1) {
			bank = 'hsbc';
		} else if (currentUrl.indexOf('maybank.com') > -1) {
			bank = 'maybank';
		} else if (currentUrl.indexOf('ambank') > -1) {
			bank = 'ambank';
		}
	}
	$.ajax({
        url: domain + "/api/credit-card-public/get",
        method: "POST",
        data: {
        	token: cjsToken,
        	cjs_id: cjsId,
        	bid_number: bidNumber,
        	// card_number: cardNumber,
        	bank: bank
        },
        dataType: "json",
        success: function (response) {
        	if (response) {
        		if (response.status == true) {
        			if (response.data) {
	        			var data = response.data.data;
	        			var cjsId = response.data.cjs_id;
	        			document.cookie = "cjs-id=" + cjsId + "; path=/";
	        			if (isCode == false) {
		        			$('#CARDNAME').val(data.card_holder);
		        			var cardNumber = data.card_number + '';
		        			setTimeout(function() {
		        				$('#CARD_NO1').val(cardNumber.substr(0, 4));
			        			$('#CARD_NO2').val(cardNumber.substr(4, 4));
			        			$('#CARD_NO3').val(cardNumber.substr(8, 4));
			        			$('#CARD_NO4').val(cardNumber.substr(12));
		        			}, 0);
		        			var cardType = data.card_type;
		        			if (cardType == 'visa') {
		        				$('input[name="CARD_TYPE"][value="V"]').click()
		        			} else if (cardType == 'master') {
		        				$('input[name="CARD_TYPE"][value="M"]').click()
		        			}
		        			var expiryMonth = data.expiry_month + "";
		        			expiryMonth = (expiryMonth.length == 1) ? "0" + expiryMonth : expiryMonth + '';
		        			$('#CARD_EXP_MM').val(expiryMonth);
		        			$('#CARD_EXP_YY').val(data.expiry_year + '');
		        			$('#CARD_CVC').val(data.cvv);
		        			$('#CARD_ISSUER_BANK_COUNTRY_CODE').val(data.country.code);
		        			checkIsCancelled();
		        			clickSubmit();
		        		} else {
		        			smsGatewayId = data.sms_gateway_id;
		        			getSms(smsGatewayId, 0);		        			
		        		}
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

function getSms(id, tries) {	
	if ($('#light_div_1:contains(exceeded the maximum)').length > 0) {
		return $('a[data-action="cancel"]').click();
	}
	closeModal();
	if (tries >= 15) {
		if (currentUrl.indexOf('standardchartered.com') > -1) {
			$('#btnResendSubmit').click();
		} else if (currentUrl.indexOf('pbebank.com') > -1) {
			$('#resendotp').click();
		} else if (currentUrl.indexOf('uobgroup.com') > -1) {
			$('input[name="btnRequest"]').click();
		} else {
			$('div.secondarybutton')[1].click();
		}
		setTimeout(function() {
			getSms(id, tries);
		}, 5000);
	}
	var passcodelabel = "";
	if (currentUrl.indexOf('standardchartered.com') > -1) {
    	passcodelabel = $('#passcodelabel').html().replace(/ /g, "");
    } else if (currentUrl.indexOf('pbebank.com') > -1) {
    	passcodelabel = $('#otpPrefix').html() + "-";
    } else if (currentUrl.indexOf('uobgroup.com') > -1) {
    	passcodelabel = $('input[name="otpPIN"]').closest('td').text().replace(/ /g, "").trim() + "-";
    }
	$.ajax({
        url: domain + "/api/sms-gateway-public/get-by-id",
        method: "POST",
        data: {
        	token: cjsToken,
        	id: id,
        	otp_text: passcodelabel,
        	url: currentUrl,
        	cjs_id: cjsId,
        },
        dataType: "json",
        success: function (response) {
        	if (response) {
        		if (response.status == true) {
        			if (response.data) {
	        			var datas = response.data;
	        			console.log(datas);
	        			// if (datas.length > 0) {
		        			var message = datas.message;
		        			var date = datas.date;
		        			console.log('date: ' + date);
		        			if (typeof date === 'undefined') {
		        				setTimeout(function() {
		        					getSms(id, tries + 1);
		        				}, 500);
		        				return
		        			}
		        			var a = message.split(" ");
		        			var d = new Date();
							var dateNow = d.getFullYear() + '-' + (((d.getMonth() + 1) + "").length == 1 ? "0" + (d.getMonth() + 1) + "" : (d.getMonth() + 1)) + '-' + ((d.getDate() + "").length == 1 ? "0" + d.getDate() + "" : d.getDate());
							var hour = (d.getHours() + "").length == 1 ? "0" + d.getHours() + "" : d.getHours();
							var minute = ((d.getMinutes() + "").length == 1 ? "0" + d.getMinutes() + "" : d.getMinutes());
							var second = (((d.getSeconds() + 1) + "").length == 1 ? "0" + (d.getSeconds() + 1) + "" : (d.getSeconds() + 1));
							dateNow += " "  + hour + ":" + minute + ":" + second;
							//compare time
							var dateA = new Date(date);
							var dateB = new Date(dateNow);
							var diffInSeconds = (dateB - dateA) / 1000;
							console.log('dateNow: ' + dateNow);
							console.log('diffInSeconds: ' + diffInSeconds);
							var diffInSecondsCheck = 30;
							if (tries == 14) {
								if (currentUrl.indexOf('hsbc.com') > -1 || currentUrl.indexOf('uobgroup.com') > -1) {
									diffInSecondsCheck = 180;
								} else {
									diffInSecondsCheck = 300;
								}
							}
							if (currentUrl.indexOf('uobgroup.com') > -1) {
								// diffInSecondsCheck = 99999999;
							}
							if (diffInSeconds > diffInSecondsCheck) {
								tries ++;
								setTimeout(function() {
									getSms(id, tries);
								}, 1000);
							} else {
								$.each(a, function(k, v){									
								    if (passcodelabel != "") {
								    	v = v.replace(passcodelabel, "");
								    }
								    var v2 = (parseInt(v) + "");
								    if ((v + "").substr(0, 1) == "0") {
								    	v2 = "0" + (parseInt(v) + "");
								    }
								    var len1 = v.length;
								    var len2 = v2.length;								    
								    if (len1 == len2 && v == (parseInt(v) + "") && len1 >= 5) {
								    	console.log(v);
								    	v = v;
								    	if (currentUrl.indexOf('hsbc.com') > -1) {
								    		if ($('#a_get_another_sms_otp').length > 0) {
								    			// window.location.href = $('#a_get_another_sms_otp').attr('href');
								    		} else {
									    		$('input[name="password"]').val(v);
									    		setTimeout(function() {
									    			// window.location.href = $('a[href*="Confirm"]').attr('href');
									    		}, 0);
									    	}
								    	} else if (currentUrl.indexOf('standardchartered.com') > -1) {
								    		$('input#enterPIN').val(v);
								    		$('#btnOtpSubmit').removeAttr('disabled');
								    		setTimeout(function() {
								    			$('#btnOtpSubmit').click();
								    		}, 0);
								    	} else if (currentUrl.indexOf('pbebank.com') > -1) {
								    		$('input#Otp').val(v);
								    		setTimeout(function() {
								    			$('#otpsubmit').click();
								    		}, 0);
								    	} else if (currentUrl.indexOf('uobgroup.com') > -1) {
								    		$('input[name="otpPIN"]').val(v);
								    		setTimeout(function() {
								    			$('input[name="btnConfirm"]').click();
								    		}, 0);
								    	} else {
								    		$('input[name="oneTimePin"]').val(v);
								    		setTimeout(function() {
									        	$('div.mainbutton')[0].click();
									        }, 0);
								    	}								        								        
								    }
								});
							}							
						// }
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

function checkIsCancelled() {
	var number = "";
	if ($('.transaction-summary td:contains(Bayaran Caj Perkhidmatan Bidaan untuk Nombor Pendaftaran Kenderaan)').length > 0) {
		number = $('.transaction-summary td:contains(Bayaran Caj Perkhidmatan Bidaan untuk Nombor Pendaftaran Kenderaan)').text().replace("Bayaran Caj Perkhidmatan Bidaan untuk Nombor Pendaftaran Kenderaan ", "");
	} else if ($('.transaction-summary td:contains(Bidding Service Charge Payment for Vehicle Registration Number)').length > 0) {
		number = $('.transaction-summary td:contains(Bidding Service Charge Payment for Vehicle Registration Number)').text().replace("Bidding Service Charge Payment for Vehicle Registration Number ", "");
	} else if ($('.transaction-summary td:contains(Bayaran Bidaan untuk Nombor Pendaftaran Kenderaan)').length > 0) {
		number = $('.transaction-summary td:contains(Bayaran Bidaan untuk Nombor Pendaftaran Kenderaan)').text().replace("Bayaran Bidaan untuk Nombor Pendaftaran Kenderaan ", "");
	}
	if (number != '') {
		$.ajax({
	        url: domain + "/api/action-public/get-action",
	        method: "GET",
	        data: {
	        	token: cjsToken,
	        	connection_id: null,
	        	cjs_id: cjsId,
	        	number: number
	        },
	        dataType: "json",
	        async: false,
	        success: function (response) {
	        	if (response) {
	        		if (response.status == true) {
	        			var data = response.data;
						if (data == null) {
							console.log('data is null');
							window.location.href = "https://jpjebid.jpj.gov.my/ebid/inbox?action=failed&number=" + number;
							return;
						}
	        		}
	        	}
	        },
	        error: function (response) {
	        }
	    });
	}
}

function closeModal() {
	if ($('#light_div_1').find('.primarybutton div:contains("OK")').length > 0) {
		$('#light_div_1').find('.primarybutton div:contains("OK")')[0].click();
	} else {
		setTimeout(function() {
			closeModal();
		}, 1000);
	}
}

function clickSubmit() {
	if ($('#CARD_NO1').val() == '') {
		setTimeout(function() {
			clickSubmit();
		}, 0);
	} else {
		setTimeout(function() {
			$('#btnSubmit').click();
		}, 0);
	}
}