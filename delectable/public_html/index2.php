<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/config.php');


if (isset($_SESSION['admin_id'])) :
    header('Location: /delectable/public_html/admin/dashboard/');
elseif (isset($_SESSION['emp_id'])) :
    header('Location: /delectable/public_html/business/dashboard/');
else :

    $title = "Delectable | ";
    require_once(INCLUDE_PATH . 'header.php');
?>
    <!-- Navigation -->
    <nav id="home-nav" class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
        <div class="container">
            <a class="navbar-brand text-uppercase" href="/delectable/public_html/index.php">Delectable</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/delectable/public_html/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/delectable/public_html/business/">Business</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/delectable/public_html/admin/">Admin</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <!-- Full Page Image Header with Vertically Centered Content -->


    // LIST RESTAURANT RESULTS
    <?php
    $listResults = false;
    $searchTerm = "";

    /* [SEARCH FOR RESTAURANTS] */
    if (isset($_GET["restaurant-search"]) && !empty($_GET["restaurant-search"])) {

        if ($_GET["restaurant-search"] !== "") {
            $searchTerm = $_GET["restaurant-search"];
            $listResults = true;
        } else {
            $listResults = false;
        }
    }

    if ($listResults) {

        /*  //<!-- Page Content -->
        echo "<section class=\"py-5\">";
        echo "    <div class=\"container\">";
        echo "        <h2 class=\"font-weight-light\">Page Content</h2>";
        echo "        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus ab nulla dolorum autem nisi officiis blanditiis voluptatem hic, assumenda aspernatur facere ipsam nemo ratione cumque magnam enim fugiat reprehenderit expedita.</p>";
        echo "    </div>";
        echo "</section>";
*/
    ?>
        <!-- Page Content -->
        <section class="py-5">
            <div class="container">
                <h2 class="font-weight-light">List of Restaurants: </h2><br>
            </div>
            <div class="container">
                <!-- Sort By: -->
                State: <select id="state" name="states"></select>
                City: <input type="text" id="city" name="city" placeholder="e.g. Bakersfield">
                Zipcode: <input type="text" id="zipcode" name="zipcode" placeholder="e.g. 93309">
                <br><br>
                <hr>
                <!-- List of Restaurants: Queries -->
                <?php

                $sql = $conn->query("SELECT restaurant.res_name, restaurant.res_slogan, restaurant.specialty, 
                location.loc_address_1, location.loc_address_2, location.loc_city, location.loc_state, 
                location.loc_postal_code, location.loc_phone
                FROM restaurant
                INNER JOIN location ON restaurant.res_id=location.fk_res_id WHERE restaurant.specialty='$searchTerm'");

                //$stmt = $conn->query("SELECT * FROM restaurant");
                while ($r = $sql->fetch()) {

                    echo htmlspecialchars($r["res_name"]) . "<br> \n";
                    echo htmlspecialchars($r["res_slogan"]) . "<br> \n";
                    echo htmlspecialchars($r["loc_address_1"]) . "  " . $r["loc_address_2"] . "<br> \n";
                    echo htmlspecialchars($r["loc_state"]) . "<br> \n";
                    echo htmlspecialchars($r["loc_city"]) . "<br> \n";
                    echo htmlspecialchars($r["loc_postal_code"]) . "<br> \n";
                    echo htmlspecialchars($r["loc_phone"]) . "<br> \n";
                    echo htmlspecialchars($r['specialty']) . "<br> \n";
                    echo "<button type=\"button\"> RSVP </button><br>";
                    //echo "SeachTerm: " . $searchTerm . "<br> \n";
                    echo "<br><br>";

                    $name = $r["res_name"];
                }

                ?>

            </div>
        </section>
    <?php

    } else {

    ?>
        // ELSE SHOW ORIGINAL CONTENT
        <header class="masthead">
            <div class="overlay">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12 text-center">
                            <h1 class="font-weight-light text-white text-uppercase">
                                Delectable
                            </h1>
                            <p class="lead text-white">
                                Skip the line. Table reservations at your fingertips.
                            </p>
                            <div class="masthead-input mx-auto w-50">
                                <form method="GET">
                                    <input class="form-control" type="text" name="restaurant-search" placeholder="Search restaurants" value="<?= $searchTerm ?>">
                                    <input type="submit" value="Search" />
                                </form>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </header>
        <!-- Page Content -->
        <section class="py-5">
            <div class="container">
                <h2 class="font-weight-light">Page Content</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus ab nulla dolorum autem nisi officiis blanditiis voluptatem hic, assumenda aspernatur facere ipsam nemo ratione cumque magnam enim fugiat reprehenderit expedita.</p>
            </div>
        </section>
    <?php
    }
    ?>
<?php
    unset($_SESSION['error']);
    require_once(INCLUDE_PATH . 'footer.php');
endif;
?>