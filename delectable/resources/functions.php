<?php

// GLOBAL FUNCTIONS

// Does string contain letters
function has_letter($string) {
    return preg_match( '/[a-zA-Z]/', $string );
}

// Does string contain numbers
function has_number($string) {
    return preg_match( '/\d/', $string );
}

// Does string contain special characters
function has_special_char($string) {
    return preg_match('/[^a-zA-Z\d]/', $string);
}

// Check if password contains number, 
// letter, special char, and length
function password_check($pass = '') {
	if(!has_number($pass) || !has_letter($pass) || !has_special_char($pass) || strlen($pass) < 8 || strlen($pass) > 128) { return false; }

	return true;
}

function sort_by_name_asc($a, $b) {
	$a = $a["res_name"];
	$b = $b["res_name"];

	if($a == $b) return 0;
	return ($a < $b) ? -1 : 1;
}

function sort_by_name_desc($a, $b) {
	$a = $a["res_name"];
	$b = $b["res_name"];

	if($a == $b) return 0;
	return ($a > $b) ? -1 : 1;
}

function invalid_search($term) {
	return preg_match('/[^a-zA-Z\d .\-\']/', $term);
}

// Checks if number is formatted as N,2
function is_currency($number) {
  return preg_match("/^-?[0-9]+(?:\.[0-9]{2})?$/", $number);
}

function is_invalid_name($string) {
	return preg_match('/[^a-zA-Z\-\d ]/', $string);
}

function is_invalid_address($string) {
	return preg_match('/[^a-zA-Z\-\d #,.]/', $string);
}

function is_invalid_zip($string) {
	return preg_match('/[^\d\-]/', $string);
}

function is_invalid_phone($string) {
	return preg_match('/[^\d\- ()]/', $string);
}

function is_invalid_text($string) {
	return preg_match('/[^a-zA-Z\-\d .!]/', $string);
}

function is_invalid_price($number) {
	return preg_match('/[^\d\.]/', $number);
}

function is_valid_price_format($number) {
	return preg_match('/^(0|[1-9]\d*)(\.\d{1,2})?$/', $number);
}

// Iterate through array of keys/fields
// and return an array using the field names
// as keys
function post_fields_to_array_keys($arr) {
	$tmp = array();
	foreach ($required as $field) {
		if(isset($_POST[$field]) && !empty($_POST[$field])) {
			$tmp[$field] = $_POST[$field];
		}
	}
	return $tmp;
}

// ADMIN DASHBOARD FUNCTIONS

function restaurant_list($conn) {
	$query = $conn->prepare("SELECT * FROM restaurant, location WHERE res_id = fk_res_id");
	try {
		$query->execute();
		return $query->fetchAll();
	} catch (PDOException $e) {
		
	}
}

function restaurant_info($conn, $id) {
	$query = $conn->prepare("SELECT * FROM restaurant, location WHERE res_id = fk_res_id AND loc_id = :id");
	$query->bindParam(":id", $id);

	try {
		$query->execute();
		return $query->fetch();
	} catch(PDOException $e) {

	}
}

function restaurant_managers($conn, $id) {
	$query = $conn->prepare("SELECT * FROM employee WHERE fk_loc_id = :id AND emp_manager = 1");
	$query->bindParam(":id", $id, PDO::PARAM_INT);

	try {
		$query->execute();
		return $query->fetchAll();
	} catch(PDOException $e) {

	}
}

function restaurant_employees($conn, $id) {
	$query = $conn->prepare("SELECT * FROM employee WHERE fk_loc_id = :id AND emp_manager = 0");
	$query->bindParam(":id", $id, PDO::PARAM_INT);

	try {
		$query->execute();
		return $query->fetchAll();
	} catch(PDOException $e) {

	}	
}

function employee_list($conn) {
	$query = $conn->prepare("SELECT * FROM employee");

	try {
		$query->execute();
		return $query->fetchAll();
	} catch (PDOException $e) {
		
	}
}

function employee_info($conn, $id) {
	$query = $conn->prepare("
		SELECT emp_first_name, emp_last_name, emp_email, emp_username, emp_last_login, emp_created, emp_address_1, emp_address_2, emp_city, emp_state, emp_postal_code, emp_phone, emp_status, res_name, loc_id, loc_address_1, loc_address_2, loc_city, loc_state, loc_postal_code, loc_phone 
		FROM employee
		LEFT JOIN location ON
		loc_id = fk_loc_id
		LEFT JOIN restaurant ON
		res_id = fk_res_id 
		WHERE emp_id = :eid
		");
	$query->bindParam(":eid", $id, PDO::PARAM_INT);

	try {
		$query->execute();
		return $query->fetch();
	} catch (PDOException $e) {
		
	}
}

function menu_item_categories($conn, $id) {
	$lid = $id;
	$sql = "SELECT item_cat_id, item_cat_name, item_cat_description FROM menu_item_category WHERE fk_loc_id = :lid";
	$query = $conn->prepare($sql);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	if($query->execute()) {
		return $query->fetchAll();
	}
}

function menu_items($conn, $id) {
	$cid = $id;
	$sql = "SELECT item_id, item_name, item_description, item_price FROM menu_item WHERE fk_item_cat_id = :cid";
	$query = $conn->prepare($sql);
	$query->bindParam(":cid", $cid, PDO::PARAM_INT);
	if($query->execute()) {
		return $query->fetchAll();
	}
}

function restaurant_search_filter($conn, $term, $city, $state, $res_list) {
	$count = 0;
	$list = "";
	$term = "%" . $term . "%";
	$sql = "SELECT res_id, res_name, res_slogan, res_description, loc_id, loc_city, loc_state FROM restaurant, location WHERE fk_res_id = res_id AND (res_name LIKE :term OR res_description LIKE :term)";

	if(strlen($city) > 0 && strlen($state) > 0) {
		$sql .= " AND loc_city = :city AND loc_state = :state";
		$count += 1;
	}

	if(!empty($res_list)) {
		$sql .= " AND res_id IN (";
		for($i = 0; $i < count($res_list); $i++) {
			if($i != count($res_list) - 1) {
				$sql .= ":res_" . $i . ", ";
			} else {
				$sql .= ":res_" . $i . ")";
			}
		}
		$count += 2;
	}

	$query = $conn->prepare($sql);
	$query->bindParam(":term", $term, PDO::PARAM_STR);
	switch ($count) {
		case 1:
			$query->bindParam(":city", $city, PDO::PARAM_STR);
			$query->bindParam(":state", $state, PDO::PARAM_STR);
			break;
		case 2:
			for($i = 0; $i < count($res_list); $i++) {
				$query->bindParam(":res_" . $i, $res_list[$i], PDO::PARAM_INT);
			}
			break;
		case 3:
			$query->bindParam(":city", $city, PDO::PARAM_STR);
			$query->bindParam(":state", $state, PDO::PARAM_STR);
			for($i = 0; $i < count($res_list); $i++) {
				$query->bindParam(":res_" . $i, $res_list[$i], PDO::PARAM_INT);
			}
			break;
		default:
			break;
	}

	if($query->execute()) {
		return $query->fetchAll();
	}
}

function restaurant_schedule($conn, $lid) {
	$query = $conn->prepare("SELECT * FROM location_hours WHERE fk_loc_id = :lid");
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	if($query->execute()) {
		return $query->fetchAll();
	}
}

function restaurant_pending_orders($conn, $lid) {
	// Limit orders between dates
	$date = date('Y-m-d');

	// Do not remove space at end of each $sql line
	$sql = "SELECT order_id, rsvn_id, rsvn_date, rsvn_slot, rsvn_status ";
	$sql .= "FROM `order` o, reservation r ";
	$sql .= "WHERE r.rsvn_id = o.fk_rsvn_id AND r.fk_loc_id = :lid AND r.rsvn_date >= :rdate ";
	$sql .= "ORDER BY rsvn_date ASC, rsvn_slot ASC";
	$query = $conn->prepare($sql);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->bindParam(":rdate", $date, PDO::PARAM_STR);
	if($query->execute()) {
		return $query->fetchAll();
	}
}

function restaurant_archived_orders($conn, $lid) {
	// Limit orders between dates
	$date = date('Y-m-d');

	// Do not remove space at end of each $sql line
	$sql = "SELECT order_id, rsvn_id, rsvn_date, rsvn_slot, rsvn_status ";
	$sql .= "FROM `order` o, reservation r ";
	$sql .= "WHERE r.rsvn_id = o.fk_rsvn_id AND r.fk_loc_id = :lid AND r.rsvn_date < :rdate ";
	$sql .= "ORDER BY rsvn_date DESC, rsvn_slot DESC";
	$query = $conn->prepare($sql);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->bindParam(":rdate", $date, PDO::PARAM_STR);
	if($query->execute()) {
		return $query->fetchAll();
	}
}

function restaurant_emp_info($conn, $eid, $lid) {
	$data = array("error" => false, "emp" => array(), "work" => array());
	$sql = <<<'EOT'
		SELECT
			emp_first_name, emp_last_name, emp_username, 
			emp_email, emp_status, emp_last_login, emp_address_1, 
			emp_address_2, emp_city, emp_state, emp_postal_code, 
			emp_phone, emp_job, emp_manager, emp_hire_date, emp_dismissed, 
			fk_loc_id
		FROM 
			employee
		WHERE 
			emp_id = :eid
	EOT;
	$query = $conn->prepare($sql);
	$query->bindParam(":eid", $eid, PDO::PARAM_INT);
	if($query->execute()) {
		$row = $query->fetch();
		if($row["fk_loc_id"] != $lid) {
			$data["error"] = true;
			return $data;
		}
		$data["emp"] = $row;
		$sql =<<<'EOT'
			SELECT DISTINCT 
				c.cust_first_name, c.cust_last_name, r.rsvn_id, r.rsvn_date, r.rsvn_slot, r.rsvn_status, s.fk_emp_id, w.*
			FROM 
				reservation r
			LEFT JOIN 
				review w 
			ON 	r.rsvn_id = w.fk_rsvn_id
			LEFT JOIN
				reservation_staff s
			ON	s.fk_rsvn_id = r.rsvn_id
			LEFT JOIN
				customer c
			ON c.cust_id = r.fk_cust_id
			WHERE
				s.fk_emp_id = :eid
			ORDER BY 
				r.rsvn_date ASC,
			    r.rsvn_slot ASC
		EOT;

		$query = $conn->prepare($sql);
		$query->bindParam(":eid", $eid, PDO::PARAM_INT);
		if($query->execute()) {
			$rows = $query->fetchAll();
			$data["work"] = $rows;
			return $data;
		}
		$data["emp"] = "";
		$data["error"] = true;
		return $data;
	}
}

function compact_paragraph($text, $limit) {
	$html = "";
	$sub = $text.substr(0, $limit);
	$hide = $text.substr($limit, strlen($text) - $limit);
	$html += $sub;
	if(strlen($text) > $limit) {
		$html += "<span class='d-none'>" + $hide + "</span>";
		$html += "<button class='btn btn-link-alt btn-sm table-link text-link text-capitalize read-more pt-0' style='padding-bottom: 4px;'>... Read More</button>";
	}
	echo $html;
}

function rating_to_stars($rating) {
	$html = "";
	for($i = 0; $i < 5; $i++) {
		if($i < $rating) {
			$html .= '<span class="fas fa-star star-checked"></span>';
		} else {
			$html .= '<span class="fas fa-star"></span>';
		}
	}
	echo $html;
}

function convert_state_abbr($str) {
	$us_state_abbrevs_names = array(
		'AL'=>'ALABAMA',
		'AK'=>'ALASKA',
		'AS'=>'AMERICAN SAMOA',
		'AZ'=>'ARIZONA',
		'AR'=>'ARKANSAS',
		'CA'=>'CALIFORNIA',
		'CO'=>'COLORADO',
		'CT'=>'CONNECTICUT',
		'DE'=>'DELAWARE',
		'DC'=>'DISTRICT OF COLUMBIA',
		'FM'=>'FEDERATED STATES OF MICRONESIA',
		'FL'=>'FLORIDA',
		'GA'=>'GEORGIA',
		'GU'=>'GUAM GU',
		'HI'=>'HAWAII',
		'ID'=>'IDAHO',
		'IL'=>'ILLINOIS',
		'IN'=>'INDIANA',
		'IA'=>'IOWA',
		'KS'=>'KANSAS',
		'KY'=>'KENTUCKY',
		'LA'=>'LOUISIANA',
		'ME'=>'MAINE',
		'MH'=>'MARSHALL ISLANDS',
		'MD'=>'MARYLAND',
		'MA'=>'MASSACHUSETTS',
		'MI'=>'MICHIGAN',
		'MN'=>'MINNESOTA',
		'MS'=>'MISSISSIPPI',
		'MO'=>'MISSOURI',
		'MT'=>'MONTANA',
		'NE'=>'NEBRASKA',
		'NV'=>'NEVADA',
		'NH'=>'NEW HAMPSHIRE',
		'NJ'=>'NEW JERSEY',
		'NM'=>'NEW MEXICO',
		'NY'=>'NEW YORK',
		'NC'=>'NORTH CAROLINA',
		'ND'=>'NORTH DAKOTA',
		'MP'=>'NORTHERN MARIANA ISLANDS',
		'OH'=>'OHIO',
		'OK'=>'OKLAHOMA',
		'OR'=>'OREGON',
		'PW'=>'PALAU',
		'PA'=>'PENNSYLVANIA',
		'PR'=>'PUERTO RICO',
		'RI'=>'RHODE ISLAND',
		'SC'=>'SOUTH CAROLINA',
		'SD'=>'SOUTH DAKOTA',
		'TN'=>'TENNESSEE',
		'TX'=>'TEXAS',
		'UT'=>'UTAH',
		'VT'=>'VERMONT',
		'VI'=>'VIRGIN ISLANDS',
		'VA'=>'VIRGINIA',
		'WA'=>'WASHINGTON',
		'WV'=>'WEST VIRGINIA',
		'WI'=>'WISCONSIN',
		'WY'=>'WYOMING',
		'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
		'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
		'AP'=>'ARMED FORCES PACIFIC'
	);

	return $us_state_abbrevs_names[$str];
}

function convertAvgToHexColorClass($num) {
	if($num >= 4.5) {
		return "legend-9";
	} else if($num >= 4.0 && $num < 4.5) {
		return "legend-8";
	} else if($num >= 3.5 && $num < 4.0) {
		return "legend-7";
	} else if($num >= 3.0 && $num < 3.5) {
		return "legend-6";
	} else if($num >= 2.5 && $num < 3.0) {
		return "legend-5";
	} else if($num >= 2.0 && $num < 2.5) {
		return "legend-4";
	} else if($num >= 1.5 && $num < 2.0) {
		return "legend-3";
	} else if($num >= 1.0 && $num < 1.5) {
		return "legend-2";
	} else {
		return "legend-1";
	}
}
?>