<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['restaurant_hours'])) {
	$lid = $_POST['loc_id'];
	$day = $_POST['day'];
	$query = $conn->prepare("SELECT * FROM location_hours WHERE hours_day = :day AND fk_loc_id = :lid");
	$query->bindParam(":day", $day, PDO::PARAM_INT);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	if($query->execute()) {
		echo json_encode($query->fetch());
	}
}

if(isset($_POST['available_hours'])) {
	$lid = $_POST['loc_id'];
	$day = $_POST['day'];
	$uuid = $_POST['table_uuid'];
	$date = $_POST['rsvn_date'];
	$data = array();
	$query = $conn->prepare("SELECT hours_open, hours_close FROM location_hours WHERE hours_day = :day AND fk_loc_id = :lid");
	$query->bindParam(":day", $day, PDO::PARAM_INT);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	if($query->execute()) {
		$data["hours"] = $query->fetch();
		$query = $conn->prepare("SELECT table_id FROM `table` WHERE table_uuid = :uuid");
		$query->bindParam(":uuid", $uuid, PDO::PARAM_STR);
		if($query->execute()) {
			$table = $query->fetch();
			$tid = $table["table_id"];
			$query = $conn->prepare("SELECT rsvn_slot FROM reservation WHERE rsvn_date = :rsvn_date AND fk_loc_id = :lid AND fk_table_id = :tid");
			$query->bindParam(":rsvn_date", $date, PDO::PARAM_STR);
			$query->bindParam(":lid", $lid, PDO::PARAM_INT);
			$query->bindParam(":tid", $tid, PDO::PARAM_INT);
			if($query->execute()) {
				$data["rsvn"] = $query->fetchAll();
				echo json_encode($data);
			}
		}
	}
}
?>