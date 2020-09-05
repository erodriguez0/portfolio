<?php if(isset($_SESSION['admin_id']) || isset($_SESSION['emp_id'])): ?>

<!-- Navigation -->
<nav id="home-nav" class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
    <div class="container">
        <a class="navbar-brand text-uppercase" href="/delectable/public_html/">Delectable</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/">Home</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="/delectable/resources/scripts/logout.php">
                        <button type="submit" name="logout" class="nav-link text-capitalize btn-link-alt">Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php elseif(isset($_SESSION['cust_id'])): ?>
<nav id="home-nav" class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
    <div class="container">
        <a class="navbar-brand text-uppercase" href="/delectable/public_html/">Delectable</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/restaurants/">Restaurants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/account/">Account</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="/delectable/resources/scripts/logout.php">
                        <button type="submit" name="logout" class="nav-link text-capitalize btn-link-alt">Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php else: ?>
<nav id="home-nav" class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
    <div class="container">
        <a class="navbar-brand text-uppercase" href="/delectable/public_html/">Delectable</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/restaurants/">Restaurants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/business/">Business</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/admin/">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/delectable/public_html/account/">Login | Sign Up</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>