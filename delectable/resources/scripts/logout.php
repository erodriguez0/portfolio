<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');
require_once(INCLUDE_PATH . 'functions.php');

if(isset($_POST['logout'])) {
	session_destroy();
	$_SESSION = array();

	header('Location: /delectable/public_html/');
}

?>