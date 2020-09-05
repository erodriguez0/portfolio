<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

// Redirect if not logged in
if(!isset($_SESSION['manager'])):
	header('Location: /delectable/public_html/'); exit();
// Display dashboard if they have manager access
else:

if(!isset($_GET['eid']) || empty($_GET['eid']) || !ctype_digit($_GET['eid'])):
$title = "Delectable | For Restaurants";
require_once(INCLUDE_PATH . 'header.php');
require_once(INCLUDE_PATH . 'business/manager/dashboard.php');
require_once(INCLUDE_PATH . 'functions.php');
$managers = restaurant_managers($conn, $_SESSION['loc_id']);
$employees = restaurant_employees($conn, $_SESSION['loc_id']);
?>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4 mb-3">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	    <h1 class="h2">Employees</h1>
	</div>
	<div class="manager-main row">
		<div class="col-12 col-lg-6 restaurant-edit-form-wrap">
			<h1 class="h3 subheader-border">Create Employee Account</h1>
			<div class="alert create-emp-alert d-none"></div>
			<div class="add-employee-form mt-3">
				<div class="row">
					<div class="col-12 col-lg-6">
						<input type="text" name="emp-first-name" class="form-control" placeholder="First Name" id="create-emp-first-name">
					</div>
					<div class="col-12 col-lg-6">
						<input type="text" name="emp-last-name" class="form-control mt-3 mt-lg-0" placeholder="Last Name" id="create-emp-last-name">
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<input type="text" name="emp-username" class="form-control" placeholder="Create Username" id="create-emp-username">
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<input type="email" name="emp-email" class="form-control" placeholder="Enter Email" id="create-emp-email">
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<input type="password" name="emp-password-1" class="form-control" placeholder="Create Password" id="create-emp-password-1">
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<input type="password" name="emp-password-2" class="form-control" placeholder="Confirm Password" id="create-emp-password-2">
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<input type="text" name="emp-job" class="form-control" placeholder="Job Title" id="create-emp-job">
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-6">
						<input type="number" name="emp-pay-rate" class="form-control" placeholder="$0.00" id="create-emp-pay" min="0.00" step="0.01" max="10000000">
					</div>
					<div class="col-6">
						<select name="emp-pay-rate-unit" class="form-control" id="create-emp-pay-rate">
							<option value="none">Choose Pay Rate</option>
							<optgroup label="Wage">
								<option value="hourly">Hourly</option>
							</optgroup>
							<optgroup label="Salary">
								<option value="weekly">Weekly</option>
								<option value="biweekly">Bi-weekly</option>
								<option value="semimonthly">Semi-monthly</option>
								<option value="monthly">Monthly</option>
								<option value="semiannual">Semi-Annual</option>
								<option value="annual">Annual</option>
							</optgroup>
						</select>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<label class="switch">
							<input id="create-emp-manager" type="checkbox" name="emp-manager-access">
							<span class="status-slider"></span>
						</label>
						<label class="ml-3">Grant Manager Access</label>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<button type="button" name="emp-create-account" class="btn btn-primary btn-block" id="create-employee-account">Create Account</button>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12 col-lg-6 restaurant-edit-form-wrap mt-3 mt-lg-0">
			<h1 class="h3 subheader-border">Managers</h1>
			<div id="manager-list-alert" class="alert d-none"></div>
			<div class="manager-list mt-3 overflow-x-fit" id="manager-list">
				<table class="table">
					<thead class="text-center">
						<th scope="col">Name</th>
						<th scope="col">Username</th>
						<th scope="col">Info</th>
						<th scope="col">Suspended</th>
						<th scope="col">Manager</th>
					</thead>
					<tbody class="text-center">
					<?php
					foreach ($managers as $k):
						if($k["emp_id"] != $_SESSION["emp_id"]):
							$name = $k["emp_first_name"] . " " . $k["emp_last_name"];
							$uname = $k["emp_username"];
							$eid = $k["emp_id"];
							$status = ($k["emp_status"] == 0) ? "checked" : "";
					?>
						<tr>
							<td><?php echo $name; ?></td>
							<td><?php echo $uname; ?></td>
							<td><a class="btn-link-alt border-0 text-link table-link" href="./?eid=<?php echo $eid; ?>">Profile</a></td>
							<td class="d-flex justify-content-center"><div class="custom-control custom-checkbox">
								<input class="custom-control-input" type="checkbox" name="" disabled="true" <?php echo $status; ?>>
								<label class="custom-control-label"></label>
							</div></td>
							<td><button class="btn-link-alt border-0 text-link table-link emp-revoke-manager" value="<?php echo $eid; ?>">Revoke</button></td>
						</tr>
					<?php
						endif;
					endforeach;
					?>
					</tbody>
				</table>
			</div>

			<h1 class="h3 subheader-border mt-3">Employees</h1>
			<div id="employee-list-alert" class="alert d-none"></div>
			<div class="employee-list mt-3 overflow-x-fit" id="employee-list">
				<table class="table">
					<thead class="text-center">
						<th scope="col">Name</th>
						<th scope="col">Username</th>
						<th scope="col">Info</th>
						<th scope="col">Suspended</th>
						<th scope="col">Manager</th>
					</thead>
					<tbody class="text-center">
					<?php
					foreach ($employees as $k):
						if($k["emp_id"] != $_SESSION["emp_id"]):
							$name = $k["emp_first_name"] . " " . $k["emp_last_name"];
							$uname = $k["emp_username"];
							$eid = $k["emp_id"];
							$status = ($k["emp_status"] == 0) ? "checked" : "";
					?>
						<tr>
							<td><?php echo $name; ?></td>
							<td><?php echo $uname; ?></td>
							<td><a class="btn-link-alt border-0 text-link table-link profile-link" href="./?eid=<?php echo $eid; ?>">Profile</a></td>
							<td class="d-flex justify-content-center"><div class="custom-control custom-checkbox">
								<input class="custom-control-input" type="checkbox" name="" disabled="true" <?php echo $status; ?>>
								<label class="custom-control-label"></label>
							</div></td>
							<td><button class="btn-link-alt border-0 text-link table-link emp-add-manager" value="<?php echo $eid; ?>">Grant</button></td>
						</tr>
					<?php
						endif;
					endforeach;
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>

<?php 
// Check if employee id is selected
elseif (isset($_GET['eid'])):
// Remove get var if invalid / redirect to employees page
if(empty($_GET['eid']) || !ctype_digit($_GET['eid'])):
	header('Location: ./'); exit();
endif;
$title = "Delectable | For Restaurants";
require_once(INCLUDE_PATH . 'functions.php');
$data = restaurant_emp_info($conn, $_GET['eid'], $_SESSION['loc_id']);
// LEFT JOIN review (if exists) AND reservation details
if($data["error"]):
	header('Location: ./'); exit();
endif;
require_once(INCLUDE_PATH . 'header.php');
require_once(INCLUDE_PATH . 'business/manager/dashboard.php');

$emp = $data["emp"];
$work = $data["work"];
$fname = htmlspecialchars($emp["emp_first_name"]);
$lname = htmlspecialchars($emp["emp_last_name"]);
$name = $fname . " " . $lname;
$uname = htmlspecialchars($emp["emp_username"]);
$email = htmlspecialchars($emp["emp_email"]);
$position = (isset($emp["emp_job"])) ? htmlspecialchars($emp["emp_job"]) : "N/A";
$manager = ($emp["emp_manager"]) ? "Yes" : "No";
$pay = (isset($emp["emp_pay"])) ? $emp["emp_pay"] : "N/A";
$rate = (isset($emp["emp_pay_rate"])) ? htmlspecialchars($emp["emp_pay_rate"]) : "N/A";
$hired = (isset($emp["emp_hire_date"])) ? htmlspecialchars($emp["emp_hire_date"]) : "N/A";
$dismissed = (isset($emp["emp_dismissed"])) ? htmlspecialchars($emp["emp_dismissed"]) : "N/A";
$form_position = ($position == "N/A") ? "" : $position;
$form_manager = ($manager == "No") ? "" : "checked";
$form_pay = ($pay == "N/A") ? "" : $pay;
$form_rate = ($rate == "N/A") ? "" : $rate;
$add1 = (isset($emp["emp_address_1"])) ? htmlspecialchars($emp["emp_address_1"]) : "";
$add2 = (isset($emp["emp_address_2"])) ? htmlspecialchars($emp["emp_address_2"]) : "";
$city = (isset($emp["emp_city"])) ? htmlspecialchars($emp["emp_city"]) : "";
$state = (isset($emp["emp_state"])) ? htmlspecialchars($emp["emp_state"]) : "";
$zip = (isset($emp["emp_postal_code"])) ? htmlspecialchars($emp["emp_postal_code"]) : "";
$phone = (isset($emp["emp_phone"])) ? htmlspecialchars($emp["emp_phone"]) : "";
$address = $add1 . " " . $add2 . " " . $city . " " . $state . " " . $zip;
$concat_add = (empty(trim($address))) ? "N/A" : $address;

?>
<main role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4 mb-3">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	    <h1 class="h2">Employee Profile</h1>
	    <a class="btn btn-alt" href="./">< Back</a>
	</div>
	<div class="manager-main row">
		<div class="col-12 col-lg-6 restaurant-edit-form-wrap">
			<h1 class="h3 subheader-border">Employee Details</h1>
			<!-- Form to update profile/account -->
			<div class="alert update-emp-alert d-none"></div>

			<div class="profile-img-wrap mt-3">
				<img src="https://via.placeholder.com/150" class="profile-img">
			</div>

			<!-- Profile - At a glance -->
			<div class="row no-gutters mt-3">
				<div class="col-2">
					<label class="bg-light w-100 profile-label">Name: </label>
					<br>
					<label class="profile-label">Username: </label>
					<br>
					<label class="bg-light w-100 profile-label">Email: </label>
					<br>
					<label class="profile-label">Position: </label>
					<br>
					<label class="bg-light w-100 profile-label">Manager: </label>
					<br>
					<label class="profile-label">Pay: </label>
					<br>
					<label class="bg-light w-100 profile-label">Pay Rate: </label>
					<br>
					<label class="profile-label">Hired: </label>
					<br>
					<label class="bg-light w-100 profile-label">Dismissed: </label>
					<br>
					<label class="profile-label">Address: </label>
				</div>

				<div class="col-10 border-left">
					<label class="bg-light w-100 pl-2 profile-label"><?php echo $name; ?></label>
					<br>
					<label class="pl-2 profile-label"><?php echo $uname; ?></label>
					<br>
					<label class="bg-light w-100 pl-2 profile-label"><?php echo $email; ?></label>
					<br>
					<label class="pl-2 profile-label"><?php echo $position; ?></label>
					<br>
					<label class="bg-light w-100 pl-2 profile-label"><?php echo $manager; ?></label>
					<br>
					<label class="pl-2 profile-label"><?php echo $pay; ?></label>
					<br>
					<label class="bg-light w-100 pl-2 profile-label"><?php echo ucfirst($rate); ?></label>
					<br>
					<label class="pl-2 profile-label"><?php echo $hired; ?></label>
					<br>
					<label class="bg-light w-100 pl-2 profile-label"><?php echo $dismissed; ?></label>
					<br>
					<label class="pl-2 profile-label"><?php echo $concat_add; ?></label>
				</div>
			</div>			

			<!-- Accordion with update forms -->
			<div id="accordion">
				<!-- UPDATE ACCOUNT -->
				<div class="card border-0">
					<div class="card-header p-0 bg-transparent border-top">
						<button class="btn btn-link-alt table-link text-link text-capitalize px-0 btn-block text-left h-100" data-toggle="collapse" data-target="#change-account-info">
							<span class="float-left">Update Account</span>
							<i class="fas fa-angle-down float-right pt-1"></i>
						</button>
					</div>
					<div id="change-account-info" class="collapse" data-parent="#accordion">
						<div class="card-body px-0">
							<div class="row">
								<div class="col-12 col-lg-6">
									<input type="text" name="emp-first-name" class="form-control" placeholder="First Name" id="update-emp-first-name" value="<?php echo $fname; ?>">
								</div>
								<div class="col-12 col-lg-6">
									<input type="text" name="emp-last-name" class="form-control mt-3 mt-lg-0" placeholder="Last Name" id="update-emp-last-name" value="<?php echo $fname; ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<input type="email" name="emp-email" class="form-control" placeholder="Enter Email" id="update-emp-email" value="<?php echo $email; ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<input type="text" name="emp-job" class="form-control" placeholder="Job Title" id="update-emp-job"
									 value="<?php echo $form_position; ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-6">
									<input type="number" name="emp-pay-rate" class="form-control" placeholder="$0.00" id="update-emp-pay" min="0.00" step="0.01" max="10000000"
									 value="<?php echo $form_pay; ?>">
								</div>
								<div class="col-6">
									<select name="emp-pay-rate-unit" class="form-control" id="update-emp-pay-rate" value="<?php echo $form_rate; ?>">
										<option value="none">Choose Pay Rate</option>
										<optgroup label="Wage">
											<option value="hourly">Hourly</option>
										</optgroup>
										<optgroup label="Salary">
											<option value="weekly">Weekly</option>
											<option value="biweekly">Bi-weekly</option>
											<option value="semimonthly">Semi-monthly</option>
											<option value="monthly">Monthly</option>
											<option value="semiannual">Semi-Annual</option>
											<option value="annual">Annual</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<label class="switch">
										<input id="update-emp-manager" type="checkbox" name="emp-manager-access" <?php echo $form_manager; ?>>
										<span class="status-slider"></span>
									</label>
									<label class="ml-3">Grant Manager Access</label>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<button type="button" name="emp-create-account" class="btn btn-primary btn-block" id="create-employee-account">Update Account</button>
								</div>
							</div>
						</div>
						<!-- ./Card Body -->
					</div>
					<!-- ./Collapse -->
				</div>
				<!-- ./Card -->

				<!-- UPDATE CONTACT INFO -->
				<div class="card border-0">
					<div class="card-header p-0 bg-transparent">
						<button class="btn btn-link-alt table-link text-link text-capitalize px-0 btn-block text-left" data-toggle="collapse" data-target="#change-contact-info">
							<span class="float-left">Update Contact Info</span>
							<i class="fas fa-angle-down float-right pt-1"></i>
						</button>
					</div>
					<div id="change-contact-info" class="collapse" data-parent="#accordion">
						<div class="card-body px-0">
							<div class="row">
								<div class="col-12">
									<input type="text" name="emp-address-1" class="form-control" placeholder="Address" value="<?php echo $add1; ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12 col-lg-4">
									<input type="text" name="emp-address-2" class="form-control" placeholder="Apt/Ste" value="<?php echo $add2; ?>">
								</div>
								<div class="col-12 col-lg-5">
									<input type="tel" name="emp-phone" class="form-control mt-3 mt-lg-0" placeholder="Phone #" value="<?php echo $phone; ?>">
								</div>
								<div class="col-12 col-lg-3">
									<input type="text" name="emp-postal-code" class="form-control mt-3 mt-lg-0" placeholder="Zip" value="<?php echo $zip; ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12 col-lg-8">
									<input type="text" name="emp-city" class="form-control" placeholder="City" value="<?php echo $city; ?>">
								</div>
								<div class="col-12 col-lg-4">
									<input type="text" name="emp-state" class="form-control mt-3 mt-lg-0" placeholder="State" value="<?php echo $state; ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<button type="button" name="emp-create-account" class="btn btn-primary btn-block" id="create-employee-account">Update Contact Info</button>
								</div>
							</div>
						</div>
						<!-- ./Card Body -->
					</div>
					<!-- ./Collapse -->
				</div>
				<!-- ./Card -->

				<!-- UPDATE USERNAME -->
				<div class="card border-0">
					<div class="card-header p-0 bg-transparent">
						<button class="btn btn-link-alt table-link text-link text-capitalize px-0 btn-block text-left" data-toggle="collapse" data-target="#change-username">
							<span class="float-left">Change Username</span>
							<i class="fas fa-angle-down float-right pt-1"></i>
						</button>
					</div>
					<div id="change-username" class="collapse" data-parent="#accordion">
						<div class="card-body px-0">
							<div class="row">
								<div class="col-12">
									<input type="text" name="emp-username" class="form-control" placeholder="Change Username" id="update-emp-username" value="<?php echo $uname; ?>">
								</div>
							</div>

							<div class="row mt-3">
								<div class="col-12">
									<button type="button" name="emp-create-account" class="btn btn-primary btn-block" id="update-employee-username">Update Username</button>
								</div>
							</div>
						</div>
						<!-- ./Card Body -->
					</div>
					<!-- ./Collapse -->
				</div>
				<!-- ./Card -->

				<!-- UPDATE PASSWORD -->
				<div class="card border-0">
					<div class="card-header p-0 bg-transparent">
						<button class="btn btn-link-alt table-link text-link text-capitalize px-0 btn-block text-left" data-toggle="collapse" data-target="#change-password">
							<span class="float-left">Change Password</span>
							<i class="fas fa-angle-down float-right pt-1"></i>
						</button>
					</div>
					<div id="change-password" class="collapse" data-parent="#accordion">
						<div class="card-body px-0">
							<div class="row">
								<div class="col-12">
									<input type="password" name="emp-password-1" class="form-control" placeholder="Current Password" id="update-emp-password-0">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<input type="password" name="emp-password-1" class="form-control" placeholder="Create Password" id="update-emp-password-1">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<input type="password" name="emp-password-2" class="form-control" placeholder="Confirm Password" id="update-emp-password-2">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-12">
									<button type="button" name="emp-update-password" class="btn btn-primary btn-block" id="update-employee-password">Update Password</button>
								</div>
							</div>
						</div>
						<!-- ./Card Body -->
					</div>
					<!-- ./Collapse -->
				</div>
				<!-- ./Card -->
			</div>
			<!-- ./Accordion -->
		</div>
		<!-- ./Col -->

<?php
$overall_rating_sum = 0;
$food_rating_sum = 0;
$service_rating_sum = 0;
$count = 0;
foreach($work as $k):
	if(isset($k["review_rating"])):
		$count++;
		$overall_rating_sum += $k["review_rating"];
		$food_rating_sum += $k["review_food_rating"];
		$service_rating_sum += $k["review_service_rating"];
	endif;
endforeach;
if($count > 0):
	$overall_avg = round($overall_rating_sum / $count, 2);
	$food_avg = round($food_rating_sum / $count, 2);
	$service_avg = round($service_rating_sum / $count, 2);
	$overall_bg = convertAvgToHexColorClass($overall_avg);
	$food_bg = convertAvgToHexColorClass($food_avg);
	$service_bg = convertAvgToHexColorClass($service_avg);
else:
	$overall_avg = "N/A";
	$food_avg = "N/A";
	$service_avg = "N/A";
	$overall_bg = "bg-dark";
	$food_bg = "bg-dark";
	$service_bg = "bg-dark"; 
endif;
?>

		<div class="col-12 col-lg-6 restaurant-edit-form-wrap mt-3 mt-lg-0">
			<h1 class="h3 subheader-border">Rating</h1>
			<div id="overall-emp-rating" class="my-3 d-flex justify-content-between px-5">
				<!-- Display rating in rating-based color -->
				<div class="box-score-wrap d-inline-block rounded <?php echo $overall_bg; ?>">
					<div class="box-score d-flex justify-content-center align-items-center flex-column">
						<span class="line-height-one"><?php echo $overall_avg; ?></span>
						<small>Overall</small>
					</div>
				</div>
				<div class="box-score-wrap d-inline-block rounded <?php echo $food_bg; ?>">
					<div class="box-score d-flex justify-content-center align-items-center flex-column">
						<span class="line-height-one"><?php echo $food_avg; ?></span>
						<small>Food</small>
					</div>
				</div>
				<div class="box-score-wrap d-inline-block rounded <?php echo $service_bg; ?>">
					<div class="box-score d-flex justify-content-center align-items-center flex-column">
						<span class="line-height-one"><?php echo $service_avg; ?></span>
						<small>Service</small>
					</div>
				</div>
			</div>
			<div class="text-center">
				<small>Based on reservations they worked on and have customer feedback</small>
			</div>

			<h1 class="h3 subheader-border mt-3">Assigned Work</h1>
			<div id="order-list" class="emp-rsvn-list row no-gutters">
				<!-- Show all work assigned as display any ratings if any -->
				<?php
				if(!empty($work)):
				foreach($work AS $k):
				$overall = (isset($k["review_rating"])) ? $k["review_rating"] : "";
				$food = (isset($k["review_food_rating"])) ? $k["review_food_rating"] : "";
				$service = (isset($k["review_service_rating"])) ? $k["review_service_rating"] : "";
				$comment = (isset($k["review_text"])) ? htmlspecialchars($k["review_text"]) : "";
				$fname = htmlspecialchars($k["cust_first_name"]);
				$lname = htmlspecialchars($k["cust_last_name"]);
				$name = $fname . " " . $lname;
				$date = date("m-d-Y", strtotime($k["rsvn_date"]));
				$rsvn_id = $k["rsvn_id"];
				?>
				<div class="col-12 emp-rsvn-header mt-1">
					<div class="rsvn-customer d-flex align-items-center">
						<span><?php echo $name; ?></span> 
						<span class="mx-1"> | </span>
						<span><?php echo $date; ?></span> 
						<span class="mx-1"> | </span>
						<button class="btn btn-link-alt btn-sm table-link text-link text-capitalize px-0 order-row" value="<?php echo $rsvn_id; ?>" data-toggle="modal" data-target=".rsvn-modal">View Details</button>
					</div>
					<?php if(!empty($overall)): ?>
					<div class="rsvn-rating d-flex align-items-center pb-1">
						<span class="mr-1">Overall: </span>
						<?php echo rating_to_stars($overall); ?>

						<span class="mx-1"> | </span>

						<span class="mr-1">Food: </span>
						<?php echo rating_to_stars($food); ?>

						<span class="mx-1"> | </span>

						<span class="mr-1">Service: </span>
						<?php echo rating_to_stars($service); ?>
					</div>
					<!-- ./Rating -->
					<div class="rsvn-comment">
						<button class="btn btn-link-alt table-link text-link text-capitalize px-0 pt-0 btn-block text-left h-100 btn-sm comment-collapse">
							<span class="float-left">Feedback</span>
							<i class="fas fa-angle-down float-right pt-1"></i>
						</button>
						<div class="collapse bg-light">
							<p class="mb-0 px-3 py-2"><?php echo $comment; ?></p>
						</div>
					</div>
					<!-- ./RSVN Comment -->
					<?php else: ?>
					<div class="rsvn-rating d-flex align-items-center pb-1">
						<span>Customer did not provide feedback</span>
					</div>
					<?php endif; ?>
				</div>
				<!-- ./RSVN row -->
				<?php 
				endforeach;
				else: 
				?>

				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<div class="mt-3 modal fade rsvn-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title rsvn-modal-title">RSVN# </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h1 class="h3 subheader-border">Reservation Details</h1>
				<div id="order-form" class="d-none">
					<div class="row">
						<div class="col-12">

				    		<div class="invoice-details">
				    			<div class="row">

				    				<div class="col-6 col-md-4 mb-3 mb-md-0">
				    					<address class="mb-0">
				    						<strong id="cust-name"></strong><br>
				    						<span id="cust-address-1"></span><br>
				    						<span id="cust-address-2"></span><br>
				    						<span id="cust-phone"></span><br>
				    					</address>
				    				</div>

				    				<div class="col-6 col-md-4 mb-3 mb-md-0">
				    					<!-- <b>Payment Method:</b><br>
				    					<span>Visa **** 1234</span><br> -->
				    					<b>Email:</b><br>
										<span id="cust-email"></span>
				    				</div>

				    				<div class="col-12 col-md-4">
				    					<b>Order Date:</b><br>
				    					<span id="order-created"></span><br>

				    					<b>Reservation Date:</b><br>
				    					<span id="rsvn-date"></span><br>

				    					<b>Reserved:</b><br>
				    					<span id="table-number"></span><br>
				    				</div>

				    			</div>

				    			<div class="row mt-3">

				    				<div class="col-12 staff-row">

				    					<b>Staff</b>
				    					<table id="rsvn-staff" class="table">
				    						<thead>
				    							<th scope="col">Name</th>
				    							<th scope="col">Position</th>
				    							<th scope="col">Link</th>
				    						</thead>
				    						<tbody>
				    							
				    						</tbody>
				    					</table>

				    				</div>

				    			</div>

				    			<div class="row mt-3">

				    				<div class="col-12 order-items-row">

				    					<b>Order Items</b>
				    					<table class="table order-items">
				    						<thead>
				    							<th scope="col">Item</th>
				    							<th scope="col">Price</th>
				    							<th scope="col">Qty</th>
				    							<th scope="col">Total</th>
				    						</thead>

				    						<tbody>
				    							<tr>
				    								<td>Super Banger Burger</td>
				    								<td>$4.99</td>
				    								<td>2</td>
				    								<td>$9.98</td>
				    							</tr>
				    						</tbody>
				    					</table>

				    				</div>

				    			</div>

				    		</div>

				    	</div>
					</div>
				</div>
				<!-- ./Order Form -->
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<script type="text/javascript">
	var lid = <?php echo (isset($_SESSION['loc_id'])) ? $_SESSION['loc_id'] : 0; ?>;
</script>
<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>