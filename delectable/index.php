<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/delectable/resources/inc/config.php');

$title = "Delectable | Customer";
// require_once(INCLUDE_PATH . 'header.php');

if(!$_SESSION['active']):
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Delectable | Home</title>

  <!-- Bootstrap core CSS -->
  <link href="public_html/media/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="public_html/media/css/business-frontpage.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#"><img src="public_html/media/img/graphics/Artboard 1.png" alt="Delectable Logo"</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <header class="masthead">
    <div class="container h-100 align-items-center justify-content-center">
      <div class="row h-100 align-items-center justify-content-center">
		  <div class="row h-350 align-items-center my-auto">
        	<div class="col-lg-12">
          		<h1 class="display-4 text-white mb-2 text-center">Discover a new dining experience</h1>
          		<p class="lead mb-5 text-white-50 text-center">Browse from a variety of local restaurants in your area</p>
		  		<p class="lead mb-5">
					<form class="form-inline active-cyan-4 justify-content-center">
  						<input class="form-control form-control-sm mr-3 w-50" type="text" placeholder="Location" aria-label="Location">
  						<i class="fas fa-search" aria-hidden="true"></i>
					</form>
		  		</p>
        	</div>
	  	</div>
      </div>
    </div>
  </header>

  <!-- Page Content -->
  <div class="container">

    <!-- /.row -- features for customers -->

    <div class="row">
	  <div class="col-lg-12">
		  <h2 class="display-4 text-black-50 text-center mb-5">Why use Delectable?</h2>
	  </div>
      <div class="col-md-4 mb-5">
		
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/300x200" alt="">
          <div class="card-body">
            <h4 class="card-title">Discover More</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque sequi doloribus.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/300x200" alt="">
          <div class="card-body">
            <h4 class="card-title">Customizable Reservations</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque sequi doloribus totam ut praesentium aut.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/300x200" alt="">
          <div class="card-body">
            <h4 class="card-title">Direct Feedback</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque.</p>
          </div>
        </div>
      </div>
    </div>
	<!-- /.row -- call to action for customers -->
	<div class="row">
		<div class="container h-100 align-items-center justify-content-center">
      		<div class="row h-50 align-items-center justify-content-center my-auto">
		  		<div class="row h-50 align-items-center my-auto">
        			<div class="col-lg-12 h-25 text-center">
          				<h2 class="display-4 mb-2 text-center">Sign Up for myDelectable</h2>
          				<p class="lead mb-5 text-center">Join now to start making reservations and reviewing your dining adventures</p>
		  				<p class="mb-5">
							<a href="#" class="btn btn-primary">Sign Up</a>
		  				</p>
        			</div>
	  			</div>
      		</div>
    	</div>
	</div>
    <!-- /.row -- features for restaurants -->
	<div class="row">
		<div class="col-lg-12">
			<h2 class="display-4 text-black-50 text-center mb-5">Delectable for Business</h2>
	  	</div>
     <div class="col-md-4 mb-5">
		<div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/300x200" alt="">
          <div class="card-body">
            <h4 class="card-title">Discover More</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque sequi doloribus.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/300x200" alt="">
          <div class="card-body">
            <h4 class="card-title">Customizable Reservations</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque sequi doloribus totam ut praesentium aut.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/300x200" alt="">
          <div class="card-body">
            <h4 class="card-title">Direct Feedback</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque.</p>
          </div>
        </div>
      </div>
	 </div>
	 <!--/.row -- call to action for restaurants -->
	 <div class="row">
		<div class="container h-100 align-items-center justify-content-center">
      		<div class="row h-50 align-items-center justify-content-center my-auto">
		  		<div class="row h-50 align-items-center my-auto">
        			<div class="col-lg-12 h-25 text-center">
          				<h2 class="display-4 mb-2 text-center">Simplify management with Delectable</h2>
          				<p class="lead mb-5 text-center">Running a restaurant is made easy with our intuitive management portal</p>
		  				<p class="mb-5">
							<a href="#" class="btn btn-primary">Learn More</a>
		  				</p>
        			</div>
	  			</div>
      		</div>
    	</div>
	 </div>
	  
  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Delectable 2019</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php
endif;

// Unset sticky form input and errors
unset($_SESSION['error']);
unset($_SESSION['create']);

require_once(INCLUDE_PATH . 'footer.php');
?>