<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['restaurant-create-account'])) {
	$fname = $_POST['first-name'];
	$lname = $_POST['last-name'];
	$uname = $_POST['username'];
	$email = $_POST['email'];
	$pass1 = $_POST['create-password'];
	$pass2 = $_POST['confirm-password'];

	// First name is not alphanumeric
	if(has_special_char($fname)) {
		$_SESSION['error']['footer'] = "First name must be alphanumeric<br>";
		$_SESSION['error']['fname'] = true;
	}

	// Last name is not alphanumeric
	if(has_special_char($lname)) {
		$_SESSION['error']['footer'] .= "Last name must be alphanumeric<br>";
		$_SESSION['error']['lname'] = true;
	}

	// Username is not alphanumeric or less than 8 characters long
	if(has_special_char($uname) || strlen($uname) < 8) {
		$_SESSION['error']['footer'] .= "Username must be alphanumeric and at least 8 characters long<br>";
		$_SESSION['error']['uname'] = true;
	}

	// Username exists
	$query = $conn->prepare("SELECT * FROM employee WHERE emp_username = :uname");
	$query->bindParam(':uname', $uname, PDO::PARAM_STR);
	if($query->execute()) {
		if($query->rowCount() > 0) {
			$_SESSION['error']['footer'] .= "Username taken";
			$_SESSION['error']['uname'] = true;
		}
	} else {
		$_SESSION['popup'] = "Cannot create account at this time. Please try again later";
	}

	// Invalid email
	// TODO: Email verification
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['error']['footer'] .= "Invalid email address<br>";
		$_SESSION['error']['email'] = true;
	}

	// Confirm passwords match before validating password
	if($pass1 != $pass2) {
		$_SESSION['error']['footer'] .= "Passwords don't match<br>";
		$_SESSION['error']['pass1'] = true;
		$_SESSION['error']['pass2'] = true;
	} else {
		// Password must include one uppercase, lowercase,
		// special char, and be at least 8 characters long
		if(!password_check($pass1)) {
			$_SESSION['error']['footer'] .= "Password must be at least 8 characters and have at least one special character, uppercase, and lowercase<br>";
			$_SESSION['error']['pass1'] = true;
			$_SESSION['error']['pass2'] = true;
		}
	}

	// Redirect to business page if error found
	if(isset($_SESSION['error'])) {
		$_SESSION['create']['fname'] = htmlspecialchars($fname);
		$_SESSION['create']['lname'] = htmlspecialchars($lname);
		$_SESSION['create']['uname'] = htmlspecialchars($uname);
		$_SESSION['create']['email'] = htmlspecialchars($email);
		$_SESSION['modal'] = true;
		header('Location: /delectable/public_html/business/'); exit();
	}
	
	$phash = password_hash($pass1, PASSWORD_DEFAULT);
	$query = $conn->prepare("INSERT INTO employee (emp_first_name, emp_last_name, emp_username, emp_password, emp_email) VALUES (:fname, :lname, :uname, :passw, :email)");
	$query->bindParam(':fname', $fname);
	$query->bindParam(':lname', $lname);
	$query->bindParam(':uname', $uname);
	$query->bindParam(':passw', $phash);
	$query->bindParam(':email', $email);
	
	try {
		$query->execute();
		$_SESSION['active'] = true;
		$_SESSION['emp_id'] = $conn->lastInsertId();
		header('Location: /delectable/public_html/business/dashboard/');
	} catch (PDOException $e) {
		// echo $e;
	}
}

?>