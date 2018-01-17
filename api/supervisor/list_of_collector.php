<?php
include('conn.php');
include('config.php');

$response = array();

if(isset($_GET['token']) && isset($_GET["page_number"])){
	$token = $_GET['token'];
	$page_number = $_GET["page_number"];
	$user_id = validateToken($token);
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$a = 10 * ($page_number-1);
		$b = 10 * $page_number;
		$query = "SELECT * FROM collector WHERE supervisor_id='$user_id' ORDER BY id LIMIT 10 OFFSET ".$a;
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
				$p["id"] = $row["user_id"];
				$p["name"] = $row["name"];
				$p["phone_number"] = $row["phone_number"];
				$p["address"] = $row["address"];
				$p["cash_in_hand"] = $row["cash_in_hand"];
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