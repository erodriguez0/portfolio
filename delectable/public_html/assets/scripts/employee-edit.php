<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['employee_profile'])) {
	$eid = $_POST['eid'];

	$query = $conn->prepare("
		SELECT emp_first_name, emp_last_name, emp_email, emp_username, emp_last_login, emp_created, emp_updated, emp_address_1, emp_address_2, emp_city, emp_state, emp_postal_code, emp_phone, emp_status, res_name, loc_address_1, loc_address_2, loc_city, loc_state, loc_postal_code, loc_phone 
		FROM employee
		LEFT JOIN location ON
		loc_id = fk_loc_id
		LEFT JOIN restaurant ON
		res_id = fk_res_id 
		WHERE emp_id = :eid
		");
	$query->bindParam(":eid", $eid, PDO::PARAM_INT);

	try {
		$query->execute();
		echo json_encode($query->fetch());
	} catch (PDOException $e) {
		
	}
}

if(isset($_POST['employee_update'])) {
	$data = array();
	$eid = $_POST['eid'];
	$data['fname'] = $fname = trim($_POST['fname']);
	$data['lname'] = $lname = trim($_POST['lname']);
	$data['uname'] = $uname = trim($_POST['uname']);
	$data['email'] = $email = trim($_POST['email']);
	$data['address1'] = $address1 = trim($_POST['address1']);
	$data['address2'] = $address2 = trim($_POST['address2']);
	$data['phone'] = $phone = trim($_POST['phone']);
	$data['city'] = $city = trim($_POST['city']);
	$data['state'] = $state = trim($_POST['state']);
	$data['zip'] = $zip = trim($_POST['zip']);
	$data['status'] = $status = trim($_POST['status']);
	$error_msg = "";
	$error = false;

	// First name is not alphanumeric
	if(has_special_char($fname)) {
		$error_msg = "First name must be alphanumeric<br>";
		$error = true;
	}

	// Last name is not alphanumeric
	if(has_special_char($lname)) {
		$error_msg .= "Last name must be alphanumeric<br>";
		$error = true;
	}

	// Username is not alphanumeric or less than 8 characters long
	if(has_special_char($uname) || strlen($uname) < 8) {
		$error_msg .= "Username must be alphanumeric and at least 8 characters long<br>";
		$error = true;
	}

	// Invalid email
	// TODO: Email verification
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error_msg .= "Invalid email address<br>";
		$error = true;
	}

	// Username exists
	$query = $conn->prepare("SELECT * FROM employee WHERE emp_username = :uname");
	$query->bindParam(':uname', $uname, PDO::PARAM_STR);
	if($query->execute()) {
		// $row = $query->fetch();
		if($query->rowCount() > 0) {

			$row = $query->fetch();

			if($row['emp_id'] != $eid) {
				$error_msg .= "Username taken";
				$error = true;
			}

		}

	} else {
		$error_msg = "Cannot create account at this time. Please try again later";
		$error = true;
	}

	if(!$error) {
		$query = $conn->prepare("
			UPDATE employee
			SET
				emp_first_name = :fname,
				emp_last_name = :lname,
				emp_username = :uname,
				emp_email = :email,
				emp_address_1 = :address1,
				emp_address_2 = :address2,
				emp_phone = :phone,
				emp_city = :city,
				emp_state = :state,
				emp_postal_code = :zip,
				emp_status = :status,
				emp_updated = CURRENT_TIMESTAMP
			WHERE
				emp_id = :eid
			");

		$query->bindParam(":fname", $fname, PDO::PARAM_STR);
		$query->bindParam(":lname", $lname, PDO::PARAM_STR);
		$query->bindParam(":uname", $uname, PDO::PARAM_STR);
		$query->bindParam(":email", $email, PDO::PARAM_STR);
		$query->bindParam(":address1", $address1, PDO::PARAM_STR);
		$query->bindParam(":address2", $address2, PDO::PARAM_STR);
		$query->bindParam(":phone", $phone, PDO::PARAM_STR);
		$query->bindParam(":city", $city, PDO::PARAM_STR);
		$query->bindParam(":state", $state, PDO::PARAM_STR);
		$query->bindParam(":zip", $zip, PDO::PARAM_STR);
		$query->bindParam(":status", $status, PDO::PARAM_INT);
		$query->bindParam(":eid", $eid, PDO::PARAM_INT);

		if($query->execute()) {
			$res = array(
				'error' => false
			);

			echo json_encode($res);
		} else {
			$res = array(
				'error' => true,
				'error_msg' => "Error connecting to database",
				'data' => $data
			);

			echo json_encode($res);
		}

	} else {
		$res = array(
			'error' => true,
			'error_msg' => $error_msg,
			'data' => $data
		);

		echo json_encode($res);	
	}
}

?>