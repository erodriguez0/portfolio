<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(!$_SESSION['admin_id']):
	header('location: /delectable/public_html');
else:

$title = "Delectable | Edit Employee";
require_once(INCLUDE_PATH . 'header.php');

require_once(INCLUDE_PATH . '/admin/dashboard.php');

require_once(INCLUDE_PATH . 'functions.php');

$id = $_GET['eid'];
$emp = employee_info($conn, $id);
$status = ($emp['emp_status']) ? 'Active' : 'Suspended';
$check = ($emp['emp_status']) ? 'checked' : '';
$fname = $emp['emp_first_name'];
$lname = $emp['emp_last_name'];
$uname = $emp['emp_username'];
$email = $emp['emp_email'];
$addr1 = (isset($emp['emp_address_1'])) ? $emp['emp_address_1'] : '';
$addr2 = (isset($emp['emp_address_2'])) ? $emp['emp_address_2'] : '';
$phone = (isset($emp['emp_phone'])) ? $emp['emp_phone'] : '';
$city = (isset($emp['emp_city'])) ? $emp['emp_city'] : '';
$state = (isset($emp['emp_state'])) ? $emp['emp_state'] : '';
$zip = (isset($emp['emp_postal_code'])) ? $emp['emp_postal_code'] : '';
$res = $emp['res_name'];
$add1 = $emp['loc_address_1'] . ' ' . $emp['loc_address_2'];
$add2 = $emp['loc_city'] . ', ' . $emp['loc_state'] . ' ' . $emp['loc_postal_code'];
$add3 = $emp['loc_phone'];
$res_url = '/delectable/public_html/admin/restaurants/edit/index.php?lid=' . $emp['loc_id'];
?>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Employee</h1>
        <a class="btn btn-alt" href="../">< Back</a>
    </div>

    <div class=" mb-3">
    	<div class="row">
    		<div class="col-12 col-lg-6 restaurant-edit-form-wrap">
    			<h1 class="h3 subheader-border">Info</h1>
                <div class="alert emp-update-alert d-none">
                    
                </div>
                <div class="row mt-3">
                	<div class="col-12">
                		<img id="profile-img" class="emp-profile-img img-fluid mx-auto" src="https://i.pinimg.com/originals/0d/36/e7/0d36e7a476b06333d9fe9960572b66b9.jpg">
                	</div>
                </div>
                <div class="row mt-3">
                	<div class="col-12">
                		<h6>Status</h6>
                		<label class="switch">
							<input id="edit-emp-status" type="checkbox" <?php echo $check; ?>>
							<span class="status-slider"></span>
						</label>
						<label id="emp-status-label"><?php echo $status; ?></label>
                	</div>
                </div>
                <div class="row mt-3">
	                <div class="col-12 col-md-6">
	                    <h6>First Name</h6>
	                    <input id="edit-emp-fname" class="form-control" type="text" name="emp-first-name" value="<?php echo $fname; ?>">
	                </div>
	                <div class="col-12 col-md-6 mt-3 mt-md-0">
	                    <h6>Last Name</h6>
	                    <input id="edit-emp-lname" class="form-control" type="text" name="emp-last-name" value="<?php echo $lname; ?>">
	                </div>
	            </div>
                <div class="row mt-3">
                	<div class="col-12">
	                    <h6>Username</h6>
	                    <input id="edit-emp-uname" class="form-control" type="text" value="<?php echo $uname; ?>" name="emp-username">
                	</div>
                </div>
                <div class="row mt-3">
                	<div class="col-12">
                		<h6>Email</h6>
	                    <input id="edit-emp-email" class="form-control" type="text" value="<?php echo $email; ?>" name="emp-email">
	                </div>
	            </div>
                <div class="row mt-3">
                	<div class="col-12">
	                    <h6>Address 1</h6>
	                    <input id="edit-emp-add1" class="form-control" type="text" name="emp-address-1" value="<?php echo $addr1; ?>">
	                </div>
                </div>
                <div class="row mt-3">
	                <div class="col-12 col-md-6">
	                    <h6>Address 2</h6>
	                    <input id="edit-emp-add2" class="form-control" type="text" name="emp-address-2" value="<?php echo $addr2; ?>">
	                </div>
	                <div class="col-12 col-md-6 mt-3 mt-md-0">
	                    <h6>Phone</h6>
	                    <input id="edit-emp-phone" class="form-control" type="text" name="emp-phone" value="<?php echo $phone; ?>">
	                </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-5">
                        <h6>City</h6>
                        <input id="edit-emp-city" class="form-control" type="text" name="emp-city" value="<?php echo $city; ?>">
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <h6>State</h6>
                        <input id="edit-emp-state" class="form-control" type="text" name="emp-state" value="<?php echo $state; ?>">
                    </div>
                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <h6>Zip</h6>
                        <input id="edit-emp-postal-code" class="form-control" type="text" name="emp-postal-code" value="<?php echo $zip; ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button id="emp-update-btn" class="btn btn-primary btn-block" name="emp-update-btn">Update Profile</button>
                    </div>
                </div>
    		</div>

    		<div class="col-12 col-lg-6 restaurant-edit-form-wrap mt-3 mt-lg-0">
    			<h1 class="h3 subheader-border">Workplace</h1>
                <div class="row mt-3">
                    <div class="col-12">
                        <address>
                            <strong><?php echo $res; ?></strong><br>
                            <?php echo $add1; ?><br>
                            <?php echo $add2; ?><br>
                            <?php echo $add3; ?>
                        </address>
                        <div class="btn-group">
                            <button id="toggle-res-search" class="btn btn-primary">Edit</button>
                            <a class="btn btn-alt" href="<?php echo $res_url; ?>">View Location</a>
                        </div>
                        <div id="show-res-search-form" style="display: none;">
                            <div class="row mt-3">
                                <div class="col-12">
                                    <input type="text" class="form-control" name="res-search" placeholder="Search restaurants">

                                    <table class="table mt-3">
                                        <thead>
                                            <th scope="col">Restaurant</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">View</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle">
                                                    BANGABURGER
                                                </td>
                                                <td class="align-middle">
                                                    2550 California Ave<br>
                                                    Suite #201<br>
                                                    Bakersfield, California
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn btn-primary text-white btn-sm">View</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    		</div>
    	</div>
    </div>
</main>

<script type="text/javascript">
    var eid = <?php echo $id; ?>;
</script>

<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>