<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(isset($_SESSION['admin_id']) || isset($_SESSION['emp_id']) || isset($_SESSION['cust_id'])):
	header('Location: /delectable/public_html/');
else:

$title = "Delectable | Admin Portal";
require_once(INCLUDE_PATH . 'header.php');
?>
<nav id="home-nav" class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
    <div class="container">
        <a class="navbar-brand text-uppercase" href="/delectable/public_html/">Delectable</a>
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
                    <a class="nav-link" href="/delectable/public_html/account/">Login | Sign Up</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="row mx-0 mt-5">
	<div class="col-12">
		<h1 class="text-center py-3">Admin Portal</h1>
		<form method="POST" action="/delectable/resources/scripts/admin-login.php">
			<div class="row">
				<div class="col-12">
					<div class="admin-form-wrap mx-auto">
						<?php if(isset($_SESSION['error'])): ?>
							<div class="alert alert-danger">
								<?php echo $_SESSION['error']; ?>
							</div>
						<?php endif; ?>
						<input class="form-control mb-3" type="text" placeholder="Username" value="<?php echo (isset($_SESSION['login']['uname'])) ? $_SESSION['login']['uname'] : ""; ?>" name="admin-username" required>
						<input class="form-control mb-3" type="password" placeholder="Password" name="admin-password" required>
						<input class="btn btn-primary btn-block" type="submit" value="Login" name="admin-login">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<?php
unset($_SESSION['error']);
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>