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
        <a class="btn btn-alt" href="../index.php?lid=<?php echo $id; ?>">< Back</a>
    </div>

    <div class="">
        <div class="mt-5">
            <!-- Restaurant Table Layout -->
            <!-- Admin/Restaurant Mode -->
            <div class="form-group admin-mode">
                <div class="row">
                    <div class="col-12">
                        <div class="btn-toolbar d-flex align-items-center justify-content-center" role="toolbar">

                            <!-- Rectangles -->
                            <div class="btn-group mr-2" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn">Rectangle</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text rectangle-0">0&#xb0;</button>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text rectangle-45">45&#xb0;</button>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text rectangle-315">-45&#xb0;</button>
                            </div>

                            <!-- Squares -->
                            <div class="btn-group mr-2" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn w-100">Square</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text square-0 w-100">0&#xb0;</button>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text square-45 w-100">45&#xb0;</button>
                            </div>

                            <!-- Circle -->
                            <div class="btn-group" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn">Round</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text round-0 w-100">0&#xb0;</button>
                            </div>
                        </div>

                        <div class="btn-toolbar mt-3 d-flex align-items-center justify-content-center">

                            <!-- Objects -->
                            <div class="btn-group mr-2" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn w-100">Objects</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text object-0 w-100">0&#xb0;</button>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text object-45 w-100">45&#xb0;</button>
                            </div>

                            <!-- Chairs -->
                            <div class="btn-group mr-2" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn w-100">Chair</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text chair-0 w-100">0&#xb0;</button>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text chair-45 w-100">45&#xb0;</button>
                            </div>

                            <!-- Actions -->
                            <div class="btn-group" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn w-100">Actions</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text remove w-100">Remove</button>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text clear w-100">Clear</button>
                                <!-- <button type="button" class="btn btn-alt btn-sm btn-sm-text export w-100">Export</button> -->
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text save-layout w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./Admin/Restaurant Mode -->

            <!-- Customer Mode -->
<!--             <div class="form-group customer-mode" style="display: none;">
                <div class="row">
                    <div class="col-12">
                        <div class="btn-toolbar d-flex align-items-center justify-content-center" role="toolbar">
                            <div class="btn-group mr-2" role="group">
                                <a href="#" role="button" class="btn btn-alt btn-sm btn-sm-text label-btn">Actions</a>
                                <button type="button" class="btn btn-alt btn-sm btn-sm-text mode w-100">Mode</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- ./Customer Mode -->

            <!-- Layout Canvas -->
            <div class="row">
                <div class="col-12">
                    <div class="mt-3 overflow-x d-flex align-items-center justify-content-center">
                        <canvas id="canvas" width="720" height="540"></canvas>
                    </div>
                </div>
            </div>
            <!-- ./Layout Canvas -->
        </div>
    </div>
</main>

<script type="text/javascript">
    var lid = <?php echo $id; ?>;
    var rid = <?php echo $res['fk_res_id']; ?>
</script>
<?php
require_once(INCLUDE_PATH . 'footer.php');
endif;
?>
