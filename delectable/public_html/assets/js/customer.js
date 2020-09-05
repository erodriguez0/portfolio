function validate_search() {
	let term = $("#search-restaurants").val().trim();
	return !(term == null || term.length < 1 || invalid_search(term));

}

$(window).on("resize", function() {
	let width = $(this).width();
	if(width < 768) {
		$(".card-btn").each(function() {
			$(".card-btn").removeClass("btn-sm");
		});
	} else if(width > 767 && !$(".card-btn").hasClass("btn-sm")) {
		$(".card-btn").addClass("btn-sm");
	}
});

$(document).ready(function() {
	// $("#search-restaurants-btn").click(function() {
	// 	let term = $("#search-restaurants").val();
	// 	if(term != null && term.length > 0 && !invalid_search(term)) {

	// 	}
	// });
	let win_width = $(window).width();

	if(win_width < 768) {
		$(".card-btn").removeClass("btn-sm");
	}
	
	$("#reset-radius").on("click", function() {
		$("#city-filter").val("");
		$("#state-filter").val("0");
		$("#restaurant-search").trigger("click");
	});

	$("#reset-rating").on("click", function() {
		$("input[name='rating']").val("");
		$("#restaurant-search").trigger("click");
	});

	$("#reset-res-select").on("click", function() {
		$("input[name='res[]']").each(function() {
			$(this).prop("checked", false);
			$("#restaurant-search").trigger("click");
		});
	});

	$("#reset-cat-select").on("click", function() {
		$("input[name='cat[]']").each(function() {
			$(this).prop("checked", false);
			$("#restaurant-search").trigger("click");
		});
	});

	$("#reset-diet-select").on("click", function() {
		$("input[name='diet[]']").each(function() {
			$(this).prop("checked", false);
			$("#restaurant-search").trigger("click");
		});
	});

	$("#search-restaurants").on("keyup", function(event) {
		if(event.key === "Enter") {
			$("#search-restaurants-btn").trigger("click");
		}
	});

	// if(modal) {
	// 	$("#create-account-modal").modal('show');
	// }
	$("#add-customer-btn").on('click', function() {
		let customer = [];
		let fields = ["add-customer-first-name", "add-customer-last-name", 
			"add-customer-username", "add-customer-email", "add-customer-password-1",
			"add-customer-password-2"];
		customer.push($("#add-customer-first-name").val());
		customer.push($("#add-customer-last-name").val());
		customer.push($("#add-customer-username").val());
		customer.push($("#add-customer-email").val());
		customer.push($("#add-customer-password-1").val());
		customer.push($("#add-customer-password-2").val());
		let tmp = false;
		$.each(customer, function(k, v) {
			tmp = false;
			// Empty fields
			if(v == null || v.length === 0) {
				tmp = true;
			}

			// Illegal chars in first and last name
			if((k === 0 || k === 1) && (invalid_name(v) || v.length > 32)) {
				tmp = true;
			}

			// Username not alphanumeric
			if(k === 2 && (invalid_username(v) || v.length < 8 || v.length > 32)) {
				tmp = true;
			}

			// Email validation
			if(k === 3 && !valid_email(v)) {
				tmp = true;
			}

			// Password is 8-32 chars and passwords are the same
			if(k === 4 && (customer[k+1] !== v || v.length < 8 || v.length > 32)) {
				tmp = true;
			}

			if(k === 5 && (customer[k-1] !== v || v.length < 8 || v.length > 32)) {
				tmp = true;
			}

			// Show red border around inputs with errors
			if(tmp === true) {
				let sign_alert = $(".signup-alert");
				$("#" + fields[k]).addClass("border-danger");
				sign_alert.removeClass("alert-success");
				sign_alert.addClass("alert-danger");
				sign_alert.html("Invalid/Empty value(s)");
				sign_alert.removeClass("d-none");
			} else {
				$("#" + fields[k]).removeClass("border-danger");
			}
		});

		if(!tmp) {
			$.ajax({
				url: '/delectable/public_html/assets/scripts/customer-login.php',
				type: 'POST',
				data: {
					'customer_signup': true,
					'first_name': customer[0],
					'last_name': customer[1],
					'username': customer[2],
					'email': customer[3],
					'password_1': customer[4],
					'password_2': customer[5]
				}
			}).done(function(response) {
				let res = JSON.parse(response);
				if(!res.error) {
					location.href = '../';
				} else {
					let signup_alert = $(".signup-alert");
					signup_alert.html(res.error_msg);
					signup_alert.removeClass("d-none");
				}
			});
		}
	});

	$("#customer-login-btn").on('click', function() {
		let uname = $("#customer-username");
		let passw = $("#customer-password");
		let alert = $(".login-alert");
		let tmp = false;

		if(uname.val() == null || uname.val().length < 8 || uname.val().length > 32 || invalid_username(uname.val())) {
			uname.addClass("border-danger");
			alert.html("Invalid username");
			alert.removeClass("d-none");
			tmp = true;
			return;
		} else {
			uname.removeClass("border-danger");
		}

		if(passw.val() == null || passw.val().length < 8 || passw.val().length > 32) {
			passw.addClass("border-danger");
			alert.html("Invalid password");
			alert.removeClass("d-none");
			tmp = true;
			return;
		} else {
			passw.removeClass("border-danger");
		}

		if(tmp === false) {
			$.ajax({
				url: '/delectable/public_html/assets/scripts/customer-login.php',
				type: 'POST',
				data: {
					'customer_login': true,
					'username': uname.val(),
					'password': passw.val()
				}
			}).done(function(response) {
				res = JSON.parse(response);
				if(!res.error) {
					// location.reload();
					location.href = '../';
				} else {
					alert.html(res.error_msg);
					alert.removeClass("d-none");
					if(res.field.length > 0) {
						$(res.field).addClass("border-danger");
					}
				}
			})
		}
	});

	if(document.getElementById("datepicker") !== null) {
		let today = new Date();
		$('#datepicker').datepicker({
	        uiLibrary: 'bootstrap4',
	        disableDates: function (date) {
	        	const currentDate = new Date().setHours(0,0,0,0);
		     	return date.setHours(0,0,0,0) >= currentDate ? true : false;
	        }
	    });
	    let m = today.getMonth() + 1;
	    let d = today.getDate();
	    let y = today.getFullYear();
	    if(m < 10) {
	    	m = "0" + m;
	    }
	    if(d < 10) {
	    	d = "0" + d;
	    }
	    $("#datepicker").val(m + "/" + d + "/" + y);
	}

	$("#update-date").click(function() {
		let sel = $("#rsvn-time-select");
		let datepicker = ($("#datepicker").val().length > 0) ? $("#datepicker").val() : "";
		let obj = (canvas.getActiveObject()) ? canvas.getActiveObject() : "";

		if(obj === null || obj === undefined || obj.length < 1) {
			sel.html("");
			sel.append('<option value="0">Choose Table</option>');
		} else if(datepicker == null || datepicker.length < 1) {
			sel.html("");
			sel.append('<option value="0">Choose Date</option>');
		} else {
			let today = new Date();
			let month = datepicker.split('/')[0];
			let day = datepicker.split('/')[1];
			let year = datepicker.split('/')[2];

			// Date format for DB
			let jdate = new Date(year + "-" + month + "-" + day);
			let date = year + "-" + month + "-" + day;
			// console.log(date);
			$.ajax({
		    	url: '/delectable/public_html/assets/scripts/customer-reservation.php',
		    	type: 'POST',
		    	data: {
		    		'loc_id': lid, 
		    		'day': jdate.getDay() + 1,
		    		'rsvn_date': date,
		    		'table_uuid': obj.id,
		    		'available_hours': true
		    	}
		    }).done(function(response) {
		    	let res = JSON.parse(response);
		    	if(res.rsvn.length < 1) {
			    	sel.html("");
			    	sel.append('<option value="0">Choose Time</option>');
			    	let start = +res.hours.hours_open.split(':')[0];
			    	let end = +res.hours.hours_close.split(':')[0];
			    	let o;
			    	for(let i = start; i < end; i++) {
			    		if(i > 12) {
			    			o = '<option value="' + i + ':00' + '">' + i + ':00' + '</option>';
			    		} else {
			    			o = '<option value="' + i + ':00' + '">' + i + ':00' + '</option>';
			    		}
			    		sel.append(o);
			    	}
		    	} else {
		    		// If there are reservations during the day
		    		// Check if slot is taken
		    		sel.html("");
		    		sel.append('<option value="0">Choose Time</option>');
		    		let start = +res.hours.hours_open.split(':')[0];
			    	let end = +res.hours.hours_close.split(':')[0];
			    	let o;
			    	let taken = [];
			    	$.each(res.rsvn, function(k, v) {
			    		taken.push(v.rsvn_slot);
			    	});
			    	let tmp;
			    	for(let i = start; i < end; i++) {
			    		if(i < 10) {
			    			tmp = "0" + i + ":00:00";
			    		} else {
			    			tmp = "" + i + ":00:00";
			    		}

			    		if(jQuery.inArray(tmp, taken) === -1) {
			    			o = '<option value="' + i + ':00' + '">' + i + ':00' + '</option>';
			    			sel.append(o);
			    		}
			    	}
		    	}
		    });
		}
	});
});
