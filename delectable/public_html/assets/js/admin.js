$(document).ready(function() {
	if(document.referrer != '') {
		// $("#create-account-modal").modal('show');
	}

	$('#new-loc-phone').keyup(function(){
	    $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3'));
	});


	$("#new-restaurant-btn").click(function() {
		let name = $("#new-res-name").val();
		let slogan = $("#new-res-slogan").val();
		let description = $("#new-res-description").val();
		let address_1 = $("#new-loc-address-1").val();
		let address_2 = $("#new-loc-address-2").val();
		let phone = $("#new-loc-phone").val();
		let postal = $("#new-loc-zip").val();
		let city = $("#new-loc-city").val();
		let state = $("#new-loc-state").val();
		let alert = $(".new-res-alert");

		// CHECK LENGTH OF STRINGS

		if(name.length == 0 || name.length > 128) {
			alert.html("Name must be between 1-128 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(slogan.length > 128) {
			alert.html("Slogan must be between 0-128 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(description.length > 255) {
			alert.html("Description must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(address_1.length == 0 || address_1.length > 64) {
			alert.html("Address must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(address_2.length > 64) {
			alert.html("Name must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(phone.length == 0 || phone.length > 64) {
			alert.html("Name must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(city.length == 0 || city.length > 64) {
			alert.html("Name must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(state.length == 0 || state.length > 64) {
			alert.html("Name must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(postal.length == 0 || postal.length > 64) {
			alert.html("Name must be between 0-255 characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		// USE VALIDATION FUNCTIONS FROM GLOBAL.JS
		
		if(is_invalid_name(name)) {
			alert.html("Name can only contain letters, numbers, and dashes");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(is_invalid_text(slogan)) {
			alert.html("Slogan can only contain letters, numbers, and dashes");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(is_invalid_text(description)) {
			alert.html("Description contains invalid characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(is_invalid_address(address_1)) {
			alert.html("Address contains invalid characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(address_2.length > 0) {
			if(is_invalid_address(address_2)) {
				alert.html("Apt/Ste contains invalid characters");
				alert.addClass("alert-danger");
				alert.removeClass("alert-success");
				alert.removeClass("d-none");
				return;
			}
		}

		if(is_invalid_name(city)) {
			alert.html("City contains invalid characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(is_invalid_name(state)) {
			alert.html("State contains invalid characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		if(is_invalid_zip(postal)) {
			alert.html("Zip code contains invalid characters");
			alert.addClass("alert-danger");
			alert.removeClass("alert-success");
			alert.removeClass("d-none");
			return;
		}

		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {
				'res_name': name,
				'res_slogan': slogan,
				'res_description': description,
				'loc_address_1': address_1,
				'loc_address_2': address_2,
				'loc_phone': phone,
				'loc_city': city,
				'loc_state': state,
				'loc_postal_code': postal,
				'add_new_restaurant': true
			}
		}).done(function(response) {
			res = JSON.parse(response);
			if(!res.error) {
				data = res.data;
				let row = '<tr>';
	            row += '<td>' + data.name + '</td>';
	            row += '<td>' + data.address_1 + ' ' + data.address_2 + ' ';
	            row += data.city + ' ' + data.state + ' ' + data.postal + '</td>';
	            row += '<td>';
	            row += '<a class="btn btn-sm btn-primary"';
	            row += ' href="./edit/index.php?lid=' + data.loc_id + '">Edit</a>';
	            row += '</td>';
	            row += '</tr>';

		        $("#new-restaurant-modal").modal('hide');
		        $(".res-list tbody").append(row);
			} else {
				alert.html(res.error_msg);
				alert.addClass("alert-danger");
				alert.removeClass("alert-success");
				alert.removeClass("d-none");
			}
		});
	});

	$("#emp-table-search").keyup(function() {
        var value = $(this).val().toLowerCase().trim();

        $("table").children().find("tr").each(function(index) {
            var name = $(this).find("td").first().text().toLowerCase().trim();
            var uname = $(this).find("td").first().next().text().toLowerCase().trim();
            var email = $(this).find("td").first().next().next().text().toLowerCase().trim();
            var status = $(this).find("td").first().next().next().next().text().toLowerCase().trim();
            $(this).toggle(name.indexOf(value) !== -1 || uname.indexOf(value) !== -1 || email.indexOf(value) !== -1 || status.indexOf(value) !== -1);
        });
    });

    $(".emp-profile-modal-btn").each(function() {
    	$(this).on('click', function() {
    		var eid = $(this).attr("name");
    		$.ajax({
    			url: '/delectable/public_html/assets/scripts/employee-edit.php',
				type: 'POST',
				data: {
					'eid': eid,
					'employee_profile': true
				}
    		}).done(function(res) {
    			var emp = JSON.parse(res);
    			var emp_address = "";
    			var work_address = "";
    			var name = emp['emp_first_name'] + ' ' + emp['emp_last_name']
    			var phone = (emp['emp_phone'] == null) ? "N/A" : emp['emp_phone'];
    			var work_phone = (emp['loc_phone'] == null) ? "N/A" : emp['loc_phone'];
    			var workplace = (emp['res_name'] == null) ? "N/A" : emp['res_name'];
    			var status = (emp['emp_status'] == 1) ? "Active" : "Suspended";

    			if(emp['emp_address_1'] == null || emp['emp_address_2'] == null || emp['emp_city'] == null || emp['emp_state'] == null || emp['emp_postal_code'] == null) {
    				emp_address = "N/A";
    			} else {
	    			emp_address = emp['emp_address_1'].concat(' ', emp['emp_address_2'], '<br>', emp['emp_city'], ' ', emp['emp_state'], ', ', emp['emp_postal_code']);
    			}

    			if(emp['loc_address_1'] == null || emp['loc_address_2'] == null || emp['loc_city'] == null || emp['loc_state'] == null || emp['loc_postal_code'] == null) {
    				work_address = "N/A";
    			} else {
	    			work_address = emp['loc_address_1'].concat(' ', emp['loc_address_2'], '<br>', emp['loc_city'], ' ', emp['loc_state'], ', ', emp['loc_postal_code']);
    			}

    			$("#profile-name").html(name);
    			$("#profile-username").html(emp['emp_username']);
    			$("#profile-email").html(emp['emp_email']);
    			$("#profile-phone").html(phone);
    			$("#profile-address").html(emp_address);
    			$("#profile-status").html(status);
    			$("#profile-registered").html(emp['emp_created']);
    			$("#profile-login").html(emp['emp_last_login']);
    			$("#profile-updated").html(emp['emp_updated']);
    			$("#profile-workplace").html(workplace);
    			$("#profile-work-phone").html(work_phone);
    			$("#profile-work-address").html(work_address);
    		});
    	});
    });

    // Employee Edit
    $("#emp-update-btn").on('click', function(e) {
    	var fname = $("#edit-emp-fname").val();
    	var lname = $("#edit-emp-lname").val();
    	var uname = $("#edit-emp-uname").val();
    	var email = $("#edit-emp-email").val();
    	var address1 = $("#edit-emp-add1").val();
    	var address2 = $("#edit-emp-add2").val();
    	var phone = $("#edit-emp-phone").val();
    	var city = $("#edit-emp-city").val();
    	var state = $("#edit-emp-state").val();
    	var zip = $("#edit-emp-postal-code").val();
    	var status = ($("#edit-emp-status").is(":checked")) ? 1 : 0;

    	$.ajax({
    		url: '/delectable/public_html/assets/scripts/employee-edit.php',
			type: 'POST',
			data: {
				'eid': eid,
				'fname': fname,
				'lname': lname,
				'uname': uname,
				'email': email,
				'address1': address1,
				'address2': address2,
				'phone': phone,
				'city': city,
				'state': state,
				'zip': zip,
				'status': status,
				'employee_update': true
			}
    	}).done(function(res) {
    		var res = JSON.parse(res);
    		if(!res.error) {
    			$(".emp-update-alert").html("Successfully updated");
    			$(".emp-update-alert").removeClass("alert-danger");
    			$(".emp-update-alert").addClass("alert-success");
    			$(".emp-update-alert").removeClass("d-none");
    		} else {
    			$(".emp-update-alert").html(res.error_msg);
    			$(".emp-update-alert").removeClass("alert-success");
    			$(".emp-update-alert").addClass("alert-danger");
    			$(".emp-update-alert").removeClass("d-none");
    		}
    	});
    });

    $("#edit-emp-status").on('click', function() {
    	var check = ($("#edit-emp-status").is(":checked")) ? 1 : 0;
    	if(check) {
    		$("#emp-status-label").html("Active");
    	} else {
    		$("#emp-status-label").html("Suspended");
    	}
    });

    $("#toggle-res-search").on('click', function() {
    	$("#show-res-search-form").slideToggle();
    });

    $("#res-table-search").keyup(function() {
        var value = $(this).val().toLowerCase().trim();

        $("table").children().find("tr").each(function(index) {
            var name = $(this).find("td").first().text().toLowerCase().trim();
            var address = $(this).find("td").first().next().text().toLowerCase().trim();
            $(this).toggle(name.indexOf(value) !== -1 || address.indexOf(value) !== -1);
        });
    });

	$("#res-update-btn").on('click', function(e) {
		e.preventDefault();
		var name = $("#res-name").val();
		var slogan = $("#res-slogan").val();
		var desc = $("#res-desc").val();

		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {
				'name': name,
				'slogan': slogan,
				'desc': desc,
				'rid': rid,
				'res_update': true
			}
		}).done(function() {
			$(".res-update-alert").removeClass("d-none");
		});
	});

	$(".loc-update-btn").on('click', function(e) {
		e.preventDefault();
		var add1 = $("[name='loc-address-1']").val();
		var add2 = $("[name='loc-address-2']").val();
		var phone = $("[name='loc-phone']").val();
		var city = $("[name='loc-city']").val();
		var state = $("[name='loc-state']").val();
		var zip = $("[name='loc-postal-code']").val();
		
		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {
				'lid': lid,
				'add1': add1,
				'add2': add2,
				'phone': phone,
				'city': city,
				'state': state,
				'zip': zip,
				'loc_update': true
			}
		}).done(function() {
			$(".loc-update-alert").removeClass("d-none");
		});
	});

	// Search for employees by username
	$(".emp-search-btn").on('click', function(e) {
		e.preventDefault();
		var input = $("#emp-search").val();

		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {'employee_search': input}
		}).done(function(res) {
			$("#emp-table tbody").empty();
			var par = JSON.parse(res);
			$.each(par, function(k, v) {
				var row = '<tr><td>' + v.emp_first_name + ' ' + v.emp_last_name + '</td>'
				+ '<td>' + v.emp_username + '</td>'
				+ '<td><button class="btn btn-primary btn-sm add-man-btn" value="' 
				+ v.emp_id + '">Add</button></td></tr>';
				$("#emp-table tbody").append(row);
			});
		});
	});

	$(".emp-search-add-btn").on('click', function(e) {
		e.preventDefault();
		var input = $("#emp-search-input").val();

		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {
				'employee_add_search': true,
				'search_input': input
			}
		}).done(function(res) {
			var data = JSON.parse(res);
			$("#emp-list-table tbody").empty();
			$.each(data, function(k, v) {
				var row = '<tr><td>' + v.emp_first_name + ' ' + v.emp_last_name + '</td>'
				+ '<td>' + v.emp_username + '</td>'
				+ '<td><button class="btn btn-primary btn-sm add-emp-btn" value="' 
				+ v.emp_id + '">Add</button></td></tr>';

				$("#emp-list-table tbody").append(row);
			});
		});
	});

	$(".save-employee-btn").on('click', function(e) {
		e.preventDefault();
		var id = $(".add-emp-btn").val();
		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {
				'restaurant_add_employee': true,
				'emp_id': id,
				'loc_id': rid
			}
		}).done(function(res) {
			var data = JSON.parse(res);
			if(!data.error) {
				let emp = data.data;
				let name = emp.emp_first_name + ' ' + emp.emp_last_name;
				let uname = emp.emp_username;
				let emp_id = emp.emp_id;
				let url = '/delectable/public_html/admin/employees/edit/index.php?eid=' + emp_id;
				let link = '<a class="btn btn-primary btn-sm" href="' + url + '">Info</a>';
				let remBtn = '<button type="button" class="btn btn-primary btn-sm remove-employee-btn" value="' + emp_id + '">X</button>';
				var row = '<tr id="row-' + emp_id + '"><td><span>' + name + '</span></td><td><span>' + uname + '</span></td><td>' + link + '</td><td>' + remBtn + '</td></tr>';
				$("#emp-list-table tbody").empty();
				$("#emp-list tbody").append(row);
				$("#emp-list-modal").modal('toggle');
				let alert = $(".employee-update-alert");
				alert.html("");
				alert.html("Employee added");
				alert.removeClass("d-none");
			}
		});
	})

	// Update selected employee to have manager access
	$(".save-manager-btn").on('click', function(e) {
		e.preventDefault();
		var eid = $(".add-man-btn").val();
		$.ajax({
			url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
			type: 'POST',
			data: {
				'add_manager': true,
				'emp_id': eid, 
				'loc_id': lid
			}
		}).done(function(res) {
			var data = JSON.parse(res);
			var emp = data.data;
			if(!data.error) {
				let name = emp.emp_first_name + ' ' + emp.emp_last_name;
				let uname = emp.emp_username;
				let emp_id = emp.emp_id;
				let url = '/delectable/public_html/admin/employees/edit/index.php?eid=' + emp_id;
				let link = '<a class="btn btn-primary btn-sm" href="' + url + '">Info</a>';
				let remBtn = '<button type="button" class="btn btn-primary btn-sm remove-manager-btn" value="' + emp_id + '">X</button>';
				var row = '<tr id="row-' + emp_id + '"><td><span>' + name + '</span></td><td><span>' + uname + '</span></td><td>' + link + '</td><td>' + remBtn + '</td></tr>';
				$("#row-" + emp_id).remove();
				$("#emp-table tbody").empty();
				$("#manager-table tbody").append(row);
				$("#list-modal").modal('toggle');
				let alert = $(".manager-update-alert");
				alert.html("");
				alert.html("Manager added");
				alert.removeClass("d-none");
			}
		});
	});


	$("#manager-table").each(function() {
		$(this).on('click', '.remove-manager-btn', function(e) {
			e.preventDefault();
			var eid = $(this).val();
			var row = $(this).parent().parent();
			$(this).removeClass("remove-manager-btn");
			$(this).addClass("remove-employee-btn");
			var man_table = $("#emp-table tbody");
			var table = $("#emp-list tbody");
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
				type: 'POST',
				data: {'remove_manager': eid}
			}).done(function() {
				table.append(row);
				man_table.remove(row);
				let alert = $(".manager-update-alert");
				alert.html("");
				alert.html("Manager removed");
				alert.removeClass("d-none");
			});
		});
	});

	$("#emp-list").each(function() {
		$(this).on('click', '.remove-employee-btn', function(e) {
			e.preventDefault();
			var eid = $(this).val();
			var row = $(this).parent().parent();
			var table = $("#emp-list tbody");
			row.remove();
			$.ajax({
				url: '/delectable/public_html/assets/scripts/restaurant-edit.php',
				type: 'POST',
				data: {
					'remove_employee': true,
					'emp_id': eid
				}
			}).done(function(res) {
				let data = JSON.parse(res);
				if(!data.error) {
					table.remove(row);
					let alert = $(".employee-update-alert");
					alert.html("Employee removed");
					alert.removeClass("d-none");
				}
			});
		});
	});
});