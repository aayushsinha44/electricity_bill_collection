<?php
include ('conn.php');
include ('config.php');

//Api returns all transaction
$response = array();

if(isset($_GET['token']) && isset($_GET['page_number'])){
	$token = $_GET['token'];
	$page_number = $_GET['page_number'];
	$user_id = validateToken($token);
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$a = 10 * ($page_number-1);
		$b = 2 * $page_number;
		$query = "SELECT * FROM collector_transaction WHERE supervisor_id='$user_id' ORDER BY id DESC LIMIT 10 OFFSET ".$a;
		$result = mysqli_query($conn,$query);
		if(mysqli_num_rows($result)==0){
			$response["success"] = true;
			$response["code"] = 200;
			$response["message"] = "All data Sent";
			echo json_encode($response);
		}
		else{
			$response["success"] = true;
			$response["code"] = 200;
			$response["details"] = array();
			while($row = mysqli_fetch_assoc($result)){
				$p["collector_id"] = $row["collector_id"];
				$p["date_time"] = $row["date_time"];
				$p["receipt_number"] = $row["receipt_number"];
				$p["consumer_id"] = $row["consumer_id"];
				$collector_id = $row["collector_id"];
				$q = "SELECT * FROM collector WHERE user_id='$collector_id'";
				$r = mysqli_query($conn,$q);
				$d = mysqli_fetch_assoc($r);
				$p["collector_name"] = $d["name"];
				$p["collector_address"] = $d["address"];
				$p["collector_phone_number"] = $d["phone_number"];
				array_push($response["details"],$p);
			}
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