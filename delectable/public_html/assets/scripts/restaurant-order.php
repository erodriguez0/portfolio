<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['order_details'])) {
	$rid = $_POST['rsvn_id'];
	$res = array("error" => false, "error_msg" => "", "data" => array());
	$sql = "SELECT cust_id, cust_first_name, cust_last_name, cust_address_1, cust_address_2, cust_city, cust_state, cust_postal_code, cust_phone, cust_email, ";
	$sql .= "order_id, order_created, order_total, order_status, order_message, rsvn_id, rsvn_date, rsvn_slot, rsvn_length, rsvn_status, table_id, table_number ";
	$sql .= "FROM customer c, `order` o, reservation r, `table` t ";
	$sql .= "WHERE rsvn_id = fk_rsvn_id AND table_id = fk_table_id AND c.cust_id = r.fk_cust_id AND o.fk_cust_id = r.fk_cust_id AND r.rsvn_id = :rid";

	$query = $conn->prepare($sql);
	$query->bindParam(":rid", $rid, PDO::PARAM_INT);
	if($query->execute()) {
		$res["data"]["rsvn"] = $query->fetch();
		$sql = "";
		$sql .= "SELECT emp_id, emp_first_name, emp_last_name, emp_job, emp_manager ";
		$sql .= "FROM reservation_staff s, employee e ";
		$sql .= "WHERE e.emp_id = s.fk_emp_id AND s.fk_rsvn_id = :rid";
		$query = $conn->prepare($sql);
		$query->bindParam("rid", $rid, PDO::PARAM_INT);
		if($query->execute()) {
			$res["data"]["emp"] = $query->fetchAll();
			echo json_encode($res); exit();
		}
	}

	$res["error"] = true;
	$res["error_msg"] = "Could not retrieve reservation information";
	echo json_encode($res); exit();
}

if(isset($_POST['restaurant-employee-list'])) {
	$lid = $_POST['loc_id'];
	$rid = $_POST['rsvn_id'];
	$query = $conn->prepare("SELECT emp_id, emp_first_name, emp_last_name, emp_job FROM employee e WHERE fk_loc_id = :lid AND emp_id NOT IN (SELECT fk_emp_id FROM reservation_staff WHERE fk_rsvn_id = :rid)");
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->bindParam(":rid", $rid, PDO::PARAM_INT);
	if($query->execute()) {
		echo json_encode($query->fetchAll()); exit();
	}
}

if(isset($_POST['assign_staff'])) {
	$rid = $_POST['rsvn_id'];
	$emps = $_POST['emps'];
	$res = array("error" => false, "error_msg" => "", "data" => array());
	if(count($emps) > 0) {
		$sql = "INSERT INTO reservation_staff (fk_rsvn_id, fk_emp_id) VALUES ";
		for($i = 0; $i < count($emps); $i++) {
			$sql .= "(:rsvn" . $i . ", :eid" . $i . ")";
			if($i != count($emps) - 1) {
				$sql .= ",";
			}
		}

		$query = $conn->prepare($sql);
		for($i = 0; $i < count($emps); $i++) {
			$query->bindParam(":rsvn" . $i, $rid, PDO::PARAM_INT);
			$query->bindParam(":eid" . $i, $emps[$i]["emp_id"], PDO::PARAM_INT);
		}

		if($query->execute()) {
			echo json_encode($res); exit();
		}
	}

	$res["error"] = true;
	$res["error_msg"] = "Error assigning staff";
	echo json_encode($res); exit();
}

?>