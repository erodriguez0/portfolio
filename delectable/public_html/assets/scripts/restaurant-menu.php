<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['add_menu_item_category'])) {
	$cat = $_POST['cat_name'];
	$desc = (isset($_POST['cat_desc'])) ? $_POST['cat_desc'] : "";
	$lid = $_POST['loc_id'];
	$res = array("error" => false, "error_msg" => "", "cat_id" => 0);
	
	// 
	if(empty($cat) || strlen($cat) > 32) {
		$res["error"] = true;
		$res["error_msg"] = "Category name must be 1-32 characters long";
		echo json_encode($res); exit();
	}

	if(strlen($desc) > 255) {
		$res["error"] = true;
		$res["error_msg"] = "Category description too long";
		echo json_encode($res); exit();
	}

	if(!$res["error"]) {
		$query = $conn->prepare("INSERT INTO menu_item_category (item_cat_name, item_cat_description, fk_loc_id) VALUES (:name, :description, :lid)");
		$query->bindParam(":name", $cat, PDO::PARAM_STR);
		$query->bindParam(":description", $desc, PDO::PARAM_STR);
		$query->bindParam(":lid", $lid, PDO::PARAM_INT);
		if($query->execute()) {
			$cid = $conn->lastInsertId();
			$res["cat_id"] = $cid;
			echo json_encode($res);
		} else {
			$res["error"] = true;
			$res["error_msg"] = "Error creating category";
			echo json_encode($res);
		}
	} else {
		$res["error"] = true;
		$res["error_msg"] = "Error creating category";
		echo json_encode($res);
	}
}

if(isset($_POST['add_menu_item'])) {
	$lid = (isset($_POST['loc_id'])) ? $_POST['loc_id'] : 0;
	$cat = (isset($_POST['item_cat'])) ? $_POST['item_cat'] : 0;
	$name = (isset($_POST['item_name'])) ? trim($_POST['item_name']) : 0;
	$desc = (isset($_POST['item_desc'])) ? trim($_POST['item_desc']) : "";
	$price = (isset($_POST['item_price'])) ? $_POST['item_price'] : 0;
	$req = array($lid, $cat, $name, $price);
	$res = array("error" => false, "error_msg" => "", "id" => 0);
	
	// Find invalid/empty data
	// for($i = 0; $i < count($req); $i++) {
	// 	if($req[$i] == 0) {
	// 		$res["error"] = true;
	// 		$res["error_msg"] = "Invalid/empty data";
	// 		break;
	// 	}
	// }


	// if($res["error"]) {
	// 	echo json_encode($res); exit();
	// }

	// Valid integers for location and category id's
	if(!ctype_digit($lid) || !ctype_digit($cat) || $lid < 0 || $cat < 0) {
		$res["error"] = true;
		$res["error_msg"] = "Invalid/empty data";
		echo json_encode($res); exit();
	}

	if(!has_letter($name)) {
		$res["error"] = true;
		$res["error_msg"] = "Name must contain letters";
		echo json_encode($res); exit();
	}

	if(is_invalid_name($name)) {
		$res["error"] = true;
		$res["error_msg"] = "Name can only contain letters, numbers, and dashes";
		echo json_encode($res); exit();
	}

	if(strlen($name) > 32) {
		$res["error"] = true;
		$res["error_msg"] = "Name must be 1-32 characters";
		echo json_encode($res); exit();
	}

	if(strlen($desc) > 255) {
		$res["error"] = true;
		$res["error_msg"] = "Name cannot be more than 255 characters";
		echo json_encode($res); exit();
	}

	if(is_invalid_price($price)) {
		$res["error"] = true;
		$res["error_msg"] = "Invalid price";
		echo json_encode($res); exit();
	}

	if(!is_valid_price_format($price)) {
		$res["error"] = true;
		$res["error_msg"] = "Invalid price format";
		echo json_encode($res); exit();
	}

	if($price < 0)  {
		$res["error"] = true;
		$res["error_msg"] = "Price must be a positive number";
		echo json_encode($res); exit();
	}

	if(!$res["error"]) {
		$query = $conn->prepare("SELECT fk_loc_id FROM menu_item_category WHERE item_cat_id = :cid");
		$query->bindParam(":cid", $cat, PDO::PARAM_INT);
		if($query->execute()) {
			$row = $query->fetch();
			$db_lid = $row["fk_loc_id"];
			if(empty($db_lid) || $db_lid != $lid) {
				$res["error"] = true;
				$res["error_msg"] = "Permission denied. Cannot assign category";
				echo json_encode($res); exit();
			}
			$query = $conn->prepare("INSERT INTO menu_item(item_name, item_description, item_price, fk_item_cat_id) VALUES (:name, :description, :price, :cid)");
			$query->bindParam(":name", $name, PDO::PARAM_STR);
			$query->bindParam(":description", $desc, PDO::PARAM_STR);
			$query->bindParam(":price", $price, PDO::PARAM_STR);
			$query->bindParam(":cid", $cat, PDO::PARAM_INT);
			if($query->execute()) {
				$res["id"] = $conn->lastInsertId();
				echo json_encode($res); exit();
			} else {
				$res["error"] = true;
				$res["error_msg"] = "Cannot add item at this time";
				echo json_encode($res); exit();
			}
		} else {
			$res["error"] = true;
			$res["error_msg"] = "Cannot add item at this time";
			echo json_encode($res); exit();
		}
	} else {
		$res["error"] = true;
		$res["error_msg"] = "Cannot add item at this time";
		echo json_encode($res); exit();
	}
}

?>