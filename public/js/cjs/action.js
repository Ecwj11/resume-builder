// var domain = '';
// var cjsToken = '';
var creditCardType = '';
var pathName = window.location.pathname;
var bidNumberActionIds = '';

$(function() {
	var x = document.cookie;
    if (x != "") {
        var y = x.split("; ");
        var len = y.length;
        var i;
        for (i = 0; i < len; i ++) {
            cookieParam = y[i].split("=");
            cookieKey = cookieParam[0];
            cookieVal = cookieParam[1];
            if (cookieKey == "bid_number_action_ids") {
                bidNumberActionIds = cookieVal;
                document.cookie = "bid_number_action_ids=" + bidNumberActionIds + "; path=/";
            }
        }
    }

    getAction();
    var getActionInterval = setInterval(function() {
		getAction();
		// checkInbox();
	}, 3000);

	// checkInbox();
});

function getAction() {
	if ($('body:contains("Service Unavailable")').length > 0) {
		return false;
	}
	if ($('a[data-target="#logout"]').length > 0) {
		if (typeof $('h5:contains("Selamat Datang")').html() !== 'undefined') {
			var name = $('h5:contains("Selamat Datang")').html().split(", ")[1];	
		} else {
			var name = $('h5:contains("Welcome")').html().split(", ")[1];
		}
		
		$.ajax({
	        url: domain + "/api/action-public/get-action",
	        method: "GET",
	        data: {
	        	token: cjsToken,
	        	cjs_id: cjsId,
	        	connection_id: connectionId
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
	        			var responses = JSON.parse(data.responses);
	        			console.log(responses);
	        			if (responses != null) {
	        				var latestResponse = "";
	        				var responseDate = "";
	        				var d = new Date();
							var dateNow = d.getFullYear() + '-' + (((d.getMonth() + 1) + "").length == 1 ? "0" + (d.getMonth() + 1) + "" : (d.getMonth() + 1)) + '-' + ((d.getDate() + "").length == 1 ? "0" + d.getDate() + "" : d.getDate());
							var hour = (d.getHours() + "").length == 1 ? "0" + d.getHours() + "" : d.getHours();
							var minute = ((d.getMinutes() + "").length == 1 ? "0" + d.getMinutes() + "" : d.getMinutes());
							var second = (((d.getSeconds() + 1) + "").length == 1 ? "0" + (d.getSeconds() + 1) + "" : (d.getSeconds() + 1));
							dateNow += " "  + hour + ":" + minute + ":" + second;

	        				$.each(responses, function(k, v) {
	        					if (responseDate == '') {
	        						responseDate = v;
	        						latestResponse = k;
	        					} else if (v > responseDate) {
	        						responseDate = v;
	        						latestResponse = k;
	        					}
	        				});
	        				console.log(latestResponse);
	        				if (latestResponse == 'making_payment') {
	        					if ($.inArray(pathName, ['/ebid/inbox']) == -1) {
	        						window.location.href = '/ebid/inbox';
	        					} else {
	        						if ($('td:contains("JPJeBid - Bayaran Caj Perkhidmatan ' + details.prefix + details.number + ' : Diterima")').length > 0) {
	        							updateAction(data.id, 'success_payment', 'add_number');
	        						} else if ($('td:contains("JPJeBid - Bayaran Caj Perkhidmatan ' + details.prefix + details.number + ' : Gagal")').length > 0) {
	        							updateAction(data.id, 'failed_payment');
	        						} else {
	        							var dateA = new Date(responseDate);
										var dateB = new Date(dateNow);
										var diffInSeconds = (dateB - dateA) / 1000;
										console.log('dateNow: ' + dateNow);
										console.log('diffInSeconds: ' + diffInSeconds);
										if (diffInSeconds > 300) {
											updateAction(data.id, 'failed_payment');
										}
	        						}
	        					}
	        				} else if (latestResponse == 'confirmed_payment_service_charge') {
	        					$('#btnPay').click();

	        					if (pathName == '/ebid/payment/paysvcchg') {
		        					var cardType = data.card_type;
		        					if ($.inArray(cardType, ['visa', 'master']) > -1) {
		        						var input = $('input[name="cardType"][value="V"]').click();
		        					} else {
		        						var input = $('input[name="cardType"][value="A"]').click();
		        					}
		        					setTimeout(function() {
		        						updateAction(data.id, 'making_payment');
		        						input.closest('form').submit();
		        					}, 300);
		        				} else {
		        					if ($('.card-body:contains("Caj Perkhidmatan")').length > 0) {
			        					var selector = $('.card-body:contains("Caj Perkhidmatan")');	
			        				} else {
			        					var selector = $('.card-body:contains("Service Charge")');
			        				}
			        				if (selector.length > 0) {
			        					$('#btnPay').click();
			        				}
		        				}
	        				} else if (latestResponse == 'success_payment') {
	        					if (action == 'search_number') {
	        						if ($('p:contains("Masa Tamat")').length > 0 || $('p:contains("Time Left")').length > 0) {
		        						var prefix = window.location.pathname.split("/")[6];
			        					var number = window.location.pathname.split("/")[4];
			        					var stateCode = window.location.pathname.split("/")[5];
			        					var html = $('#tdRankId').html();
			        					updateBiddingNumber(prefix, number, stateCode, html);
			        				}
	        					}
	        				}
	        				
	        				return false;
	        			} else {
		        			if (action == 'search_number') {
		        				if (data.url !== pathName && $.inArray(pathName, ['/ebid/payment/paysvcchg']) == -1)
		        					window.location.href = data.url;

		        				if ($('.card-body:contains("Caj Perkhidmatan")').length > 0) {
		        					var selector = $('.card-body:contains("Caj Perkhidmatan")');
		        				} else {
		        					var selector = $('.card-body:contains("Service Charge")');
		        				}
		        				if (selector.length > 0) {
		        					updateAction(data.id, 'confirmed_payment_service_charge');
		        					return $('#btnPay').click();
		        				}

		        				if ($('.card-body:contains(Bidaan nombor tidak dibenarkan)').length > 0 || 
		        					$('.card-body:contains(Bidding not allowed)').length > 0) {
		        					updateAction(data.id, 'bidding_number_not_allowed');
		        					return $('button[type="submit"]').click()
		        				}

		        				if ($('p:contains("Masa Tamat")').length > 0 || $('p:contains("Time Left")').length > 0) {
		        					updateAction(data.id, 'success_payment', 'add_number');
		        				}
		        			} else if (action == 'update_number') {
		        				if (data.url !== pathName && $.inArray(pathName, ['/ebid/payment/paysvcchg']) == -1) {
		        					window.location.href = data.url;
		        				} else {
		        					if ($('.card-body:contains("Sesi bidaan telah tamat.")').length > 0 || $('.card-body:contains(Bid session has expired)').length > 0) {
		        						updateAction(data.id, 'bidding_session_ended');
		        						return false;
		        					}
		        					var prefix = window.location.pathname.split("/")[6];
		        					var number = window.location.pathname.split("/")[4];
		        					var stateCode = window.location.pathname.split("/")[5];
		        					var html = $('#tdRankId').html();
		        					updateBiddingNumber(prefix, number, stateCode, html);
		        					return updateAction(data.id, 'updated_number');
		        				}
		        			} else if (action == 'bid_number') {
		        				// alert(pathName);
		        				
		        				var bidNumberActionIdsArr = [];
		        				if (bidNumberActionIds == "") {
		        					bidNumberActionIdsArr = [data.id];
		        				} else {
		        					bidNumberActionIdsArr.push(data.id);
		        				}
		        				bidNumberActionIdsArr = bidNumberActionIdsArr.filter(function(item, i, bidNumberActionIdsArr) {
							        return i == bidNumberActionIdsArr.indexOf(item);
							    });
							    document.cookie = "bid_number_action_ids=" + bidNumberActionIdsArr.join(",") + "; path=/";

		        				if (data.url !== pathName && $.inArray(pathName, ['/ebid/payment/paysvcchg']) == -1) {
		        					if (pathName.indexOf('/ebid/payment/cc') > -1) {
		        						if ($('button.buttonPay:contains("Hantar")').length > 0) {
		        							$('button.buttonPay:contains("Hantar")').click();
		        						} else {
		        							$('button.buttonPay:contains("Submit")').click();
		        						}
		        						setTimeout(function() {
		        							submitBid();
		        						}, 300);
		        					} else {
		        						window.location.href = data.url;
		        					}
		        				} else {
		        					if ($('.card-body:contains("Sesi bidaan telah tamat.")').length > 0 || $('.card-body:contains(Bid session has expired)').length > 0) {
		        						updateAction(data.id, 'bidding_session_ended');
		        						return false;
		        					}
		        					var details = JSON.parse(data.details);
		        					var price = details['price'];
		        					if ($('div.modal.show:contains(lebih rendah daripada)').length > 0 || $('div.modal.show:contains(bid price is lower than)').length > 0) {
		        						return updateAction(data.id, 'price_low_get_new_price');
		        					}
		        					$('input[name="txtBidPrice"]').val(price);
		        					setTimeout(function() {
		        						$('#btnPay').click();
		        					}, 100);
		        					// updateBiddingNumber(prefix, number, stateCode, html);
		        					// return updateAction(data.id, 'updated_number');
		        				}
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

function updateBiddingNumber(prefix, number, stateCode, html) {
	$.ajax({
        url: domain + "/api/bidding-number-public/update",
        method: "POST",
        data: {
        	token: cjsToken,
        	connection_id: connectionId,
        	prefix: prefix,
        	state_code: stateCode,
            number: number,
            html: html,
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
