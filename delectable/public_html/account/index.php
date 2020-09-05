<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(isset($_SESSION['admin_id']) || isset($_SESSION['emp_id'])):
	header('Location: /delectable/public_html/'); exit();
elseif(isset($_SESSION['cust_id'])):

$title = "Delectable | Account";
$bodyClasses = "";
require_once(INCLUDE_PATH . 'header.php');
require_once(INCLUDE_PATH . 'navbar.php');
?>

<div class="wrapper mt-5 pt-3">
	<h4>Working</h4>
</div>

<?php
else:

$title = "Delectable | Login or Sign Up";
$bodyClasses = "";
require_once(INCLUDE_PATH . 'header.php');
require_once(INCLUDE_PATH . 'navbar.php');
?>

<div class="container h-100 mt-5 pt-5">
	<div class="form-wrap row justify-content-center">
		<div class="col-12 col-md-6 col-lg-5 col-xl-4">
			<h1 class="h3 subheader-border">Sign Up</h1>
			<div class="alert signup-alert alert-danger d-none"></div>
			<div class="row mt-3">
				<div class="col pr-1">
					<input id="add-customer-first-name" type="text" class="form-control rounded-0" placeholder="First Name" value="<?php echo $SUfname; ?>">
				</div>
				<div class="col pl-1">
					<input id="add-customer-last-name" type="text" class="form-control rounded-0" placeholder="Last Name" value="<?php echo $SUlname; ?>">
				</div>
			</div>
			<input id="add-customer-username" type="text" class="form-control rounded-0 mt-3" placeholder="Username" value="<?php echo $SUuname; ?>">
			<input id="add-customer-email" type="email" class="form-control rounded-0 mt-3" placeholder="Email Address" value="<?php echo $SUemail; ?>">
			<input id="add-customer-password-1" type="password" class="form-control rounded-0 mt-3" placeholder="Create Password">
			<input id="add-customer-password-2" type="password" class="form-control rounded-0 mt-3" placeholder="Confirm Password">
			<button id="add-customer-btn" class="btn btn-primary btn-block mt-3">Create Account</button>
		</div>
		<div class="col-12 col-md-6 col-lg-5 col-xl-4 mt-3 mt-md-0">
			<h1 class="h3 subheader-border">Login</h1>
			<div class="alert login-alert alert-danger d-none"></div>
			<input id="customer-username" type="text" class="form-control rounded-0 mt-3" placeholder="Username" value="<?php echo $LIuname; ?>">
			<input id="customer-password" type="password" class="form-control rounded-0 mt-3" placeholder="Password">
			<button id="customer-login-btn" class="btn btn-primary btn-block mt-3">Login</button>
		</div>
	</div>
</div>

<?php
unset($_SESSION['signup']);
unset($_SESSION['login']);
endif;
require_once(INCLUDE_PATH . 'footer.php');
?>