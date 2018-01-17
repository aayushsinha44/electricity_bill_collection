<?php
include ('conn.php');
$response = array();
if(isset($_POST['userid']) && isset($_POST['password'])){
	$user_id = $_POST['userid'];
	$pswd = $_POST['password'];
	$pswd = md5($pswd);
	$query = "SELECT * FROM supervisor WHERE user_id = '$user_id' AND password='$pswd'";
	$result = mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
		$token = $user_id."/.@./".$pswd;
		$token = base64_encode($token);
		$response['success'] = true;
		$response['status'] = 200;
		$response['token'] = $token;
		echo json_encode($response);
	}
	else{
		$response['status'] = 200;
		$response['success'] = false;
		$response['message'] = "Invalid User id or password";
		echo json_encode($response);
	}
}
else{
	$response['status'] = 200;
	$response['success'] = false;
	$response['message'] = "Data is not set";
	echo json_encode($response);
}

?>