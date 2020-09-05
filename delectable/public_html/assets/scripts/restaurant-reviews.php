<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['load_layout'])) {
	$lid = $_POST['loc_id'];
	$res = array("error" => false, "error_msg" => "", "data" => array());
	$sql = "";
	$sql = <<<'EOT'
		SELECT
			(SELECT AVG(w1.review_rating) 
			FROM review w1, reservation r1, `table` t1 
			WHERE w1.fk_rsvn_id = r1.rsvn_id AND r1.fk_table_id = t1.table_id 
				AND r1.fk_loc_id = l.loc_id AND t1.table_id = t.table_id) as avg_rating,
			(SELECT COUNT(w2.review_id) 
			FROM review w2, reservation r2, `table` t2 
			WHERE w2.fk_rsvn_id = r2.rsvn_id AND r2.fk_table_id = t2.table_id 
				AND r2.fk_loc_id = l.loc_id AND t2.table_id = t.table_id) as num_of_reviews,
			t.*
		FROM location l, `table` t
		WHERE t.fk_loc_id = l.loc_id AND t.table_status = 1 AND l.loc_id = :lid	
	EOT;
	$query = $conn->prepare($sql);
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	if($query->execute()) {
		$rows = $query->fetchAll();
		if(count($rows) > 0) {
			foreach($rows as $k) {
				$row = array(
					"avg" => $k["avg_rating"],
					"num_of_reviews" => $k["num_of_reviews"],
					"id" => $k["table_id"],
					"uuid" => $k["table_uuid"],
					"num" => $k["table_number"],
					"seats" => $k["table_seats"],
					"type" => $k["table_type"],
					"height" => $k["table_height"],
					"width" => $k["table_width"],
					"left" => $k["table_left"],
					"top" => $k["table_top"],
					"deg" => $k["table_angle"]
				);
				$res["data"][] = $row;
			}
		}

		$sql = "SELECT * FROM `object` WHERE fk_loc_id = :lid";
		$query = $conn->prepare($sql);
		$query->bindParam(":lid", $lid, PDO::PARAM_INT);
		if($query->execute()) {
			$rows = $query->fetchAll();
			if(count($rows) > 0) {
				foreach($rows as $k) {
					$row = array(
						"id" => $k["object_id"],
						"uuid" => $k["object_uuid"],
						"type" => $k["object_type"],
						"height" => $k["object_height"],
						"width" => $k["object_width"],
						"left" => $k["object_left"],
						"top" => $k["object_top"],
						"deg" => $k["object_angle"]
					);
					$res["data"][] = $row;
				}
			}
			echo json_encode($res); exit();	
		}
	}
	$res["error"] = true;
	$res["error_msg"] = "Could not retrieve review information";
	echo json_encode($res); exit();
}

if(isset($_POST['table_reviews'])) {
	$uuid = $_POST['table_uuid'];
	$res = array("error" => false, "error_msg" => "", "data" => array());
	$sql = "";
	$sql = <<<'EOT'
		SELECT
			rsvn_id, review_text, review_rating, review_created, review_food_rating, 
			review_service_rating, cust_first_name, cust_last_name
		FROM
			reservation r, review w, `table` t, customer c
		WHERE
			w.fk_cust_id = c.cust_id AND
			r.rsvn_id = w.fk_rsvn_id AND
		    r.fk_table_id = t.table_id AND
		    t.table_uuid = :uuid
	EOT;
	$query = $conn->prepare($sql);
	$query->bindParam(":uuid", $uuid, PDO::PARAM_STR);
	if($query->execute()) {
		$res["data"] = $query->fetchAll();
		echo json_encode($res); exit();
	}
	$res["error"] = true;
	$res["error_msg"] = "Could not retrieve reviews for table";
	echo json_encode($res); exit();
}

?>