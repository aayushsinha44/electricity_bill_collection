<?php
function validateToken($token)
{
	include('conn.php');
	$token = base64_decode($token);
	list($user_id,$password) = split('/.@./', $token);
	$query = "SELECT * FROM supervisor WHERE user_id='$user_id' AND password='$password'";
	$result = mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
		return $user_id;
	}
	else{
		return "Invalid Token";
	}
}
?>