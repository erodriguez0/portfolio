function review_column(data) {
	let html = "";
	let name = data.cust_first_name + " " + data.cust_last_name;
	let rsvn_id = data.rsvn_id;
	let overall = data.review_rating;
	let food = data.review_food_rating;
	let service = data.review_service_rating;
	let review_date = formatDate(data.review_created);
	let comment = data.review_text;
	html += "<div class='col-12 review-wrap'>";
	html += "<div class='review-title d-flex align-items-center'>";
	html += "<span>" + name + " | </span>";
	html += "<span class='ml-1'>" + review_date + " | </span>";
	html += "<button class='btn btn-link-alt btn-sm table-link text-link text-capitalize order-row px-0 ml-1' value='" + rsvn_id + "' data-toggle='modal' data-target='.rsvn-modal'>View Details</button>";
	html += "</div>";
	html += "<div class='review-rating d-flex align-items-center pb-1'>";
	html += "<span class='mr-2'>Overall: </span>";
	for(let i = 0; i < 5; i++) {
		if(i < overall) {
			html += "<span class='fa fa-star star-checked'></span>";
		} else {
			html += "<span class='fa fa-star'></span>";
		}
	}
	html += "<span class='mx-2'>| Food: </span>";
	for(let i = 0; i < 5; i++) {
		if(i < food) {
			html += "<span class='fa fa-star star-checked'></span>";
		} else {
			html += "<span class='fa fa-star'></span>";
		}
	}
	html += "<span class='mx-2'>| Service: </span>";
	for(let i = 0; i < 5; i++) {
		if(i < service) {
			html += "<span class='fa fa-star star-checked'></span>";
		} else {
			html += "<span class='fa fa-star'></span>";
		}
	}
	html += "</div>";
	html += "<p>" + compact_paragraph(comment, 250) + "</p>";
	html += "</div>";
	$(".review-row").append(html);
}

$(document).ready(function() {
	$("#add-item-price").on("change", function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});

	$("#add-category-desc").keyup(function() {
		let v = $(this).val();
		let l = $(this).val().length;
		$(this).val($(this).val().substring(0, 255));
		if(l > 255) {
			$("#cat-text-counter").html(0);
		} else {
			$("#cat-text-counter").html(255 - l);
		}
	});

	$("#add-category-name").keyup(function() {
		let v = $(this).val();
		let l = $(this).val().length;
		$(this).val($(this).val().substring(0, 32));
		if(l > 255) {
			$("#cat-name-counter").html(0);
		} else {
			$("#cat-name-counter").html(32 - l);
		}
	});

	$("#add-item-description").keyup(function() {
		let v = $(this).val();
		let l = $(this).val().length;
		$(this).val($(this).val().substring(0, 255));
		if(l > 255) {
			$("#item-desc-counter").html(0);
		} else {
			$("#item-desc-counter").html(255 - l);
		}
	});

	$("#add-item-name").keyup(function() {
		let v = $(this).val();
		let l = $(this).val().length;
		$(this).val($(this).val().substring(0, 32));
		if(l > 255) {
			$("#add-item-name-counter").html(0);
		} else {
			$("#add-item-name-counter").html(32 - l);
		}
	});

	$("#add-category-btn").click(function() {
		let menu = $(".res-menu").html();
		let cat = $("#add-category-name").val();
		let desc = $("#add-category-desc").val();
		let sel = $("#add-item-category");
		
		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-menu.php',
			type: 'POST',
			data: {
				'add_menu_item_category': true,
				'cat_name': cat,
				'cat_desc': desc,
				'loc_id': lid
			}
		}).done(function(response) {
			let res = JSON.parse(response);
			if(!res.error) {
				cid = res.cat_id;
				let opt = "<option value='" + cid + "'>" + cat + "</option>";
				let cat_row = '<h1 class="h5 subheader-border mt-3 row mx-0">';
				cat_row += '<div class="col-9 pl-0">' + cat + '</div>';
				cat_row += '<div class="col-3 pr-0 text-right">';
				cat_row += '<small class="">';
				cat_row += '<button class="border-0 btn-link-alt table-link text-link px-0" value="' + cid + '">Edit</button> | ';
				cat_row += '<button class="border-0 btn-link-alt table-link text-link px-0" value="' + cid + '">Remove</a>';
				cat_row += '</small></div></h1>';
				cat_row += '<div class="word-break"><small><i>';
		        cat_row += desc;
		        cat_row += '</i></small></div>';
				$(".res-menu").append(cat_row);
				sel.append(opt);
				$(".add-item-alert").addClass("d-none");
				$(".add-cat-alert").removeClass("alert-danger");
				$(".add-cat-alert").addClass("alert-success");
				$(".add-cat-alert").html("Successfully added!");
				$(".add-cat-alert").removeClass("d-none");
				$("add-category-name").val("");
				$("add-category-desc").val("");
			} else {
				$(".add-item-alert").addClass("d-none");
				$(".add-cat-alert").removeClass("alert-success");
				$(".add-cat-alert").addClass("alert-danger");
				$(".add-cat-alert").html(res.error_msg);
				$(".add-cat-alert").removeClass("d-none");
			}
		});
	});

	$("#add-item-image").change(function() {
		let file = $(this).val();
		let name = this.files[0].name;
		if(name.length > 0) {
			$("#add-item-image-label").html(name);
		} else {
			$("#add-item-image-label").html("Choose image file");
		}
	});

	$("#add-item-btn").click(function() {
		let cid = +$("#add-item-category").val();
		let name = $("#add-item-name").val();
		let desc = $("#add-item-description").val();
		let img = $("#add-item-image").prop('files')[0];
		let price = +$("#add-item-price").val();
		let alert = $(".add-item-alert");

		// Is category a valid integer (ID)
		if(!Number.isInteger(+cid) || +cid == 0) {
			alert.html("Invalid category");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		// Is category number positive
		if(cid < 0) {
			alert.html("Invalid category");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		// Empty item name
		if(name.length < 1 || name.length > 32) {
			alert.html("Item name must be 1-32 characters");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		if(desc.length > 255) {
			alert.html("Item description must not be more than 255 characters");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
		}

		if(!has_letter(name)) {
			alert.html("Name must contain letters");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		// No special characters in name
		if(is_invalid_name(name)) {
			alert.html("Name can only contain letters, numbers, and dashes only");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		// Price is a number and valid format
		if(!jQuery.isNumeric(+price) || is_invalid_price(+price) 
			|| !is_valid_price_format(+price)) {
			alert.html("Invalid price");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		// Valid pricing
		if(+price < 0) {
			alert.html("Price can only be positive numbers");
			alert.removeClass("alert-success");
			alert.addClass("alert-danger");
			alert.removeClass("d-none");
			return;
		}

		// TODO:
		// Clear/empty form

		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-menu.php',
			type: 'POST',
			data: {
				'add_menu_item': true,
				'item_cat': cid,
				'loc_id': lid,
				'item_name': name,
				'item_desc': desc,
				'item_price': price
			}
		}).done(function(response) {
			res = JSON.parse(response);
			error = res.error;
			if(!error) {
				item_id = res.id;
				let item = "";
				item += "<div class='menu-item row mt-3 item-" + item_id + "'>";
				item += '<div class="col-2 d-flex justify-content-center align-items-center pr-0">';
				item += '<img src="https://via.placeholder.com/50" class="img-thumbnail rounded-0">';
				item += '</div>';
				item += '<div class="col-8 d-flex justify-content-left align-items-center">'
				item += name;
				item += '</div>';
				item += '<div class="col-2 d-flex justify-content-center align-items-center pl-0">';
				item += '<span class="text-success">';
				item += price;
				item += '</span>';
				item += '</div>';
				item += '<div class="col-12 d-flex justify-content-left align-items-center text-muted mt-3"><small><i>';
				item += desc;
				item += '</i></small></div>';
				item += '<div class="col-12 mt-3">';
				item += '<div class="btn-group special" role="group">';
				item += '<button type="button" class="btn btn-primary btn-sm" value="' + item_id + '">Edit Item</button>';
				item += '<button type="button" class="btn btn-primary btn-sm" value="' + item_id + '">Remove</button>';
				item += '</div>';
				item += '</div>';
				item += '</div>';

				$(".cat-" + cid).next().next().append(item);

				$("add-item-category").val("0");
				$("#add-item-name").val("");
				$("#add-item-desc").html("");
				$("#add-item-image-label").html("Choose image file");
				$("#add-item-price").val("");

				alert.html("Item added successfully");
				alert.removeClass("alert-danger");
				alert.addClass("alert-success");
				alert.removeClass("d-none");
			} else {
				alert.html(res.error_msg);
				alert.removeClass("alert-success");
				alert.addClass("alert-danger");
				alert.removeClass("d-none");
			}
		});
	});

	$('#create-emp-pay').on('change', function(){
    	$(this).val(parseFloat($(this).val()).toFixed(2));
	});

	$("#create-employee-account").on("click", function() {
		let fname  = $("#create-emp-first-name").val();
		let lname  = $("#create-emp-last-name").val();
		let uname  = $("#create-emp-username").val();
		let email  = $("#create-emp-email").val();
		let pass1  = $("#create-emp-password-1").val();
		let pass2  = $("#create-emp-password-2").val();
		let job    = $("#create-emp-job").val();
		let pay   = $("#create-emp-pay").val();
		let rate   = $("#create-emp-pay-rate").val();
		let access = ($("#create-emp-manager").prop("checked")) ? 1 : 0;
		let alert  = $(".create-emp-alert");
		let error  = false;
		let errMsg = "";
		let fields = ["#create-emp-first-name", "#create-emp-last-name", "#create-emp-username", "#create-emp-email", "#create-emp-password-1", "#create-emp-password-2"];

		// Fields cannot be left empty
		if(fname.length < 1 || lname.length < 1) {
			error = true;
			errMsg = "Name cannot be empty";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		if(email.length < 1) {
			error = true;
			errMsg = "Email cannot be empty";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		if(uname.length < 1) {
			error = true;
			errMsg = "Username cannot be empty";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		if(job.length < 1) {
			error = true;
			errMsg = "Job title cannot be empty";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		// Name/Username alphanumeric
		if(has_special_char(fname) || has_special_char(lname) || has_special_char(uname)) {
			error = true;
			errMsg = "Name/Username must be alphanumeric";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		// Passwords don't match
		if(pass1 != pass2) {
			error = true;
			errMsg = "Passwords don't match";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		// Password validation
		if(!password_check(pass1)) {
			error = true;
			errMsg = "Password must contain at least 1 special char. and be at least 8 chars. long";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		if(pay < 0) {
			error = true;
			errMsg = "Invalid wage/salary";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		let options = ["none", "hourly", "weekly", "biweekly", "semimonthly", "monthly", "semiannual", "annual"];

		if(jQuery.inArray(rate, options) == -1) {
			error = true;
			errMsg = "Invalid pay rate";
			alert.addClass("alert-danger");
			alert.html(errMsg);
			alert.removeClass("d-none");
			return;
		}

		if(error == false) {
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-employee.php',
				type: 'POST',
				data: {
					'emp-first-name': fname,
					'emp-last-name': lname,
					'emp-email': email,
					'emp-username': uname,
					'emp-password-1': pass1,
					'emp-password-2': pass2,
					'emp-manager': access,
					'emp-job': job,
					'emp-pay': pay,
					'emp-pay-rate': rate,
					'loc_id': lid,
					'create-employee-account': true
				}
			}).done(function(res) {
				response = JSON.parse(res);
				resError = response.error;
				console.log(response);
				if(resError == false) {
					if(!$("#manager-list-alert").hasClass("d-none")) {
						$("#manager-list-alert").addClass("d-none");
					}
					if(!$("#employee-list-alert").hasClass("d-none")) {
						$("#employee-list-alert").addClass("d-none");
					}
					data = response.data;
					empId = data.emp_id;
					// Build row to append to employee/manager table
					let row = "<tr>";
					row += "<td>" + fname + " " + lname + "</td>";
					row += "<td>" + uname + "</td>";
					row += "<td><a href='./edit/index.php?eid=" + empId + "' class='text-link table-link'>Profile</a></td>";
					row += "<td><input type='checkbox' name='' disabled='true'></td>";
					if(access == 1) {
						row += "<td><button class='btn-link-alt border-0 text-link table-link emp-revoke-manager' value='" + empId + "'>Revoke</button></td>";
					} else {
						row += "<td><button class='btn-link-alt border-0 text-link table-link emp-add-manager' value='" + empId + "'>Grant</button></td>";
					}
					row += "</tr>";
					if(access == 0) {
						$("#employee-list tbody").append(row);
					} else {
						$("#manager-list tbody").append(row);
					}
					if(alert.hasClass("alert-danger")) {
						alert.removeClass("alert-danger");
					}
					alert.addClass("alert-success");
					alert.html("Employee account created");
					alert.removeClass("d-none");
					// Clear input fields
					for(let f of fields) {
						$(f).val("");
					}
				} else {
					resErrorMsg = response.error_msg;
					if(alert.hasClass("alert-success")) {
						alert.removeClass("alert-success");
					}
					alert.addClass("alert-danger");
					alert.html(resErrorMsg);
					alert.removeClass("d-none");
				}
			});
		}
	});
	// Create Employee END

	// Revoke manager access
	$("#manager-list").each(function() {
		$(this).on('click', '.emp-revoke-manager', function(e) {
			let eid = $(this).val();
			let button = $(this);
			let row = $(this).parent().parent();
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-employee.php',
				type: 'POST',
				data: {
					'emp_id': eid,
					'loc_id': lid,
					'revoke_manager_access': true
				}
			}).done(function(res) {
				let success = JSON.parse(res);
				if(success) {
					if(!$("#manager-list-alert").hasClass("d-none")) {
						$("#manager-list-alert").addClass("d-none");
					}
					button.html("Grant");
					button.removeClass("emp-revoke-manager");
					button.addClass("emp-add-manager");
					$("#employee-list tbody").append(row);
					if($("#employee-list-alert").hasClass("alert-danger")) {
						"#employee-list-alert".removeClass("alert-danger");
					}
					$("#employee-list-alert").html("Employee updated");
					$("#employee-list-alert").addClass("alert-success");
					$("#employee-list-alert").removeClass("d-none");
				} else {
					if($("#employee-list-alert").hasClass("alert-success")) {
						$("#employee-list-alert").removeClass("alert-success");
					}
					$("#employee-list-alert").html("Error updating employee");
					$("#employee-list-alert").addClass("alert-danger");
					$("#employee-list-alert").removeClass("d-none");
				}
			});
		});
	});

	// Revoke manager access
	$("#employee-list").each(function() {
		$(this).on('click', '.emp-add-manager', function(e) {
			let eid = $(this).val();
			let button = $(this);
			let row = $(this).parent().parent();
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-employee.php',
				type: 'POST',
				data: {
					'emp_id': eid,
					'loc_id': lid,
					'grant_manager_access': true
				}
			}).done(function(res) {
				let success = JSON.parse(res);
				if(success) {
					if(!$("#employee-list-alert").hasClass("d-none")) {
						$("#employee-list-alert").addClass("d-none");
					}
					button.html("Revoke");
					button.removeClass("emp-add-manager");
					button.addClass("emp-revoke-manager");
					$("#manager-list tbody").append(row);
					if($("#manager-list-alert").hasClass("alert-danger")) {
						"#manager-list-alert".removeClass("alert-danger");
					}
					$("#manager-list-alert").html("Employee updated");
					$("#manager-list-alert").addClass("alert-success");
					$("#manager-list-alert").removeClass("d-none");
				} else {
					if($("#manager-list-alert").hasClass("alert-success")) {
						$("#manager-list-alert").removeClass("alert-success");
					}
					$("#manager-list-alert").html("Error updating employee");
					$("#manager-list-alert").addClass("alert-danger");
					$("#manager-list-alert").removeClass("d-none");
				}
			});
		});
	});

	// Fill reservation/order information and display
	$("#order-list").each(function() {
		$(this).on('click', '.order-row', function(e) {
			let rsvn_id = $(this).val();
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-order.php',
				type: 'POST',
				data: {
					'order_details': true,
					'rsvn_id': rsvn_id
				}
			}).done(function(response) {
				let res = JSON.parse(response);
				if(!res.error) {
					$(".assign-staff").val(rsvn_id);
					let emps = res.data.emp;
					let rsvn = res.data.rsvn;
					let address_1 = (rsvn.cust_address_1) ? rsvn.cust_address_1 : "Address: N/A";
					let address_2 = (rsvn.cust_address_2) ? rsvn.cust_address_2 : "";
					let city = (rsvn.cust_city) ? rsvn.cust_city + "," : "Address 2: N/A";
					let state = (rsvn.cust_state) ? rsvn.cust_state : "";
					let zip = (rsvn.cust_postal_code) ? rsvn.cust_postal_code : "";
					let phone = (rsvn.cust_phone) ? rsvn.cust_phone : "Phone: N/A";
					let rsvn_table = rsvn.table_number;
					$("#cust-name").html(rsvn.cust_first_name + " " + rsvn.cust_last_name);
					$("#cust-address-1").html(address_1 + " " + address_2);
					$("#cust-address-2").html(city + " " + state + " " + zip);
					$("#cust-phone").html(phone);
					$("#cust-email").html(rsvn.cust_email);
					$("#order-created").html(formatDate(rsvn.order_created));
					$("#rsvn-date").html(formatDate(rsvn.rsvn_date));
					$("#table-number").html("Table " + rsvn_table);
					$("#rsvn-staff tbody").html("");
					if(emps.length > 0) {
						$.each(emps, function(k, v) {
							let name = emps[k].emp_first_name + " " + emps[k].emp_last_name;
							let eid = emps[k].emp_id;
							let job = (emps[k].emp_job) ? emps[k].emp_job : "N/A";
							let row = "<tr><td>" + name + "</td>";
							row += "<td>" + job + "</td>";
							row += "<td><a class='btn-sm btn-link-alt text-link table-link py-0 rounded' href='../employees/?eid=" + eid + "'>Profile</a></td></tr>";
							$("#rsvn-staff tbody").append(row);
						});
					}
					if($(".rsvn-modal-title")) {
						$(".rsvn-modal-title").html("RSVN #" + rsvn_id);
					}
					$("#order-form").removeClass("d-none");
				} else {
					// Show 'soft' alert for error message
				}
			});
		});
	});


	// List employees for restaurant not assigned to reservation
	$(".staff-row").each(function() {
		$(this).on('click', '.assign-staff', function(e) {
			let rsvn_id = $(this).val();
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-order.php',
				type: 'POST',
				data: {
					'restaurant-employee-list': true,
					'loc_id': lid,
					'rsvn_id': rsvn_id
				}
			}).done(function(response) {
				let res = JSON.parse(response);
				$("#modal-assign-staff-table tbody").empty();
				$.each(res, function(k,v) {
					let name = res[k].emp_first_name + " " + res[k].emp_last_name;
					let eid = res[k].emp_id;
					let job = (res[k].emp_job) ? res[k].emp_job : "N/A";
					let row = "<tr>";
					row += "<td>" + name + "</td>";
					row += "<td>" + job + "</td>";
					row += "<td><input class='form-control staff-cb' type='checkbox' value='" + eid + "'></td>";
					row += "</tr>";
					$("#modal-assign-staff-table tbody").append(row);
				});
			});
		});
	});

	// Assign staff to reservation
	$("#assign-staff-modal").each(function() {
		$(this).on('click', '.assign-staff-submit', function(e) {
			let rsvn_id = $(".assign-staff").val();
			let emps = [];
			$(".staff-cb").each(function() {
				if($(this).prop('checked')) {
					emps.push({emp_id: $(this).val()});
				}
			})
			if(emps.length < 1) {
				return;
			}
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-order.php',
				type: 'POST',
				data: {
					'assign_staff': true,
					'rsvn_id': rsvn_id,
					'emps': emps
				}
			}).done(function(response) {
				let res = JSON.parse(response);
				if(!res.error) {
					let staff = $("#rsvn-staff tbody");
					let row;
					$(".staff-cb").each(function() {
						if($(this).prop('checked')) {
							let eid = $(this).val();
							let job = $(this).parent().prev().text();
							let name = $(this).parent().prev().prev().text();
							row = "<tr><td>" + name + "</td>";
							row += "<td>" + job + "</td>";
							row += "<td><a class='btn-sm btn-link-alt text-link table-link py-0 rounded' href='../employees/?eid=" + eid + "'>Profile</a></td></tr>";
							staff.append(row);
							$(this).parent().parent().remove();
						}
					});
					$("#assign-staff-modal").modal('hide');
				}
			});
		});
	});

	$("#view-reviews").click(function() {
		if(canvas.getActiveObject()) {
			const o = canvas.getActiveObject();
			let uuid = o.id;
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-reviews.php',
				type: 'POST',
				data: {
					'table_reviews': true,
					'table_uuid': uuid
				}
			}).done(function(response) {
				let res = JSON.parse(response);
				if(!res.error && res.data.length > 0) {
					$(".review-row").html("");
					$.each(res.data, function(k, v) {
						review_column(res.data[k]);
					});
				}
			});
		}
	});

	$("#order-list").each(function() {
		$(this).on("click", ".read-more", function() {
			$(this).prev().toggleClass("d-none");
			if($(this).html() == " Read Less") {
				$(this).html("... Read More");
			} else {
				$(this).html(" Read Less");
			}
		});
	});

	$(".comment-collapse").each(function() {
		$(this).click(function() {
			$(this).next().collapse('toggle');
		});
	});
});