<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/delectable/public_html/">Delectable</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <form method="POST" action="/delectable/resources/scripts/logout.php">
                <button type="submit" name="logout" class="text-white btn btn-link text-capitalize">Sign out</button>
            </form>
        </li>
    </ul>
</nav>
<div class="container-fluid">
<div class="row">
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/delectable/public_html/admin/dashboard/">
                <span data-feather="home"></span>
                Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/delectable/public_html/admin/restaurants/">
                <span data-feather="file-plus"></span>
                Restauraunts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/delectable/public_html/admin/employees/">
                <span data-feather="users"></span>
                Employees
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/delectable/public_html/admin/customers/">
                <span data-feather="users"></span>
                Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/delectable/public_html/admin/reports/">
                <span data-feather="bar-chart-2"></span>
                Reports
                </a>
            </li>
        </ul>
    </div>
</nav>