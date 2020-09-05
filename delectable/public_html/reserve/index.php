<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(!isset($_SESSION['cust_id']) || !isset($_GET['lid']) || empty($_GET['lid']) || !ctype_digit($_GET['lid'])):
	header("Location: /delectable/public_html/"); exit();
else:
	$title = "Delectable | Make Your Reservation";
	require_once(INCLUDE_PATH . 'header.php');
	require_once(INCLUDE_PATH . 'navbar.php');
	require_once(INCLUDE_PATH . 'functions.php');
	$lid = $_GET['lid'];
	$schedule = restaurant_schedule($conn, $lid);
	// $taken = restaurant_rsvn($conn, $lid);
?>
<div class="container py-3">
	<div class="row after-nav">
		<div class="col-12 col-lg-9 order-2 order-lg-1">
			<div class="row no-gutters d-flex justify-content-center">
				<div class="col-6 col-md-5 col-lg-5 col-xl-4 mt-3 mt-lg-0">
					<div class="row no-gutters">
						<div class="col-8 d-flex justify-content-center">
							<input id="datepicker" width="170" placeholder="MM/DD/YYYY">
						</div>
						<div class="col-4 d-flex justify-content-center">
							<button id="update-date" class="btn btn-primary rounded border">Update</button>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-4 col-lg-3 mt-3 mt-lg-0">
					<select id="rsvn-time-select" class="form-control">
						<option value="0">Choose Date</option>
					</select>
				</div>
			</div>
			<div class="d-flex justify-content-center mt-3 overflow-x">
				<canvas id="canvas" width="720" height="540"></canvas>
			</div>
		</div>
		<div class="col-12 col-lg-3 order-1 order-lg-2 mt-md-3 mt-lg-0">
			<h1 class="h3 subheader-border mb-3">Reservation</h1>
				<label class="rsvn-checkout-label pr-2"><b>Place:</b></label>
				<label id="rsvn-restaurant">BAMBOO!</label>
				<br>
				<label class="rsvn-checkout-label pr-2"><b>Table:</b></label>
				<label id="rsvn-table">Pick A Table</label>
				<br>
				<label class="rsvn-checkout-label pr-2"><b>Date:</b></label>
				<label id="rsvn-time">Choose Date</label>
				<br>
				<label class="rsvn-checkout-label pr-2"><b>Time:</b></label>
				<label id="rsvn-time">Choose Time</label>
			<button id="reserve" class="btn btn-primary border rounded mt-3 btn-block">Reserve</button>
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