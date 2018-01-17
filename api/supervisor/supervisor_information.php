<?php
include('conn.php');
include('config.php');

$response = array();

if(isset($_GET["token"])){
	$token = $_GET['token'];
	$user_id = validateToken($token);
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		// Send his amount to be submitted to the bank
		$query = "SELECT * FROM supervisor WHERE user_id = '$user_id'";
		$result = mysqli_query($conn,$query);
		$amount = 0;
		$response["success"] = true;
		$response["code"] = 200;
		$row = mysqli_fetch_assoc($result);
		$amount = $row["cash_in_hand"];
		$response["amount"] = $amount;
		echo json_encode($response);
	}
}
else{
	$response["success"] = false;
	$response["code"] = 200;
	$response["message"] = "Data not set";
	echo json_encode($response);
}
?>