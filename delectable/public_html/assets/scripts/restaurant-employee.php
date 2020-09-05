<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['create-employee-account'])) {
	$required = array('emp-first-name', 'emp-last-name', 'emp-username', 'emp-email', 'emp-password-1', 'emp-password-2');
	$response = array("error" => false, "error_msg" => "", "data" => array());
	$error = false;

	// Check if POST vars are set and not empty
	foreach ($required as $field) {
		if(!isset($_POST[$field]) || empty($_POST[$field])) {
			$error = true;
			break;
		}
	}

	if(!isset($_POST['loc_id']) || !isset($_POST['emp-manager']) 
		|| !isset($_POST['emp-pay'])) {
		$error = true;
	}

	// Fields are empty or not set
	if($error) {
		foreach ($required as $field) {
			if(isset($_POST[$field]) && !empty($_POST[$field])) {
				$response["data"][$field] = $_POST[$field];
			}
		}
		$response["error"] = true;
		$response["error_msg"] = "Required fields cannot be left empty";
		echo json_encode($response); exit();
	} else {
		foreach ($required as $field) {
			$response["data"][$field] = $_POST[$field];
		}
	}

	$fname =  trim($_POST['emp-first-name']);
	$lname =  trim($_POST['emp-last-name']);
	$uname =  trim($_POST['emp-username']);
	$email =  trim($_POST['emp-email']);
	$pass1 =  trim($_POST['emp-password-1']);
	$pass2 =  trim($_POST['emp-password-2']);
	$pay = $_POST['emp-pay'];
	$pay_rate = $_POST['emp-pay-rate'];
	$access = $_POST['emp-manager'];
	$job = trim($_POST['emp-job']);
	$lid = $_POST['loc_id'];

	// First and last name are alphanumeric
	if(has_special_char($fname) || has_special_char($lname)) {
		$response["error"] = true;
		$response["error_msg"] = "Name must be alphanumeric";
		echo json_encode($response); exit();
	}

	// Username at least 8 alphanum chars
	if(strlen($uname) < 8 || has_special_char($uname)) {
		$response["error"] = true;
		$response["error_msg"] = "Username must be alphanumeric and at least 8 characters";
		echo json_encode($response); exit();
	}

	// Check if username exists
	$query = $conn->prepare("SELECT * FROM employee WHERE emp_username = :uname");
	$query->bindParam(':uname', $uname, PDO::PARAM_STR);
	if($query->execute()) {
		if($query->rowCount() > 0) {
			$response["error"] = true;
			$response["error_msg"] = "Username exists";
			echo json_encode($response); exit();
		}
	}

	// Passwords are the same
	if($pass1 != $pass2) {
		$response["error"] = true;
		$response["error_msg"] = "Passwords do not match";
		echo json_encode($response); exit();
	}

	// Password contains at least one special char and at least 8 chars
	if(!password_check($pass1)) {
		$response["error"] = true;
		$response["error_msg"] = "Password must be at least 8 characters and have at least one special character";
		echo json_encode($response); exit();
	}

	// Basic PHP email filter validation
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response["error"] = true;
		$response["error_msg"] = "Invalid email";
		echo json_encode($response); exit();
	}

	// Access value is not 0 or 1
	if(is_int($access)) {
		$response["error"] = true;
		$response["error_msg"] = "Invalid value for manager access";
		echo json_encode($response); exit();
	}

	if(has_special_char($emp_job)) {
		$emp_job = "";
	}

	// Wage/Salary isn't formatted or negative
	if(!is_currency($pay) || $pay < 0 || $pay < "0") {
		$pay = "0.00";
	}

	$pay_rate_options = array("hourly", "weekly", "biweekly", "semimonthly", "monthly", "semiannual", "annual");

	if(!in_array($pay_rate, $pay_rate_options) || $pay_rate == "none") {
		$pay_rate = "";
	}

	$sql  = "INSERT INTO employee (emp_first_name, emp_last_name, emp_username, emp_email, emp_password, emp_manager, emp_job, emp_pay, emp_pay_rate, fk_loc_id) ";
	$sql .= "VALUES (:fname, :lname, :uname, :email, :hash, :access, :job, :rate, :type, :lid)";
	$hash = password_hash($pass1, PASSWORD_DEFAULT);
	$query = $conn->prepare($sql);
	$query->bindParam(":fname", $fname, PDO::PARAM_STR);
	$query->bindParam(":lname", $lname, PDO::PARAM_STR);
	$query->bindParam(":uname", $uname, PDO::PARAM_STR);
	$query->bindParam(":email", $email, PDO::PARAM_STR);
	$query->bindParam(":hash", $hash, PDO::PARAM_STR);
	$query->bindParam(":access", $access, PDO::PARAM_INT);
	$query->bindParam(":job", $job, PDO::PARAM_STR);
	$query->bindParam(":rate", $pay, PDO::PARAM_STR);
	$query->bindParam(":type", $pay_rate, PDO::PARAM_STR);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);

	if($query->execute()) {
		$id = $conn->lastInsertId();
		$response["data"]["emp_id"] = $id;
		echo json_encode($response); exit();
	} else {
		$response["error"] = true;
		$response["error_msg"] = "Cannot make account at this time. Please try again later.";
		echo json_encode($response); exit();
	}
}

if(isset($_POST['revoke_manager_access'])) {
	$eid = $_POST['emp_id'];
	$lid = $_POST['loc_id'];
	$success = false;

		// Check if username exists
	$query = $conn->prepare("SELECT * FROM employee WHERE fk_loc_id = :lid AND emp_id = :eid");
	$query->bindParam(':lid', $lid, PDO::PARAM_INT);
	$query->bindParam(':eid', $eid, PDO::PARAM_INT);
	if($query->execute()) {
		if($query->rowCount() > 0) {
			$query = $conn->prepare("UPDATE employee SET emp_manager = 0 WHERE emp_id = :eid");
			$query->bindParam(':eid', $eid, PDO::PARAM_INT);
			if($query->execute()) {
				$success = true;
				echo json_encode($success);
			} else {
				echo json_encode($success);
			}
		} else {
			echo json_encode($success);
		}
	} else {
		echo json_encode($success);
	}
}

if(isset($_POST['grant_manager_access'])) {
	$eid = $_POST['emp_id'];
	$lid = $_POST['loc_id'];
	$success = false;

		// Check if username exists
	$query = $conn->prepare("SELECT * FROM employee WHERE fk_loc_id = :lid AND emp_id = :eid");
	$query->bindParam(':lid', $lid, PDO::PARAM_INT);
	$query->bindParam(':eid', $eid, PDO::PARAM_INT);
	if($query->execute()) {
		if($query->rowCount() > 0) {
			$query = $conn->prepare("UPDATE employee SET emp_manager = 1 WHERE emp_id = :eid");
			$query->bindParam(':eid', $eid, PDO::PARAM_INT);
			if($query->execute()) {
				$success = true;
				echo json_encode($success);
			} else {
				echo json_encode($success);
			}
		} else {
			echo json_encode($success);
		}
	} else {
		echo json_encode($success);
	}
}

?>