<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

// Redirect if not logged in
if(isset($_SESSION['admin_id']) || !isset($_SESSION['emp_id'])):
	header('Location: /delectable/public_html/');

// Display message if no manager access granted
elseif(isset($_SESSION['emp_id']) && !isset($_SESSION['manager'])):
	$title = "Delectable | For Restaurants";
	require_once(INCLUDE_PATH . 'header.php');
?>
	<!-- Navigation -->
	<nav id="home-nav" class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
	    <div class="container">
	        <a class="navbar-brand text-uppercase" href="#">Delectable</a>
	        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse" id="navbarResponsive">
	            <ul class="navbar-nav ml-auto">
	                <li class="nav-item">
	                    <a class="nav-link" href="/delectable/public_html/">Home</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="/delectable/public_html/business/">Business</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="/delectable/public_html/admin/">Admin</a>
	                </li>
	                <li class="nav-item">
			            <form method="POST" action="/delectable/resources/scripts/logout.php">
			                <button type="submit" name="logout" class="nav-link text-capitalize btn-link-alt">Sign out</button>
			            </form>
			        </li>
	            </ul>
	        </div>
	    </div>
	</nav>
	<section class="mt-5 pt-5">
    	<div class="container">
			<h1 class="text-center">Contact Support To Finalize Registration</h1>
			<h3 class="text-center">(###) ###-####</h3>
		</div>
	</section>

<?php
	require_once(INCLUDE_PATH . 'footer.php');

// Display dashboard if they have manager access
else:

$title = "Delectable | For Restaurants";
require_once(INCLUDE_PATH . 'header.php');

require_once(INCLUDE_PATH . 'business/manager/dashboard.php');
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	    <h1 class="h2">Dashboard</h1>
	</div>
	<div class="manager-main">
		
	</div>
</main>

<?php
endif;
require_once(INCLUDE_PATH . 'footer.php');
?>