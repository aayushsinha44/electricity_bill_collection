<?php
include ('conn.php');
include('config.php');
$response = array();
if(isset($_GET['token'])){
	$token = $_GET['token'];
	$user_id = validateToken($token);
	if($user_id=='Invalid Token'){
		$response['success'] = false;
		$response['status'] = 400;
		$response['message'] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$target = 0;
		$query = "SELECT * FROM consumer WHERE collector_id='$user_id'";
		$result = mysqli_query($conn,$query);
		$response['success'] = true;
		$response['code'] = 200;
		$response['details'] = array();
		while($row = mysqli_fetch_assoc($result)){
			$product['user_id'] = $row['user_id'];
			$product['name'] = $row['name'];
			$product['address'] = $row['address'];
			$product['phone_number'] = $row['phone_number'];
			$product['amount_to_be_collected'] = $row['total_amount_to_be_paid'];
			array_push($response['details'], $product);
		}
		echo json_encode($response);
	}

}
else{
	$response['success'] = false;
	$response['status'] = 400;
	$response['message'] = "Data not set";
	echo json_encode($response);
}
?>