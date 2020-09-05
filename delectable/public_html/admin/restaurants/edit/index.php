<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(!$_SESSION['admin_id']):
	header('Location: /delectable/public_html');
else:

$title = "Delectable | Edit Restaurant";
require_once(INCLUDE_PATH . 'header.php');

require_once(INCLUDE_PATH . '/admin/dashboard.php');

require_once(INCLUDE_PATH . 'functions.php');

$id = $_GET['lid'];
$res = restaurant_info($conn, $id);
// var_dump($res);
?>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 py-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Restaurant</h1>
        <a class="btn btn-alt" href="../">< Back</a>
    </div>

    <div class="">
        <div class="row">
            <!-- Restaurant Info & Location Form -->
            <div class="col-12 col-lg-6 restaurant-edit-form-wrap">
                <h1 class="h3 subheader-border">Info</h1>
                <div class="alert alert-success res-update-alert d-none">
                    <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
                    Updated successfully!
                </div>
                <form>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Name</h6>
                            <input id="res-name" class="form-control" type="text" value="<?php echo $res['res_name']; ?>">
                            <h6 class="mt-3">Slogan</h6>
                            <input id="res-slogan" class="form-control" type="text" value="<?php echo $res['res_slogan']; ?>">
                            <h6 class="mt-3">Description</h6>
                            <textarea id="res-desc" class="form-control" rows="6"><?php echo $res['res_description']; ?></textarea>
                            <button type="button" id="res-update-btn" class="btn btn-primary btn-block px-4 mt-3">Update Restaurant</button>
                        </div>
                    </div>
                </form>
                <h1 class="h3 mt-5 subheader-border">Location</h1>
                <div class="alert alert-success loc-update-alert d-none">
                    <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
                    Updated successfully!
                </div>

                <form>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Address 1</h6>
                            <input class="form-control" type="text" name="loc-address-1" value="<?php echo $res['loc_address_1']; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-6">
                            <h6>Address 2</h6>
                            <input class="form-control" type="text" name="loc-address-2" value="<?php echo $res['loc_address_2']; ?>">
                        </div>
                        <div class="col-12 col-md-6 mt-3 mt-md-0">
                            <h6>Phone</h6>
                            <input class="form-control" type="text" name="loc-phone" value="<?php echo $res['loc_phone']; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-5">
                            <h6>City</h6>
                            <input class="form-control" type="text" name="loc-city" value="<?php echo $res['loc_city']; ?>">
                        </div>
                        <div class="col-12 col-md-4 mt-3 mt-md-0">
                            <h6>State</h6>
                            <input class="form-control" type="text" name="loc-state" value="<?php echo $res['loc_state']; ?>">
                        </div>
                        <div class="col-12 col-md-3 mt-3 mt-md-0">
                            <h6>Zip</h6>
                            <input class="form-control" type="text" name="loc-postal-code" value="<?php echo $res['loc_postal_code']; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-primary btn-block loc-update-btn" name="loc-info">Update Location</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- ./Left-Col -->

            <!-- Restaurant Manager/Employee Forms -->
            <div class="col-12 col-lg-6 restaurant-edit-form-wrap mt-3 mt-lg-0">
                <h1 class="h3 subheader-border">Managers</h1>
                <div class="alert alert-success manager-update-alert d-none">
                    <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
                    Updated successfully!
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <button class="btn btn-primary btn-sm add-manager-btn" onclick="return false;" data-toggle="modal" data-target="#list-modal">Add</button>

                            <!-- Modal -->
                            <div class="modal fade" id="list-modal" tabindex="-1" role="dialog">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="manager-modal">Add Manager</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <!-- <form id="employee-list"> -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-row">
                                                    <div class="col-10"> 
                                                        <input id="emp-search" class="form-control" type="text">
                                                    </div>
                                                    <div class="col-2 pl-0">
                                                        <button class="btn btn-primary btn-block emp-search-btn">
                                                            Search
                                                        </button>
                                                        <!-- <input class="btn btn-primary btn-block" type="submit" name="emp-search-btn"> -->
                                                    </div>
                                                </div>

                                                <form>
                                                    <table id="emp-table" class="table">
                                                        <thead>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Username</th>
                                                            <th scope="col">Action</th>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    <!-- </form> -->
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-alt" data-dismiss="modal">Close</button>
                                    <button class="btn btn-primary save-manager-btn">Save</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <table id="manager-table" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Info</th>
                                    <th scope="col">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $managers = restaurant_managers($conn, $id);
                                if(!empty($managers)):
                                    foreach($managers AS $man):
                                        $name = $man['emp_first_name'] . ' ' . $man['emp_last_name'];
                                        $user = $man['emp_username'];
                                        $eid = $man['emp_id'];
                                        $url = "/delectable/public_html/admin/employees/edit/index.php?eid=" . $eid;
                                ?>
                                <tr id="row-<?php echo $eid; ?>" class="res-man-row">
                                    <td><span><?php echo $name; ?></span></td>
                                    <td><span><?php echo $user; ?></span></td>
                                    <td><a class="btn btn-primary btn-sm" href="<?php echo $url; ?>">Info</a></td>
                                    <td><button type="button" class="btn btn-primary btn-sm remove-manager-btn" value="<?php echo $eid; ?>">X</button></td>
                                </tr>
                                <?php
                                    endforeach;
                                else:
                                ?>

                                <?php
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h1 class="h3 mt-5 subheader-border">Employees</h1>
                <div class="alert alert-success employee-update-alert d-none">
                    <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
                    Updated successfully!
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <button class="btn btn-primary btn-sm add-employee-btn" onclick="return false;" data-toggle="modal" data-target="#emp-list-modal">Add</button>

                            <!-- Modal -->
                            <div class="modal fade" id="emp-list-modal" tabindex="-1" role="dialog">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="manager-modal">Add Employee</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <!-- <form id="employee-list"> -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-row">
                                                    <div class="col-10"> 
                                                        <input id="emp-search-input" class="form-control" type="text">
                                                    </div>
                                                    <div class="col-2 pl-0">
                                                        <button class="btn btn-primary btn-block emp-search-add-btn">
                                                            Search
                                                        </button>
                                                        <!-- <input class="btn btn-primary btn-block" type="submit" name="emp-search-btn"> -->
                                                    </div>
                                                </div>

                                                <form>
                                                    <table id="emp-list-table" class="table">
                                                        <thead>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Username</th>
                                                            <th scope="col">Action</th>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    <!-- </form> -->
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-alt" data-dismiss="modal">Close</button>
                                    <button class="btn btn-primary save-employee-btn">Save</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <table id="emp-list" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Info</th>
                                    <th scope="col">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $employees = restaurant_employees($conn, $id);
                                if(!empty($employees)):
                                    foreach($employees AS $emp):
                                        $name = $emp['emp_first_name'] . ' ' . $emp['emp_last_name'];
                                        $user = $emp['emp_username'];
                                        $eid = $emp['emp_id'];
                                        $url = "/delectable/public_html/admin/employees/edit/index.php?eid=" . $eid;
                                ?>
                                <tr id="row-<?php echo $eid; ?>">
                                    <td><span><?php echo $name; ?></span></td>
                                    <td><span><?php echo $user; ?></span></td>
                                    <td><a class="btn btn-primary btn-sm" href="<?php echo $url; ?>">Info</a></td>
                                    <td><button type="button" class="btn btn-primary btn-sm remove-employee-btn" value="<?php echo $eid; ?>">X</button></td>
                                </tr>
                                <?php
                                    endforeach;
                                else:
                                ?>

                                <?php
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h1 class="h3 mt-5 subheader-border">Layout</h1>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <a class="btn btn-primary btn-block" href="./layout/index.php?lid=<?php echo $id; ?>">Edit</a>
                        </div>
                    </div>
                </div>
            </div> 
            <!-- ./Right-Col -->
        </div>
        <!-- ./Row -->
    </div>
</main>

<script type="text/javascript">
    var lid = <?php echo $id; ?>;
    var rid = <?php echo $res['fk_res_id']; ?>;
</script>
<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>