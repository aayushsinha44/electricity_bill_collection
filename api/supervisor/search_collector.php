<?php
include('conn.php');
include('config.php');

$response = array();

if(isset($_GET['token']) && isset($_GET["collector_id"])){
	$token = $_GET['token'];
	$collector_id = $_GET["collector_id"];
	$user_id = validateToken($token);
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$query = "SELECT * FROM collector WHERE user_id = '$collector_id' AND supervisor_id = '$user_id'";
		$result = mysqli_query($conn,$query);
		//inValid collector_id
		if(mysqli_num_rows($result)==0){
			$response["code"] = 200;
			$response["success"] = false;
			$response["message"] = "Invalid collector id or collector is not under you";
			echo json_encode($response);
		}
		//valid collector id
		else{
			$row = mysqli_fetch_assoc($result);
			$response["code"] = 200;
			$response["success"] = true;
			$response["collector_id"] = $row["user_id"];
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