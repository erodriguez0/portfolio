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
require_once(INCLUDE_PATH . 'functions.php');
require_once(INCLUDE_PATH . 'business/manager/dashboard.php');
$cats = menu_item_categories($conn, $_SESSION['loc_id']);
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 py-3 px-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	    <h1 class="h2">Menu</h1>
	</div>
	<div class="manager-main row">
		<div class="col-12 col-lg-6 restaurant-edit-form-wrap">

			<div class="row">
                <div class="col-12">

                	<div class="add-category-wrapper">
                		<h1 class="h3 subheader-border">Add Category</h1>
                		<div class="alert add-cat-alert d-none"></div>
                		<div class="menu-add-form mt-3">
                			<input id="add-category-name" class="form-control rounded-0 mt-3" type="text" name="add-category-name" placeholder="Category name...">
                			<span id="cat-name-counter">32</span><span> characters remaining</span>
                			<textarea id="add-category-desc" class="form-control rounded-0 mt-3" name="add-category-desc" placeholder="Category description..." rows="5" style="resize: none;"></textarea>
                			<span id="cat-text-counter">255</span><span> characters remaining</span>
                			<button id="add-category-btn" class="btn btn-primary btn-block mt-3" role="button">Add Category</button>
                		</div>
                	</div>

                	<!-- Add Category Form -->
                	<div class="add-item-wrapper mt-3">
	                	<h1 class="h3 subheader-border">Add Item</h1>
	                	<div class="alert add-item-alert d-none"></div>
	                	<div class="menu-add-form mt-3">
	                		<select id="add-item-category" class="w-100 custom-select rounded-0" name="add-item-category">
	                			<option value="0">Choose category...</option>
	                		<?php
	                		if(!empty($cats)):
	                			foreach($cats as $k):
	                				$cid = $k["item_cat_id"];
	                				$name = htmlspecialchars($k["item_cat_name"]);
	                		?>
	                			<option value="<?php echo $cid; ?>"><?php echo $name; ?></option>
	                		<?php
                				endforeach;
	                		endif;
	                		?>
	                		</select>
	                		<div class="row">
		                		<div class="col-12 col-lg-8">
		                			<input id="add-item-name" class="form-control rounded-0 mt-3" type="text" name="add-item-name" placeholder="Item name...">
		                			<span id="add-item-name-counter">32</span><span> characters remaining</span>
		                		</div>
		                		<div class="col-12 col-lg-4">
		                			<input id="add-item-price" class="form-control rounded-0 mt-3" type="number" name="add-item-price" placeholder="$0.00" min="0.00" max="10000.00" step="0.01">
		                		</div>
		                	</div>
	                		<textarea id="add-item-description" class="form-control rounded-0 mt-3" name="add-item-description" placeholder="Item description..." rows="6"></textarea>
	                		<span id="item-desc-counter">255</span><span> characters remaining</span>
	                		<div class="custom-file mt-3">
								<input type="file" id="add-item-image" class="custom-file-input" id="customFile" accept=".png, .jpg, .jpeg">
								<label id="add-item-image-label" class="custom-file-label" for="customFile">Choose image file</label>
							</div>
	                		<button id="add-item-btn" class="btn btn-primary btn-block mt-3" role="button">Add Item</button>
	                	</div>
	                </div>
	                <!-- ./Add Item Form -->
                </div>
            </div>
		</div>

		<div class="col-12 col-lg-6 restaurant-edit-form-wrap mt-3 mt-lg-0">
			<div class="row">
                <div class="col-12 res-menu">
                	<h1 class="h3 subheader-border">Menu Preview</h1>

                	<?php
                	$count = 0;
                	if(!empty($cats)):
	                	foreach($cats as $k):
	                		$name = htmlspecialchars($k["item_cat_name"]);
	                		$desc = htmlspecialchars($k["item_cat_desc"]);
	                		$cat_id = $k["item_cat_id"];
	                		$bg_color = ($count % 2 == 0) ? "" : "";
	                		$count += 1;
                	?>
                	<div class="cat-wrap <?php echo $bg_color; ?>">
                	<!-- Menu Category -->
            		<h1 class="h5 subheader-border mt-3 row mx-0 menu-cat cat-<?php echo $cat_id; ?>">
            			<div class="col-9 pl-0">
            				<?php echo $name; ?>
            			</div>
            			<div class="col-3 pr-0 text-right">
            				<small class="">
            					<button class="border-0 btn-link-alt table-link text-link px-0 edit-cat" value="<?php echo $cat_id; ?>">Edit</button>
            					|
            					<button class="border-0 btn-link-alt table-link text-link px-0 remove-cat" value="<?php echo $cat_id; ?>">Remove</button>
            				</small>
            			</div>
            		</h1>
            		<div class="word-break"><small><i>
            			<?php echo $desc; ?>
            		</i></small></div>

                	<div class="menu-cat-item-list">
                		<?php
                		$items = menu_items($conn, $cat_id);
                		if(!empty($items)):
	                		foreach($items as $i):
	                			$item_name = htmlspecialchars($i["item_name"]);
	                			$item_price = htmlspecialchars($i["item_price"]);
	                			$item_desc = htmlspecialchars($i["item_description"]);
	                			if(strlen($item_desc) < 1) {
	                				$item_desc = "";
	                			}
	                			$item_id = $i["item_id"];
                		?>
                		<!-- Menu Item -->
                		<div class="menu-item row mt-3 item-<?php echo $item_id; ?>">
	                		<div class="col-2 d-flex justify-content-center align-items-center pr-0">
	                			<img src="https://via.placeholder.com/50" class="img-thumbnail rounded-0">
	                		</div>

	                		<div class="col-8 d-flex justify-content-left align-items-center">
	                			<?php echo $item_name; ?>
	                		</div>

	                		<div class="col-2 d-flex justify-content-center align-items-center pl-0">
                				<span class="text-success">
                					<?php echo $item_price; ?>	
            					</span>
                			</div>

	                		<div class="col-12 d-flex justify-content-left align-items-center text-muted mt-3 word-break"><small><i>
	                			<?php echo $item_desc; ?>
                			</i></small></div>
<!-- 
	                		<div class="col-12 mt-3">
								<div class="btn-group special" role="group">
									<button type="button" class="btn btn-primary btn-sm" value="<?php echo $item_id; ?>">Edit Item</button>
									<button type="button" class="btn btn-primary btn-sm" value="<?php echo $item_id; ?>">Remove</button>
								</div>
	                		</div> -->
	                	</div>
	                	<!-- ./Menu Item -->
		                <?php
			            	endforeach;
			            endif;
			            ?>
	                	
                	</div>
                	<!-- ./Menu Item List -->
                	</div>
                	<?php
                		endforeach;
                	endif;
                	?>

                	<div class="mt-2"></div>
                	<!-- ./Menu Category -->
                </div>
            </div>
		</div>
	</div>
</main>

<script type="text/javascript">
	var lid = <?php echo $_SESSION['loc_id']; ?>;
</script>
<?php
endif;
require_once(INCLUDE_PATH . 'footer.php');
?>