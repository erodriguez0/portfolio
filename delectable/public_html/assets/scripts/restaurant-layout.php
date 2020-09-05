<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['save_layout'])) {
	$lid = $_POST['loc_id'];
	$delete = $_POST['deletedObjects'];
	$obj = $_POST['objects'];
	$tables = array();
	$objects = array();
	$deleteTables = array();
	$deleteObjects = array();

	foreach ($obj as $k => $v) {
		if(json_decode($obj[$k]["table"]) == "table") {
			$tables[] = $obj[$k];
		} else {
			$objects[] = $obj[$k];
		}
	}

	foreach ($delete as $k => $v) {
		if(json_decode($delete[$k]["table"])) {
			$deleteTables[] = $delete[$k];
		} else {
			$deleteObjects[] = $delete[$k];
		}
	}

	
	if(!empty($deleteTables)) {
		foreach($deleteTables as $key => $value) {
			$uuid = $deleteTables[$key]["id"];
			$query = $conn->prepare("UPDATE `table` SET table_status = 0 WHERE `table_uuid` = :uuid");
			$query->bindParam(":uuid", $uuid, PDO::PARAM_STR);
			$query->execute();
		}
	}

	if(!empty($deleteObjects)) {
		foreach($deleteObjects as $key => $value) {
			$uuid = $deleteObjects[$key]["id"];
			$query = $conn->prepare("DELETE FROM `object` WHERE `object_uuid` = :uuid");
			$query->bindParam(":uuid", $uuid, PDO::PARAM_STR);
			$query->execute();
		}
	}

	if(!empty($tables)) {
		foreach ($tables as $key => $value) {
			if(json_decode($tables[$key]["new"])) {
				$uuid = $tables[$key]["id"];
				$type = $tables[$key]["type"];
				$num = $tables[$key]["number"];
				$left = (int)$tables[$key]["left"];
				$top = (int)$tables[$key]["top"];
				$width = (int)$tables[$key]["width"];
				$height = (int)$tables[$key]["height"];
				$deg = (int)$tables[$key]["deg"];
				$query = $conn->prepare("INSERT INTO `table`(`table_uuid`, `table_number`, `table_seats`, `table_type`, `table_height`, `table_width`, `table_left`, `table_top`, `table_angle`, `fk_loc_id`) VALUES (:uuid, :num, 1, :type, :height, :width, :left, :top, :deg, :lid)");
				$query->bindParam(":uuid",   $uuid,   PDO::PARAM_STR);
				$query->bindParam(":num",    $num,    PDO::PARAM_STR);
				$query->bindParam(":type",   $type,   PDO::PARAM_STR);
				$query->bindParam(":height", $height, PDO::PARAM_INT);
				$query->bindParam(":width",  $width,  PDO::PARAM_INT);
				$query->bindParam(":left",   $left,   PDO::PARAM_INT);
				$query->bindParam(":top",    $top,    PDO::PARAM_INT);
				$query->bindParam(":deg",    $deg,    PDO::PARAM_INT);
				$query->bindParam(":lid",    $lid,    PDO::PARAM_INT);
				$query->execute();
			} else {
				$uuid = $tables[$key]["id"];
				$num = $tables[$key]["number"];
				$left = (int)$tables[$key]["left"];
				$top = (int)$tables[$key]["top"];
				$width = (int)$tables[$key]["width"];
				$height = (int)$tables[$key]["height"];
				$query = $conn->prepare("
					UPDATE `table`
					SET 
						`table_number` = :num,
						`table_height` = :height,
						`table_width` = :width,
						`table_left` = :left,
						`table_top` = :top
					WHERE
						`table_uuid` = :uuid
				");
				$query->bindParam(":uuid",   $uuid,   PDO::PARAM_STR);
				$query->bindParam(":num",    $num,    PDO::PARAM_STR);
				$query->bindParam(":height", $height, PDO::PARAM_INT);
				$query->bindParam(":width",  $width,  PDO::PARAM_INT);
				$query->bindParam(":left",   $left,   PDO::PARAM_INT);
				$query->bindParam(":top",    $top,    PDO::PARAM_INT);
				$query->execute();
			}
		}
	}

	if(!empty($objects)) {
		foreach ($objects as $key => $value) {
			if(json_decode($objects[$key]["new"])) {
				$uuid = $objects[$key]["id"];
				$type = $objects[$key]["type"];
				$left = (int)$objects[$key]["left"];
				$top = (int)$objects[$key]["top"];
				$width = (int)$objects[$key]["width"];
				$height = (int)$objects[$key]["height"];
				$deg = (int)$objects[$key]["deg"];
				$query = $conn->prepare("INSERT INTO `object`(`object_uuid`, `object_type`, `object_height`, `object_width`, `object_left`, `object_top`, `object_angle`, `fk_loc_id`) VALUES (:uuid, :type, :height, :width, :left, :top, :deg, :lid)");
				$query->bindParam(":uuid",   $uuid,   PDO::PARAM_STR);
				$query->bindParam(":type",   $type,   PDO::PARAM_STR);
				$query->bindParam(":height", $height, PDO::PARAM_INT);
				$query->bindParam(":width",  $width,  PDO::PARAM_INT);
				$query->bindParam(":left",   $left,   PDO::PARAM_INT);
				$query->bindParam(":top",    $top,    PDO::PARAM_INT);
				$query->bindParam(":deg",    $deg,    PDO::PARAM_INT);
				$query->bindParam(":lid",    $lid,    PDO::PARAM_INT);
				$query->execute();
			} else {
				$uuid = $objects[$key]["id"];
				$left = (int)$objects[$key]["left"];
				$top = (int)$objects[$key]["top"];
				$width = (int)$objects[$key]["width"];
				$height = (int)$objects[$key]["height"];
				$query = $conn->prepare("
					UPDATE `object`
					SET 
						`object_height` = :height,
						`object_width` = :width,
						`object_left` = :left,
						`object_top` = :top
					WHERE
						`object_uuid` = :uuid
				");
				$query->bindParam(":uuid",   $uuid,   PDO::PARAM_STR);
				$query->bindParam(":height", $height, PDO::PARAM_INT);
				$query->bindParam(":width",  $width,  PDO::PARAM_INT);
				$query->bindParam(":left",   $left,   PDO::PARAM_INT);
				$query->bindParam(":top",    $top,    PDO::PARAM_INT);
				$query->execute();
			}
		}
	}

	echo json_encode($objects);	
}

if(isset($_POST['load_layout'])) {
	$lid = $_POST['loc_id'];
	$tables = array();
	$query = $conn->prepare("SELECT * FROM `table`, location WHERE loc_id = fk_loc_id AND table_status = 1 AND loc_id = :lid");
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll();
	foreach ($rows as $k) {
		$row = array(
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
		$tables[] = $row;
	}

	$query = $conn->prepare("SELECT * FROM `object`, location WHERE loc_id = fk_loc_id AND loc_id = :lid");
	$query->bindParam(":lid", $lid, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll();
	foreach ($rows as $k) {
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
		$tables[] = $row;
	}

	echo json_encode($tables);
}