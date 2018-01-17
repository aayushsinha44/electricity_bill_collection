<?php
include('conn.php');
include('config.php');
date_default_timezone_set('Asia/Kolkata');

$response = array();

if(isset($_POST['token']) && isset($_POST["collector_id"]) && isset($_POST["amount"])){
	$token = $_POST['token'];
	$collector_id = $_POST["collector_id"];
	$amount = $_POST["amount"];
	$user_id = validateToken($token);
	$date_time = date("Y-m-d h:i:sa");
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
			$cash_in_hand_collector = $row["cash_in_hand"] - $amount;
			$q = "UPDATE collector SET cash_in_hand='$cash_in_hand_collector' WHERE user_id='$collector_id'";
			mysqli_query($conn,$q);
			$q = "SELECT * FROM supervisor WHERE user_id='$user_id'";
			$r = mysqli_query($conn,$q);
			$row = mysqli_fetch_assoc($r);
			$cash_in_hand_supervisor = $row["cash_in_hand"] + $amount;
			$q = "UPDATE supervisor SET cash_in_hand='$cash_in_hand_supervisor' WHERE user_id='$user_id'";
			mysqli_query($conn,$q);
			$q = "INSERT INTO supervisor_collector_transaction (collector_id,supervisor_id,amount,date_time) VALUES ('$collector_id','$user_id','$amount','$date_time')";
			$r = mysqli_query($conn,$q);
			if($r){
				$response["code"] = 200;
				$response["success"] = true;
				echo json_encode($response);
			}
			else{
				$response["code"] = 200;
				$response["success"] = false;
				$response["message"] = "Something went wrong";
				echo json_encode($response);
			}
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