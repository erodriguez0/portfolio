<?php

if(isset($_POST['export_canvas'])) {
	$json = $_POST['canvasJSON'];

	file_put_contents('file.json', $json);
	// echo $json;
}

if(isset($_POST['import_canvas'])) {
	echo file_get_contents('file.json');
}

?>