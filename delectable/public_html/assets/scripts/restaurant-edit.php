<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['add_new_restaurant'])) {
	$name = (isset($_POST['res_name'])) ? trim($_POST['res_name']) : "";
	$slogan = (isset($_POST['res_slogan'])) ? trim($_POST['res_slogan']) : "";
	$description = (isset($_POST['res_description'])) ? trim($_POST['res_description']) : "";
	$loc_address_1 = (isset($_POST['loc_address_1'])) ? trim($_POST['loc_address_1']) : "";
	$loc_address_2 = (isset($_POST['loc_address_2'])) ? trim($_POST['loc_address_2']) : "";
	$loc_phone = (isset($_POST['loc_phone'])) ? trim($_POST['loc_phone']) : "";
	$loc_postal_code = (isset($_POST['loc_postal_code'])) ? trim($_POST['loc_postal_code']) : "";
	$loc_city = (isset($_POST['loc_city'])) ? trim($_POST['loc_city']) : "";
	$loc_state = (isset($_POST['loc_state'])) ? trim($_POST['loc_state']) : "";
	$res = array("error" => false, "error_msg" => "", "data" => array());
	// $required = array("name", "loc_address_1", "loc_phone", "loc_postal_code", "loc_city", "loc_state");

	// foreach($required as $k => $v) {
	// 	if(!isset($_POST[$v]) || empty($_POST[$v])) {
	// 		$res["error"] = true;
	// 		break;
	// 	}
	// }

	// EMPTY/INVALID REQUIRED FIELDS

	if($res["error"]) {
		$res["error_msg"] = "Invalid/empty required input";
		echo json_encode($res); exit();
	}

	// STRING LENGTH VALIDATION

	if(strlen($name) > 128) {
		$res["error_msg"] = "Restaurant name too long";
		echo json_encode($res); exit();
	}

	if(strlen($slogan) > 128) {
		$res["error_msg"] = "Restaurant name too long";
		echo json_encode($res); exit();
	}

	if(strlen($description) > 255) {
		$res["error_msg"] = "Restaurant name too long";
		echo json_encode($res); exit();
	}

	if(strlen($loc_address_1) > 64) {
		$res["error_msg"] = "Address is too long";
		echo json_encode($res); exit();
	}

	if(strlen($loc_address_2) > 64) {
		$res["error_msg"] = "Apt/Ste is too long";
		echo json_encode($res); exit();
	}

	if(strlen($loc_phone) > 64) {
		$res["error_msg"] = "Phone number is too long";
		echo json_encode($res); exit();
	}

	if(strlen($loc_postal_code) > 64) {
		$res["error_msg"] = "Postal code is too long";
		echo json_encode($res); exit();
	}

	if(strlen($loc_city) > 64) {
		$res["error_msg"] = "City name is too long";
		echo json_encode($res); exit();
	}

	if(strlen($loc_state) > 64) {
		$res["error_msg"] = "State name is too long";
		echo json_encode($res); exit();
	}

	// INPUT VALIDATION FUNCTIONS

	if(is_invalid_name($name) > 64) {
		$res["error_msg"] = "Invalid restaurant name";
		echo json_encode($res); exit();
	}

	if(strlen($slogan) > 0) {
		if(is_invalid_text($slogan)) {
			$res["error_msg"] = "Invalid restaurant slogan";
			echo json_encode($res); exit();
		}
	}

	if(strlen($description) > 0) {
		if(is_invalid_text($description)) {
			$res["error_msg"] = "Invalid restaurant name";
			echo json_encode($res); exit();
		}
	}

	if(is_invalid_address($loc_address_1)) {
		$res["error_msg"] = "Invalid characters in address";
		echo json_encode($res); exit();
	}

	if(strlen($loc_address_2) > 0) {
		if(is_invalid_address($loc_address_2)) {
			$res["error_msg"] = "Invalid characters in apt/ste";
			echo json_encode($res); exit();
		}
	}

	if(is_invalid_phone($loc_phone)) {
		$res["error_msg"] = "Invalid characters in phone number";
		echo json_encode($res); exit();
	}

	if(is_invalid_name($loc_city)) {
		$res["error_msg"] = "Invalid characters in city name";
		echo json_encode($res); exit();
	}

	if(is_invalid_name($loc_state)) {
		$res["error_msg"] = "Invalid characters in state name";
		echo json_encode($res); exit();
	}

	if(is_invalid_zip($loc_postal_code)) {
		$res["error_msg"] = "Invalid characters in postal code";
		echo json_encode($res); exit();
	}

	if(!$res["error"]) {
		$query = $conn->prepare("INSERT INTO restaurant (res_name, res_slogan, res_description) VALUES (:name, :slogan, :description)");
		$query->bindParam(":name", $name, PDO::PARAM_STR);
		$query->bindParam(":slogan", $slogan, PDO::PARAM_STR);
		$query->bindParam(":description", $description, PDO::PARAM_STR);

		if($query->execute()) {
			$new_id = $conn->lastInsertId();
			$query = $conn->prepare("INSERT INTO location (loc_address_1, loc_address_2, loc_city, loc_state, loc_postal_code, loc_phone, fk_res_id) VALUES (:add1, :add2, :city, :state, :zip, :phone, :rid)");
			$query->bindParam(":add1", $loc_address_1, PDO::PARAM_STR);
			$query->bindParam(":add2", $loc_address_2, PDO::PARAM_STR);
			$query->bindParam(":city", $loc_city, PDO::PARAM_STR);
			$query->bindParam(":state", $loc_state, PDO::PARAM_STR);
			$query->bindParam(":zip", $loc_postal_code, PDO::PARAM_STR);
			$query->bindParam(":phone", $loc_phone, PDO::PARAM_STR);
			$query->bindParam(":rid", $new_id, PDO::PARAM_INT);

			if($query->execute()) {
				$id = $conn->lastInsertId();
				$res["data"]["loc_id"] = $id;
				$res["data"]["address_1"] = htmlspecialchars($loc_address_1);
				$res["data"]["address_2"] = htmlspecialchars($loc_address_2);
				$res["data"]["city"] = htmlspecialchars($loc_city);
				$res["data"]["state"] = htmlspecialchars($loc_state);
				$res["data"]["postal"] = htmlspecialchars($loc_postal_code);
				$res["data"]["name"] = htmlspecialchars($name);
				$res["data"]["slogan"] = htmlspecialchars($slogan);
				$res["data"]["description"] = htmlspecialchars($description);
				echo json_encode($res); exit();
			} else {
				$res["error"] = true;
				$res["error_msg"] = "Error: Verify database integrity";
				echo json_encode($res); exit();
			}

		} else {
			$res["error"] = true;
			$res["error_msg"] = "Something went wrong. Cannot create restaurant";
			echo json_encode($res); exit();
		}
	} else {
		$res["error_msg"] = "Cannot create restaurant at this time";
		echo json_encode($res); exit();
	}
}

if(isset($_POST['res_update'])) {
	$name = $_POST['name'];
	$slogan = $_POST['slogan'];
	$description = $_POST['desc'];
	$rid = $_POST['rid'];

	$query = $conn->prepare("UPDATE restaurant SET res_name = :name, res_slogan = :slogan, res_description = :description WHERE res_id = :rid");
	$query->bindParam(":name", $name, PDO::PARAM_STR);
	$query->bindParam(":slogan", $slogan, PDO::PARAM_STR);
	$query->bindParam(":description", $description, PDO::PARAM_STR);
	$query->bindParam(":rid", $rid, PDO::PARAM_INT);

	try {
		$query->execute();
	} catch(PDOException $e) {

	}
}

if(isset($_POST['loc_update'])) {
	$lid = $_POST['lid'];
	$add1 = $_POST['add1'];
	$add2 = $_POST['add2'];
	$phone = $_POST['phone'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];

	$query = $conn->prepare("UPDATE location SET loc_address_1 = :add1, loc_address_2 = :add2, loc_phone = :phone, loc_city = :city, loc_state = :state, loc_postal_code = :zip WHERE loc_id = :lid");
	$query->bindParam(":add1", $add1, PDO::PARAM_STR);
	$query->bindParam(":add2", $add2, PDO::PARAM_STR);
	$query->bindParam(":phone", $phone, PDO::PARAM_STR);
	$query->bindParam(":city", $city, PDO::PARAM_STR);
	$query->bindParam(":state", $state, PDO::PARAM_STR);
	$query->bindParam(":zip", $zip, PDO::PARAM_STR);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);

	try {
		$query->execute();
	} catch(PDOException $e) {

	}
}

if(isset($_POST['employee_search'])) {
	$input = $_POST['employee_search'];

	$query = $conn->prepare("SELECT * FROM employee WHERE emp_username = :input AND emp_manager = 0 AND emp_status = 1");
	$query->bindParam(":input", $input, PDO::PARAM_STR);
	try {
		$query->execute();
		echo json_encode($query->fetchAll());
	} catch(PDOException $e) {
		return $input;
	}
}

if(isset($_POST['add_manager']) && isset($_POST['emp_id']) && isset($_POST['loc_id'])) {
	$eid = $_POST['emp_id'];
	$lid = $_POST['loc_id'];
	$data = array("error" => false, "error_msg" => "", "data" => array());

	$query = $conn->prepare("UPDATE employee SET emp_manager = 1, fk_loc_id = :lid WHERE emp_id = :eid");
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->bindParam(":eid", $eid, PDO::PARAM_INT);
	try {
		if($query->execute()) {
			$query = $conn->prepare("SELECT * FROM employee WHERE emp_id = :eid");
			$query->bindParam(":eid", $eid, PDO::PARAM_INT);
			$query->execute();
			$row = $query->fetch();
			$data["data"] = $row;
			echo json_encode($data);
		} else {
			$data['error'] = true;
			$data["error_msg"] = "Error updating database";
			echo json_encode($data);
		}
	} catch(PDOException $e) {
		$data["error"] = true;
		$data["error_msg"] = "Error connecting to database";
		echo json_encode($data);
	}
}

if(isset($_POST['remove_manager'])) {
	$eid = $_POST['remove_manager'];

	$query = $conn->prepare("UPDATE employee SET emp_manager = 0 WHERE emp_id = :eid");
	$query->bindParam(":eid", $eid, PDO::PARAM_INT);
	try {
		$query->execute();
	} catch(PDOException $e) {
		
	}
}

if(isset($_POST['remove_employee'])) {
	$eid = $_POST['emp_id'];
	$data = array("error" => false, "error_msg" => "", "data" => array());

	$query = $conn->prepare("UPDATE employee SET emp_manager = 0, fk_loc_id = NULL WHERE emp_id = :eid");
	$query->bindParam(":eid", $eid, PDO::PARAM_INT);
	try {
		if($query->execute()) {
			echo json_encode($data);
		} else {
			$data['error'] = true;
			$data["error_msg"] = "Error updating database";
			echo json_encode($data);
		}
	} catch(PDOException $e) {
		$data["error"] = true;
		$data["error_msg"] = "Error connecting to database";
		echo json_encode($data);
	}
}


if(isset($_POST['employee_add_search'])) {
	$input = $_POST['search_input'];

	$query = $conn->prepare("SELECT * FROM employee WHERE emp_username = :input AND emp_manager = 0 AND emp_status = 1");
	$query->bindParam(":input", $input, PDO::PARAM_STR);
	try {
		$query->execute();
		$rows = $query->fetchAll();
		// $data = array(
		// 	'error' => false,
		// 	'data' => $rows
		// );
		echo json_encode($rows);
	} catch(PDOException $e) {
		return $input;
	}
}

if(isset($_POST['restaurant_add_employee'])) {
	$eid = $_POST['emp_id'];
	$lid = $_POST['loc_id'];
	$data = array("error" => false, "error_msg" => "", "data" => array());

	$query = $conn->prepare("UPDATE employee SET fk_loc_id = :lid WHERE emp_id = :eid");
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->bindParam(":eid", $eid, PDO::PARAM_INT);
	try {
		if($query->execute()){
			$query = $conn->prepare("SELECT * FROM employee WHERE emp_id = :eid");
			$query->bindParam(":eid", $eid, PDO::PARAM_INT);
			$query->execute();
			$row = $query->fetch();
			$data["data"] = $row;
			echo json_encode($data);
		} else {
			$data["error"] = true;
			$data["error_msg"] = "Error updating database";
			echo json_encode($data);
		}
	} catch(PDOException $e) {
		$data["error"] = true;
		$data["error_msg"] = "Error connecting to database";
		echo json_encode($data);
	}
}

?>