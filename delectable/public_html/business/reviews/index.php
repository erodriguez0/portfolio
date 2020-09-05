<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

// Redirect if not logged in
if(!isset($_SESSION['manager'])):
	header('Location: /delectable/public_html/'); exit();

// Display dashboard if they have manager access
else:

$title = "Delectable | For Restaurants";
require_once(INCLUDE_PATH . 'header.php');
require_once(INCLUDE_PATH . 'business/manager/dashboard.php');
require_once(INCLUDE_PATH . 'functions.php');
$lid = $_SESSION['loc_id'];
$orders = restaurant_archived_orders($conn, $lid);
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	    <h1 class="h2">Reviews</h1>
	</div>
	<div class="manager-main row">
		<div class="col-12 col-lg-4 col-xl-3">
			<h1 class="h3 subheader-border">Comments</h1>

			<div id="order-list" class="review-row row no-gutters">
				<!-- Reivew Comments -->
			</div>
		</div>
		<div class="col-12 col-lg-8 col-xl-9">
			<h1 class="h3 subheader-border">Ratings</h1>
			<div class="mt-3">
				<button id="view-reviews" class="btn btn-primary btn-sm">View Reviews</button>
				<span class="ml-2"><small>Click On Table Then View Reviews</small></span>
			</div>
			<div id="canvas-wrap" class="mt-3 overflow-x">
                <canvas id="canvas" width="720" height="540"></canvas>
            </div>
            <div class="mt-3 d-flex align-items-center ml-2 ml-lg-0">
            	<span class="legend-box legend-1"></span><span class="ml-2">0.0-1.0</span>
            	<span class="legend-box legend-2 ml-2"></span><span class="ml-2">1.0-1.5</span>
            	<span class="legend-box legend-3 ml-2"></span><span class="ml-2">1.5-2.0</span>
            	<span class="legend-box legend-4 ml-2"></span><span class="ml-2">2.0-2.5</span>
            	<span class="legend-box legend-5 ml-2"></span><span class="ml-2">2.5-3.0</span>
            	<span class="legend-box legend-6 ml-2"></span><span class="ml-2">3.0-3.5</span>
            	<span class="legend-box legend-7 ml-2"></span><span class="ml-2">3.5-4.0</span>
            	<span class="legend-box legend-8 ml-2"></span><span class="ml-2">4.0-4.5</span>
            	<span class="legend-box legend-9 ml-2"></span><span class="ml-2">4.5-5.0</span>
			</div>
			<span><small>Based on overall rating</small></span>
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
<script type="text/javascript">
	var lid = <?php echo $lid; ?>;
</script>
<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>