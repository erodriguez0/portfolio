<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');

if(!$_SESSION['admin_id']):
	header('Location: /delectable/public_html');
else:

$title = "Delectable | Restauraunts";
require_once(INCLUDE_PATH . 'header.php');

require_once(INCLUDE_PATH . '/admin/dashboard.php');

require_once(INCLUDE_PATH . 'functions.php');

$res = restaurant_list($conn);
// var_dump($res);
?>

<main role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Restaurants</h1>
    </div>

    <div class="">
        <div class="row">
            <div class="col-10 col-md-6 col-lg-5 col-xl-4">
                <input type="text" id="res-table-search" name="res-table-search" class="form-control rounded-0" placeholder="Search restaurants">
            </div>
            <div class="col-2 col-md-6 col-lg-7 col-xl-8">
                <button id="new-restaurant-modal-btn" class="btn btn-primary d-inline-block" data-toggle="modal" data-target="#new-restaurant-modal">New</button>
            </div>
        </div>
        <table class="table mt-3 res-list">
            <thead>
                <tr>
                    <th scope="col">Restaurant</th>
                    <th scope="col">Address</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($res as $res): 
                    $name = $res['res_name'];
                    $address = $res['loc_address_1'] . ' ' . $res['loc_address_2'] . ', ' . $res['loc_city'] . ' ' . $res['loc_postal_code']; 
                    $lid = $res['loc_id'];
                    $url = "./edit/index.php?lid=" . $lid;
                ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $address; ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?php echo $url; ?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div class="modal fade" id="new-restaurant-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profile-modal-title">Add New Restaurant</h5>
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="alert new-res-alert d-none"></div>
                    </div>
                    <div class="col-12">
                        <input id="new-res-name" class="form-control rounded-0" type="text" placeholder="Restaurant Name" >
                        <!-- <span class="res-name-counter">128</span><span> characters remaining</span> -->
                    </div>
                    <div class="col-12 mt-3">
                        <input id="new-res-slogan" class="form-control rounded-0" type="text" placeholder="Restaurant Slogan">
                        <!-- <span class="res-slogan-counter">128</span><span> characteres remaining</span> -->
                    </div>
                    <div class="col-12 mt-3">
                        <textarea id="new-res-description" class="form-control rounded-0 mh-textarea" placeholder="Description..." rows="4"></textarea>
                    </div>
                    <div class="col-5 mt-3">
                        <hr>
                    </div>
                    <div class="col-2 mt-3 text-center d-flex justify-content-center align-items-center">
                        <small>Location Info</small>
                    </div>
                    <div class="col-5 mt-3">
                        <hr>
                    </div>
                    <div class="col-12 mt-3">
                        <input id="new-loc-address-1" class="form-control rounded-0" type="text" name="" placeholder="Address" >
                    </div>
                    <div class="col-4 mt-3">
                        <input id="new-loc-address-2" class="form-control rounded-0" type="text" name="" placeholder="Apt/Ste" >
                    </div>
                    <div class="col-4 mt-3">
                        <!-- pattern="[0-9]{3} [0-9]{3} [0-9]{4}" -->
                        <input id="new-loc-phone" class="form-control rounded-0" type="tel" patter="[0-9]{3} [0-9]{3} [0-9]{4}" maxlength="10" placeholder="Phone (10-digit)">
                    </div>
                    <div class="col-4 mt-3">
                        <input id="new-loc-zip" class="form-control rounded-0" type="text" placeholder="Zip Code" >
                    </div>
                    <div class="col-6 mt-3">
                        <input id="new-loc-city" class="form-control rounded-0" type="text" placeholder="City" >
                    </div>
                    <div class="col-6 mt-3">
                        <input id="new-loc-state" class="form-control rounded-0" type="text" placeholder="State" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="new-restaurant-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>