<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(isset($_POST['restaurant-login'])) {
	$uname = $_POST['username'];
	$passw = $_POST['password'];

	// $query = $conn->prepare("SELECT emp_id, emp_password, emp_manager, emp_status, loc_id, res_id FROM employee, restaurant, location WHERE emp_username = :uname AND fk_loc_id = loc_id AND fk_res_id = res_id");
	$query = $conn->prepare("SELECT emp_id, emp_password, emp_manager, emp_status, fk_loc_id FROM employee WHERE emp_username = :uname");
	$query->bindParam(":uname", $uname, PDO::PARAM_STR);
	try {
		$query->execute();
		$row = $query->fetch();
		if($row) {
			$dbpass = $row['emp_password'];
			if(password_verify($passw, $dbpass)) {
				$_SESSION['emp_id'] = $row['emp_id'];
				$_SESSION['emp_status'] = $row['emp_status'];
				$lid = $row['fk_loc_id'];
				if(!empty($lid) && $lid > 0) {
					$query = $conn->prepare("SELECT res_id, loc_id FROM location, restaurant WHERE res_id = fk_res_id AND loc_id = :lid");
					$query->bindParam(":lid", $lid, PDO::PARAM_INT);
					$query->execute();
					$loc = $query->fetch();
					$_SESSION['res_id'] = $loc['res_id'];
					$_SESSION['loc_id'] = $loc['loc_id'];
				}
				
				if($row['emp_manager']) {
					$_SESSION['manager'] = true;
				}
				$query = $conn->prepare("UPDATE employee SET emp_last_login = CURRENT_TIMESTAMP WHERE emp_id = :eid");
				$query->bindParam(":eid", $row['emp_id'], PDO::PARAM_INT);
				try {
					$query->execute();
					header('Location: /delectable/public_html/business/dashboard'); exit();
				} catch (PDOException $e) {
					// Can't login / DB error
					unset($_SESSION['emp_id']);
					unset($_SESSION['res_id']);
					unset($_SESSION['loc_id']);
					if(isset($_SESSION['manager'])) {
						unset($_SESSION['manager']);
					}
					$_SESSION['login']['username'] = $uname;
					$_SESSION['error']['footer'] = "Can't login at this time. Please try again later.";
					header('Location: /delectable/public_html/business/'); exit();
				}
			} else {
				$_SESSION['login']['username'] = $uname;
				$_SESSION['error']['footer'] = "Wrong username/password";
				header('Location: /delectable/public_html/business/'); exit();
			}
		} else {
			// No one found
			$_SESSION['login']['username'] = $uname;
			$_SESSION['error']['username'] = true;
			$_SESSION['error']['footer'] = "Account not found.";
			header('Location: /delectable/public_html/business/'); exit();
		}
	} catch (PDOException $e) {
		// Can't login / DB error
		$_SESSION['login']['username'] = $uname;
		$_SESSION['error']['footer'] = "Can't login at this time. Please try again later.";
		header('Location: /delectable/public_html/business/'); exit();
	}
}
?>