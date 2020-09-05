<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['customer_signup'])) {
	$fname = (isset($_POST['first_name'])) ? trim($_POST['first_name']) : "";
	$lname = (isset($_POST['last_name'])) ? trim($_POST['last_name']) : "";
	$uname = (isset($_POST['username'])) ? trim($_POST['username']) : "";
	$email = (isset($_POST['email'])) ? trim($_POST['email']) : "";
	$pass1 = (isset($_POST['password_1'])) ? trim($_POST['password_1']) : "";
	$pass2 = (isset($_POST['password_2'])) ? trim($_POST['password_2']) : "";
	$res = array("error" => false, "error_msg" => "");

	// CHECKING FOR EMPTY INPUT

	if(!isset($fname) || !isset($lname) || !isset($uname) || !isset($pass1) || !isset($pass2)) {
		$res["error"] = true;
		$res["error_msg"] = "Empty/Invalid input";
		echo json_encode($res); exit();
	}

	// CHECKING LENGTH OF VARIABLES

	$fname_len = strlen($fname);
	$lname_len = strlen($lname);
	$uname_len = strlen($uname);
	$email_len = strlen($email);
	$pass1_len = strlen($pass1);
	$pass2_len = strlen($pass2);

	if($fname_len > 64) {
		$res["error"] = true;
		$res["error_msg"] = "Empty/Invalid input";
		echo json_encode($res); exit();
	}

	if($lname_len > 64) {
		$res["error"] = true;
		$res["error_msg"] = "First name is too long";
		echo json_encode($res); exit();
	}

	if($uname_len < 8 || $uname_len > 64) {
		$res["error"] = true;
		$res["error_msg"] = "Last name is too long";
		echo json_encode($res); exit();
	}

	if($email_len > 255) {
		$res["error"] = true;
		$res["error_msg"] = "Email is too long";
		echo json_encode($res); exit();
	}

	// VALIDATING INPUT

	if(has_special_char($fname) || has_special_char($lname)) {
		$res["error"] = true;
		$res["error_msg"] = "Name has invalid characters";
		echo json_encode($res); exit();
	}

	if(has_special_char($uname)) {
		$res["error"] = true;
		$res["error_msg"] = "Username contains invalid characters";
		echo json_encode($res); exit();
	}

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$res["error"] = true;
		$res["error_msg"] = "Invalid/Malformed email address";
		echo json_encode($res); exit();
	}

	if($pass1 == $pass2) {
		if(!password_check($pass1)) {
			$res["error"] = true;
			$res["error_msg"] = "Password must be at least 8 characters include a letter, number, and special character";
			echo json_encode($res); exit();
		}
	} else {
		$res["error"] = true;
		$res["error_msg"] = "Passwords don't match";
		echo json_encode($res); exit();
	}

	$phash = password_hash($pass1, PASSWORD_DEFAULT);
	$query = $conn->prepare("INSERT INTO customer (cust_first_name, cust_last_name, cust_username, cust_password, cust_email, cust_last_login) VALUES (:fname, :lname, :uname, :phash, :email, CURRENT_TIMESTAMP)");
	$query->bindParam(":fname", $fname, PDO::PARAM_STR);
	$query->bindParam(":lname", $lname, PDO::PARAM_STR);
	$query->bindParam(":uname", $uname, PDO::PARAM_STR);
	$query->bindParam(":phash", $phash, PDO::PARAM_STR);
	$query->bindParam(":email", $email, PDO::PARAM_STR);
	if($query->execute()) {
		$_SESSION['cust_id'] = $conn->lastInsertId();
		echo json_encode($res); exit();
	}

	$res["error"] = true;
	$res["error_msg"] = "Database Error: Could not create account at this time";
	echo json_encode($res); exit();
}

if(isset($_POST['customer_login'])) {
	$uname = (isset($_POST['username'])) ? trim($_POST['username']) : "";
	$passw = (isset($_POST['password'])) ? trim($_POST['password']) : "";
	$res = array("error" => false, "error_msg" => "", "field" => "");
	$tmp = false;

	if(has_special_char($uname) || strlen($uname) < 8 || strlen($uname > 32)) {
		$res["error"] = true;
		$res["error_msg"] = "Invalid username";
		$res["field"] = "#customer-username";
		echo json_encode($res); exit();
	}

	if(!password_check($passw)) {
		$res["error"] = true;
		$res["error_msg"] = "Invalid password";
		$res["field"] = "#customer-password";
		echo json_encode($res); exit();
	}

	// Attempt customer login
	$query = $conn->prepare("SELECT cust_id, cust_status, cust_password FROM customer WHERE cust_username = :uname");
	$query->bindParam(":uname", $uname, PDO::PARAM_STR);
	if($query->execute()) {
		// No account found with username
		if($query->rowCount() == 0) {
			$res["error"] = true;
			// $res["error_msg"] = "No account with this username";
			$res["error_msg"] = "No account with this username";
			$res["field"] = "#customer-username";
			echo json_encode($res); exit();
		} else {
			$row = $query->fetch();
			$phash = $row["cust_password"];
			if(password_verify($passw, $phash)) {
				// Display error if account is suspended
				if($row["cust_status"] == 0) {
					$res["error"] = true;
					$res["error_msg"] = "Account suspended";
					echo json_encode($res); exit();
				}

				// Update customer last login
				$query = $conn->prepare("UPDATE customer SET cust_last_login = CURRENT_TIMESTAMP WHERE cust_id = :cid");
				$query->bindParam(":cid", $row["cust_id"], PDO::PARAM_INT);
				if($query->execute()) {
					$_SESSION['cust_id'] = $row["cust_id"];
					echo json_encode($res); exit();
				}

				// Could not update last login timetamp
				// Display error
				$res["error"] = true;
				$res["error_msg"] = "Database error: Could not login at this time";
				echo json_encode($res); exit();
			} else {
				// Wrong password
				$res["error"] = true;
				$res["error_msg"] = "Incorrect login";
				$res["field"] = "#customer-password";
				echo json_encode($res); exit();
			}
		}
	}
}
?>