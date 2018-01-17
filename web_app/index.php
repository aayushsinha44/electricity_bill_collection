<?php
include('api/config/conn.php');
if(isset($_POST['signin'])){
	$user_id = $_POST['uname'];
	$pswd = $_POST['pswd'];
	$pswd = md5($pswd);
	$query = "SELECT * FROM admin WHERE user_id = '$user_id' AND password = '$pswd'";
	$result = mysqli_query($conn,$query);
	session_start();
	if(mysqli_num_rows($result)>0){
		$_SESSION["user_id"] = $user_id;
		if($_SESSION['user_id']!=null){
			if(isset($_SESSION['user_id']))
			header("Location: home.php");
			else{
			?><script type="text/javascript">alert("Session was not set");</script><?php
			}
		}
		
	}
	else{
		?><script type="text/javascript">alert("Invalid User id or password");</script><?php
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Index</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
<div class="container">

    <form class="form-signin" method="POST">
    	<h2 class="form-signin-heading">ADMIN SIGN IN</h2>
		<input type="text" id="inputEmail" class="form-control" name = "uname" placeholder="User ID" required autofocus>
    	<input type="password" id="inputPassword" name = "pswd" class="form-control" placeholder="Password" required>
		<input type="submit" class="btn btn-lg btn-primary btn-block" name = "signin" value="Sign In" style= "max-width: 100px;">
	</form>

</div> <!-- /container -->


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
</body>
</html>