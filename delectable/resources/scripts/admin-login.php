<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['admin-login'])) {
	$uname = $_POST['admin-username'];
	$pass = $_POST['admin-password'];

	$query = $conn->prepare("SELECT * FROM administrator WHERE admin_username = :uname");
	$query->bindParam(':uname', $uname, PDO::PARAM_STR);
	// Catch database connection errors
	if($query->execute()) {
		// If user exists check password
		if($query->rowCount() > 0) {
			$row = $query->fetch();
			$dbpass = $row['admin_password'];
			// Password verification
			if(password_verify($pass, $dbpass)) {
				$_SESSION['active'] = true;
				$_SESSION['admin_id'] = $row['admin_id'];
				$_SESSION['admin_access'] = true;

				$query = $conn->prepare("UPDATE administrator SET admin_last_login = CURRENT_TIMESTAMP WHERE admin_id = :id");
				$query->bindParam(':id', $_SESSION['admin_id']);
				$query->execute();

				header('Location: /delectable/public_html/admin/dashboard/'); exit();

			}
		}
	}

	// Redirect if not able to login
	$_SESSION['error'] = "Username and/or password don't match";
	header('Location: /delectable/public_html/admin/'); exit();
}

?>