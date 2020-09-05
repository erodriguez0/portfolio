<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(!isset($_SESSION['admin_id'])):
	header('Location: /delectable/public_html');
else:

$title = "Delectable | Employees";
require_once(INCLUDE_PATH . 'header.php');

require_once(INCLUDE_PATH . '/admin/dashboard.php');

require_once(INCLUDE_PATH . 'functions.php');
?>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Employees</h1>
    </div>

    <div class="">
    	<div class="row">
			<div class="col-12 col-md-6 col-lg-5 col-xl-4">
    			<input type="text" id="emp-table-search" name="emp-table-search" class="form-control" placeholder="Search employees">
    		</div>
    	</div>
        <table class="table mt-3">
        	<thead>
        		<th scope="col">Name</th>
        		<th scope="col">Userame</th>
        		<th scope="col">Email</th>
        		<th scope="col">Status</th>
        		<th scope="col">Links</th>
        	</thead>
        	<tbody>
        	<?php
        	$emps = employee_list($conn);
        	if(count($emps) > 0):
        		foreach($emps AS $emp):
        			$name = $emp['emp_first_name'] . ' ' . $emp['emp_last_name'];
        			$uname = $emp['emp_username'];
        			$email = $emp['emp_email'];
        			$eid = $emp['emp_id'];
        			$edit_url = "/delectable/public_html/admin/employees/edit/index.php?eid=" . $eid;
        			$color = "";
        			$status = "";
        			if($emp['emp_status']):
        				$color = "success";
        				$status = "Active";
    				else:
    					$color = "danger";
    					$status = "Suspended";
    				endif;
        	?>
        		<tr>
        			<td><?php echo $name; ?></td>
        			<td><?php echo $uname; ?></td>
        			<td><?php echo $email; ?></td>
        			<td><span class="text-<?php echo $color; ?>"><?php echo $status; ?></span></td>
        			<td>
        				<button class="btn btn-primary btn-sm emp-profile-modal-btn" name="<?php echo $eid;?>" data-toggle="modal" data-target="#profile-modal">Info</button>
        				<a href="<?php echo $edit_url; ?>" class="btn btn-alt btn-sm">Edit</a>
        			</td>
        		</tr>
    		<?php
    			endforeach;
			else:
    		?>
    			<tr>
        			<td class="text-center" colspan="5">
        				No employees to show
        			</td>
        		</tr>
    		<?php
    		endif;
    		?>
        	</tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="profile-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manager-modal">Employee Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4 text-right">
                            <label><b>Image:</b></label>
                        </div>
                        <div class="col-8">
                            <img id="profile-img" class="emp-profile-modal-img img-fluid" src="https://i.pinimg.com/originals/0d/36/e7/0d36e7a476b06333d9fe9960572b66b9.jpg">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Name:</b></label>
                    	</div>
                    	<div class="col-8">
                        	<label id="profile-name"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Username:</b></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-username"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Email:</b></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-email"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Phone:</b></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-phone"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Address:</b></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-address"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Status:</b></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-status"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Registered:</b></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-registered"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Last Login:</b><span></span></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-login"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                            <label><b>Last Updated:</b><span></span></label>
                        </div>
                        <div class="col-8">
                            <label id="profile-updated"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Workplace: </b><span></span></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-workplace"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Work Phone: </b><span></span></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-work-phone"></label>
                    	</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                        	<label><b>Work Address: </b><span></span></label>
                        </div>
                        <div class="col-8">
                        	<label id="profile-work-address"></label>
                    	</div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-alt" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</main>

<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>