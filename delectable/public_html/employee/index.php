<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/inc/config.php');

$title = "Delectable | For Restaurants";
require_once(INCLUDE_PATH . 'header.php');

if(!$_SESSION['admin_id']):
?>

<!-- Viewport Cover -->
<div class="row fit-viewport mx-auto">

	<!-- Column With Background Image (Viewport Cover) -->
	<div class="col-12 welcome-bg px-0">

		<!-- Overlay To Darken Image -->
		<div class="overlay">		

			<!-- Container For Landing Page Content -->
			<!-- Centered Vertically And Horizontally -->
			<div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center">

				<!-- Company Name -->
				<h1 class="welcome-title text-center">Delectable For Business</h1>

				<!-- Login/Sign In -->
				<div class="welcome-btn-group pt-3">
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#create-account-modal">Register Your Restaurant or Login</button>

					<!-- Create Account/Login Modal -->
					<div class="modal fade" id="create-account-modal" tabindex="-1" role="dialog">
						<!-- Large Modal -->
						<div class="modal-dialog modal-dialog-centered modal-lg">
							<div class="modal-content">
								<!-- Header -->
								<div class="modal-header">
									<h5 class="modal-title">Create Account or Login</h5>
									<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
								</div>

								<!-- Modal Body -->
								<div class="modal-body">

									<!-- Fit Content To Modal -->
									<!-- Two Forms / Two Columns -->
									<div class="container-fluid">
										<div class="row">

											<!-- Create Account Form -->
											<form class="col-12 col-lg-6 order-2 order-lg-1" method="POST" action="/delectable/resources/inc/scripts/restaurant-create-account.php">
												<!-- Name Fields -->
												<div class="row pt-4">
													<div class="col-6">
														<input type="text" class="form-control <?php echo ($_SESSION['error']['fname']) ? 'is-invalid' : ''; ?>" name="first-name" placeholder="First Name" value="<?php echo $_SESSION['create']['fname']; ?>" required>
													</div>
													<div class="col-6">
														<input type="text" class="form-control <?php echo ($_SESSION['error']['lname']) ? 'is-invalid' : ''; ?>" name="last-name" placeholder="Last Name" value="<?php echo $_SESSION['create']['lname']; ?>">
													</div>
												</div>
												<!-- Username -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="text" class="form-control <?php echo ($_SESSION['error']['uname']) ? 'is-invalid' : ''; ?>" name="username" placeholder="Userame" value="<?php echo $_SESSION['create']['uname']; ?>">
													</div>
												</div>
												<!-- Email -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="email" class="form-control <?php echo ($_SESSION['error']['emailw']) ? 'is-invalid' : ''; ?>" name="email" placeholder="Email" value="<?php echo $_SESSION['create']['email']; ?>">
													</div>
												</div>
												<!-- Create Password -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="password" class="form-control" name="create-password" placeholder="Create Password">
													</div>
												</div>
												<!-- Confirm Password -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="password" class="form-control" name="confirm-password" placeholder="Confirm Password">
													</div>
												</div>
												<!-- Submit Create Account Form -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="submit" class="btn btn-primary btn-block" name="restaurant-create-account" value="Create Account">
													</div>
												</div>
											</form>

											<!-- Login Form -->
											<form class="col-12 col-lg-6 order-1 order-lg-2">
												<!-- Username -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="text" class="form-control <?php echo $_SESSION['error']['uname']; ?>" name="username" placeholder="Userame" value="<?php echo $_SESSION['form']['uname']; ?>">
													</div>
												</div>
												<!-- Password -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="password" class="form-control" name="password" placeholder="Password">
													</div>
												</div>
												<!-- Submit Login Form -->
												<div class="row pt-4">
													<div class="col-12">
														<input type="submit" class="btn btn-primary btn-block" name="restaurant-login-account" value="Login">
													</div>
												</div>
											</form>
										</div>
										<!-- ./Row -->
									</div>
									<!-- ./Container Fluid -->
								</div>
								<!-- ./Modal Body -->

								<!-- Modal Footer -->
								<!-- TODO: Links for restaurant owners etc. -->
								<div class="modal-footer mr-auto">
									<small class="text-danger"><?php echo $_SESSION['error']['footer']; ?></small>
								</div>
							</div>
						</div>
					</div>

				</div>

				<!-- Company Statement -->
				<div class="welcome-about text-white pt-5 px-5 px-md-4 px-lg-2 px-xl-0">
					<div class="paragraph-container mx-auto">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In gravida elit metus, quis aliquet arcu blandit id. Duis eu diam gravida eros ornare imperdiet. Etiam in nisl sollicitudin, mollis nunc eget, condimentum elit. Vivamus a rutrum mauris. Nam ac ligula scelerisque, vestibulum lacus sed, rhoncus mi.</p>
					</div>
				</div>
			</div>
			<!-- ./Container -->
		</div>
		<!-- ./Overlay -->
	</div>
	<!-- ./Col -->
</div>
<!-- ./Row -->

<?php
endif;

// Unset sticky form input and errors
unset($_SESSION['error']);
unset($_SESSION['create']);

require_once(INCLUDE_PATH . 'footer.php');
?>