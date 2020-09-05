<?php
// connect to db
$db = array(
	"name" => "delectable",
	"user" => "root",
	"pass" => "Uc200760cL",
	"host" => "127.0.0.1"
);

$dsn = 'mysql:host=' . $db['host'] . ';dbname=' . $db['name'];
try {
$conn = new PDO($dsn, $db['user'], $db['pass']);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

// intialize session
if(session_status() == PHP_SESSION_NONE) { 
	session_start(); 
}

$page = basename(getcwd(), ".php");
// error_reporting(E_ALL);
// ini_set('display_errors', true);

// define paths
define("LAYOUT_IMG_PATH", $_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/img/');
define("RESOURCE_PATH", $_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/');
define("INCLUDE_PATH", $_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/');
define("HOME", $_SERVER['DOCUMENT_ROOT'] . '/delectable/public_html/');
define("SCRIPTS_PATH", $_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/scripts/');
define("JS_PATH", '/delectable/public_html/assets/js/');

$scripts = array();
// $scripts[] = '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'jquery-3.4.1.min.js"></script>';
// $scripts[] = '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>';
$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'popper.min.js"></script>';
// $scripts[] = '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'bootstrap.min.js"></script>';
// $scripts[] = '<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>';
$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'feather.min.js"></script>';
$scripts[] = '<script> feather.replace(); </script>';
// $scripts[] = '<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.11/fabric.min.js"></script>';
$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'fabric.min.js"></script>';
// $scripts[] = '<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>';
$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'gijgo.min.js"></script>';
$scripts[] = '<script defer type="text/javascript" src="' . JS_PATH . 'global.js"></script>';
if(isset($_SESSION['admin_id'])):
	if($page == "layout") {
		$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'layout.js"></script>';
	}
	$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'admin.js"></script>';
elseif(isset($_SESSION['manager'])):
	if($page == "layout") {
		$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'layout.js"></script>';
	} else if($page == "reviews") {
		$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'heatmap.js"></script>';
	}
	$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'manager.js"></script>';
elseif(isset($_SESSION['emp_id']) && !isset($_SESSION['manager'])):
	$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'tables.js"></script>';
	$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'employee.js"></script>';
else:
	if($page == "reserve") {
		$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'reserve.js"></script>';
	}
	$scripts[] = '<script type="text/javascript" src="' . JS_PATH . 'customer.js"></script>';
endif;
?>