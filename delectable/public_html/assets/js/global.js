function is_invalid_name(str) {
	return /[^a-zA-Z\-\d ]/.test(str);
}

function invalid_name(str) {
	return /[^a-zA-Z\d '".&-]/.test(str);
}

function invalid_username(str) {
	return /[^a-zA-Z\d]/.test(str);
}

function valid_email(email) {
    var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return re.test(String(email).toLowerCase());
}

function is_invalid_address(str) {
	return /[^a-zA-Z\-\d #,.]/.test(str);
}

function is_invalid_zip(str) {
	return /[^\d\-]/.test(str);
}

function is_invalid_phone(str) {
	return /[^\d\- ()]/.test(str);
}

function is_invalid_text(str) {
	return /[^a-zA-Z\-\d .!]/.test(str);
}

function is_invalid_price(num) {
	return /[^\d\.]/.test(num);
}

function is_valid_price_format(num) {
	return /^\d{0,8}(\.\d{0,2})?$/.test(num);
}

function has_special_char(str) {
	return /[^a-zA-Z\d]/.test(str);
}

function has_letter(str) {
	return /[a-zA-Z]/.test(str);
}

function has_number(str) {
	return /\d/.test(str);
}

function invalid_search(str) {
	return /[^a-zA-Z\d .\-\']/.test(str);
}

function password_check(str) {
	return (has_special_char(str) && has_letter(str) && has_number(str) && str.length >= 8);
}

function populate_state_select(el) {
    // let el = $("#" + div);
    var options = [ 'AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY' ];
    $.each(options, function(k, v) {
        el.append($('<option></option>').val(v).html(v));
    });
}

function selectElement(id, val) {
	let element = $("#" + id);
	element.val(val);
}

function selectStateElement(id, val) {
	let element = $("." + id);
	element.val(val);
}

function formatDate(str) {
	let y = str.split('-')[0];
	let m = str.split('-')[1];
	let d = str.split('-')[2].split(' ')[0];
	return m + "-" + d + "-" + y;
}

function compact_paragraph(text, limit) {
	let html = "";
	let sub = text.substr(0, limit);
	let hide = text.substr(limit, text.length - limit);
	html += sub;
	if(text.length > limit) {
		html += "<span class='d-none'>" + hide + "</span>";
		html += "<button class='btn btn-link-alt btn-sm table-link text-link text-capitalize read-more pt-0' style='padding-bottom: 4px;'>... Read More</button>";
	}
	return html;
}

function convertAvgToHexColor(num) {
	if(num >= 4.5) {
		return "#085E08";
	} else if(num >= 4.0 && num < 4.5) {
		return "#5B8835";
	} else if(num >= 3.5 && num < 4.0) {
		return "#95B329";
	} else if(num >= 3.0 && num < 3.5) {
		return "#B7A524";
	} else if(num >= 2.5 && num < 3.0) {
		return "#E39F1C";
	} else if(num >= 2.0 && num < 2.5) {
		return "#E37B1C";
	} else if(num >= 1.5 && num < 2.0) {
		return "#D94A17";
	} else if(num >= 1.0 && num < 1.5) {
		return "#c52e13";
	} else {
		return "#E31C23";
	}
}

$(window).on('load', function() {
	$("#cover").hide();
});

$(document).ready(function() {
	if(document.getElementById("NoJS") != null) {
		$("#NoJS").addClass("d-none");
	}

	if(document.getElementsByClassName("state-select") != null) {
		$(".state-select").each(function() {
			populate_state_select($(this));
		});

		if(typeof(state) !== 'undefined') {
			selectStateElement("state-select", state);
		}
	}

	if(document.getElementsByClassName("miles-filter")) {
		if(typeof(miles) !== 'undefined') {
			selectElement("mile-radius", miles);
		}
	}

	if(document.getElementById("sort-restaurants")) {
		if(typeof(sort) !== 'undefined') {
			selectElement("sort-restaurants", sort);
		}
	}

	if(document.getElementsByClassName("res-filter")) {
		if(typeof(res_select) !== 'undefined') {
			if(res_select.length > 0) {
				$.each(res_select, function(k, v) {
					$("input[name='res[]'][value='" + v + "'").prop("checked", true);
				});
			}
		}
	}

	if(document.getElementsByClassName("cat-filter")) {
		if(typeof(cat_select) !== 'undefined') {
			if(cat_select.length > 0) {
				$.each(cat_select, function(k, v) {
					$("input[name='cat[]'][value='" + v + "'").prop("checked", true);
				});
			}
		}
	}

	if(document.getElementsByClassName("rating-filter")) {
		if(typeof(rating) !== 'undefined') {
			$("input[name='rating'][value='" + rating + "']").prop("checked", true);
		}
	}

	if(document.getElementsByClassName("diet-filter")) {
		if(typeof(diet_select) !== 'undefined') {
			if(diet_select.length > 0) {
				$.each(diet_select, function(k, v) {
					$("input[name='diet[]'][value='" + v + "'").prop("checked", true);
				});
			}
		}
	}
});