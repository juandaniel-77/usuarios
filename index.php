<html lang="es">
<head>


<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    Login
</title>

<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<link rel="stylesheet" href="css/all.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/my.css">
<link rel="stylesheet" href="css/simple-sidebar.css" >

<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
  
</head>

<body>
<?php
require './db_inc.php';
require './account_class.php';
include('gconfig.php');
use PHPGrid\Grid as GridClass;

$account = new Account();
$login=FALSE;
$error_login=FALSE;

if(isset($_GET['logout'])){
	$account->logout();
	
	$accesstoken=$_SESSION['access_token'];
	
	 $t = [
            'access_token' => $_SESSION['access_token'],
            'created' => time(),
            'expires_in' => '3600'
        ];
	
	unset($_SESSION['access_token']);    
	unset($_SESSION['userData']); 
	
	//Reset OAuth access token
	$google_client->revokeToken($t);

	//Destroy entire session data.
	session_destroy();
}

if(!isset($_SESSION['access_token'])) {
   //Create a URL to obtain user authorization
   $google_login_btn = $google_client->createAuthUrl();
}

if(isset($_POST['inputEmail']) && isset($_POST['inputPassword'])){

	try
	{
		$login = $account->login($_POST['inputEmail'], $_POST['inputPassword']);
		if(!$login) $error_login=TRUE;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		die();
	}
}

if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  $login=TRUE;
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  if(!empty($data['given_name'])) { $_SESSION['user_first_name'] = $data['given_name']; }

  if(!empty($data['family_name'])) { $_SESSION['user_last_name'] = $data['family_name']; }

  if(!empty($data['email'])) { $_SESSION['user_email_address'] = $data['email']; }

  if(!empty($data['gender'])){ $_SESSION['user_gender'] = $data['gender'];  }

  if(!empty($data['picture'])) { $_SESSION['user_image'] = $data['picture']; }
 }
}

if ($login)
{
	?>

	  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><?php //echo $account->getName(); ?> </div>
      <div class="list-group list-group-flush">
	  
	 <!-- <div class="sidebar">-->
        <a href="#" class="list-group-item list-group-item-action bg-light">Dashboard</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Shortcuts</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Overview</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Events</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Profile</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Status</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $account->getName(); ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Mi perfil</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="index.php?logout">Salir</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        <h1 class="mt-4">Simple Sidebar</h1>
        <p>The starting state of the menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will change.</p>
        <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>. The top navbar is optional, and just for demonstration. Just create an element with the <code>#menu-toggle</code> ID which will toggle the menu when clicked.</p>
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->
    <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>
	<?php
	}
else
{
?>
  
  <div class="container" style="background: #007bff;   background: linear-gradient(to right, #0062E6, #33AEFF);">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Sign In</h5>
            <form class="form-signin" action="index.php" method="post">
              <div class="form-label-group">
                <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputEmail">Email address</label>
              </div>

              <div class="form-label-group">
                <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Password</label>
              </div>
<?php
if($error_login) {
?><div id="alert" class="alert alert-danger" role="alert">
  Usuario o contraseña no validos.
</div>
<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).hide();
        });
    }, 1000);
</script>
<?php
}
?>
              <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember password</label>
              </div>
              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in</button>
              <hr class="my-4">
              <a href="<?php echo $google_login_btn; ?>" class="btn btn-lg btn-google btn-block text-uppercase"><img src="images/google.png" /> Sign in with Google</a>
              <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fab fa-facebook-f mr-2"></i> Sign in with Facebook</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>
</body>

</html>