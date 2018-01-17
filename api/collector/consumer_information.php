<?php
include('conn.php');
include('config.php');

$response = array();

if(isset($_POST['token']) && isset($_POST['consumer_id'])){
	$consumer_id = $_POST['consumer_id'];
	$token = $_POST['token'];
	$collector_id = validateToken($token);
	if($collector_id == 'Invalid Token'){
		$response['success'] = false;
		$response['code'] = 400;
		$response['message'] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		//Validate consumer_id
		$q = "SELECT * FROM consumer WHERE user_id='$consumer_id' AND collector_id = '$collector_id'";
		$r = mysqli_query($conn,$q);
		if(mysqli_num_rows($r)==0){
			$response['success'] = false;
			$response['code'] = 400;
			$response['message'] = "Invalid Consumer Id or it is not your consumer";
			echo json_encode($response);
		}
		else{
			$response['details'] = array();
			$row = mysqli_fetch_assoc($r);
			$response['success'] = true;
			$response['code'] = 200;
			$product['user_id'] = $row['user_id'];
			$product['name'] = $row['name'];
			$product['address'] = $row['address'];
			$product['phone_number'] = $row['phone_number'];
			$product['amount_to_be_collected'] = $row['total_amount_to_be_paid'];
			array_push($response['details'], $product);
			echo json_encode($response);
		}
	}
}

?>