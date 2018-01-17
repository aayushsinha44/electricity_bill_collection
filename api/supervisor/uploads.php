<?php
include('conn.php');
include('config.php');
date_default_timezone_set('Asia/Kolkata');
$response = array();

if(isset($_POST['token']) && isset($_POST["amount"]) && isset($_POST["image"])){
	$token = $_POST['token'];
	$amount = $_POST["amount"];
	$image = $_POST['image'];
	$user_id = validateToken($token);
	$date_time = date("Y-m-d h:i:sa");
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$rand_dir_name = substr(str_shuffle($chars), 0, 15);
		$path = "uploads/$rand_dir_name.png";
		file_put_contents($path,base64_decode($image));
		$q = "SELECT * FROM supervisor WHERE user_id='$user_id'";
		$r = mysqli_query($conn,$q);
		$row = mysqli_fetch_assoc($r);
		$cash_in_hand = $row["cash_in_hand"] - $amount;
		$q = "UPDATE supervisor SET cash_in_hand='$cash_in_hand' WHERE user_id='$user_id'";
		mysqli_query($conn,$q);
		$q = "INSERT INTO supervisor_admin_transaction (supervisor_id,amount,date_time,image_url) VALUES ('$user_id','$amount','$date_time','$path')";
		$r = mysqli_query($conn, $q);
		if($r){
			$response["code"] = 200;
			$response["success"] = true;
			$response["message"] = "Success";
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
else{
	$response["code"] = 200;
	$response["success"] = false;
	$response["message"] = "Data not set";
	echo json_encode($response);
}

/**
if($_SERVER['REQUEST_METHOD']=='POST'){
 
 $image = $_POST['image'];
$name = $_POST['name'];
 
 
 
 $id = 0;
 
 
 $path = "uploads/$id.png";
 
 file_put_contents($path,base64_decode($image));
 echo "Successfully Uploaded";

 }else{
 echo "Error";
 }
 **/
?>