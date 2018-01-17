<?php
include('conn.php');
include('config.php');

$response = array();

if(isset($_POST['token']) && isset($_POST["collector_id"])){
	$token = $_POST['token'];
	$collector_id = $_POST["collector_id"];
	$user_id = validateToken($token);
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$q = "SELECT * FROM collector WHERE user_id = '$collector_id'";
		$r = mysqli_query($conn,$q);
		if(mysqli_num_rows($r)==0){
			$response["code"] = 200;
			$response["success"] = false;
			$response["message"] = "Invalid Collector";
			echo json_encode($response);
		}
		else{
			$row = mysqli_fetch_assoc($r);
			$response["code"] = 200;
			$response["success"] = true;
			$response["cash_in_hand"] = $row["cash_in_hand"];
			echo json_encode($response);
		}
	}
}
else{
	$response["code"] = 200;
	$response["success"] = false;
	$response["message"] = "Data not set";
	echo json_encode($response);
}

?>