<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

// Redirect if not logged in
if(isset($_SESSION['admin_id']) || !isset($_SESSION['emp_id'])):
	header('Location: /delectable/public_html/');

// Display message if no manager access granted
elseif(isset($_SESSION['emp_id']) && !isset($_SESSION['manager'])):
	header('Location: /delectable/public_html/business/dashboard/');

// Display dashboard if they have manager access
else:

$title = "Delectable | For Restaurants";
require_once(INCLUDE_PATH . 'header.php');
require_once(INCLUDE_PATH . 'business/manager/dashboard.php');
require_once(INCLUDE_PATH . 'functions.php');
$lid = $_SESSION['loc_id'];
$orders = restaurant_pending_orders($conn, $lid);
// var_dump($orders);
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	    <h1 class="h2">Orders</h1>
	</div>
	<div class="manager-main row">
		<div class="col-12 col-lg-4 col-xl-3">
			<h1 class="h3 subheader-border">Recent Reservations</h1>

			<table id="order-list" class="table text-center">
				<thead>
					<th scope="col">RSVN</th>
					<th scope="col">Date</th>
					<th scope="col">Time</th>
					<th scope="col">Link</th>
				</thead>
				<tbody>
					<?php
					foreach($orders as $o):
					$oid = $o["order_id"];
					$rdate = $o["rsvn_date"];
					$rtime = date("h:i A", strtotime($o["rsvn_slot"]));
					$rsvn_id = $o["rsvn_id"];
					?>
					<tr>
						<td><?php echo $rsvn_id; ?></td>
						<td><?php echo $rdate; ?></td>
						<td><?php echo $rtime; ?></td>
						<td><button class="order-row btn-link-alt table-link text-link rounded btn-sm py-0" value="<?php echo $rsvn_id; ?>">View</button></td>
					</tr>
					<?php
					endforeach;
					?>
				</tbody>
			</table>
		</div>

		<div class="col-12 col-lg-8 col-xl-9 mt-3 mt-lg-0">
			<h1 class="h3 subheader-border invoice-title">Reservation Details</h1>
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
			    					<button class="btn btn-primary rounded btn-sm py-0 ml-2 mb-1 assign-staff" data-toggle="modal" data-target="#assign-staff-modal">
			    						Assign
			    					</button>
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
			    					<button class="btn btn-primary rounded btn-sm py-0 ml-2 mb-1 assign-staff">
			    						Add Items
			    					</button>

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
		<!-- ./Reservation Details -->
	</div>
	<!-- ./Row -->
</main>

<!-- Modal -->
<div class="modal fade" id="assign-staff-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Assign Staff</h5>
				<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table id="modal-assign-staff-table" class="table">
					<thead>
						<th scope="col">Name</th>
						<th scope="col">Position</th>
						<th scope="col">Add</th>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-alt" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary assign-staff-submit">Submit</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var lid = <?php echo $_SESSION['loc_id']; ?>;
</script>
<?php
endif;
require_once(INCLUDE_PATH . 'footer.php');
?>