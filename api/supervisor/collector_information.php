<?php
//this api will return collector information and also his last transaction
include ('conn.php');
include ('config.php');

$response = array();

if(isset($_GET["token"]) && isset($_GET['collector_id'])){
	$token = $_GET['token'];
	$collector_id = $_GET['collector_id'];
	$user_id = validateToken($token);
	if($user_id == "Invalid Token"){
		$response["code"] = 200;
		$response["success"] = false;
		$response["message"] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		// Send collector information as well as his all transaction
		if(isset($_GET["page_number"])){

			$response["success"] = true;
			$response["code"] = 200;
			$response["collector_information"] = array();
			$response["collector_transaction"] = array();

			//Get collector information
			$q1 = "SELECT * FROM collector WHERE user_id='$collector_id'";
			$r1 = mysqli_query($conn,$q1);
			$row1 = mysqli_fetch_assoc($r1);
			$p["name"] = $row1["name"];
			$p["address"] = $row1["address"];
			$p["phone_number"] = $row1["phone_number"];
			$p["user_id"] = $row1["user_id"];
			$p["cash_in_hand"] = $row1["cash_in_hand"];
			array_push($response["collector_information"], $p);

			$page_number = $_GET["page_number"];
			$a = 10 * ($page_number-1);
			$b = 10 * $page_number;

			//Get collector transaction 
			$q2 = "SELECT * FROM collector_transaction WHERE collector_id='$collector_id' ORDER BY id DESC LIMIT 10 OFFSET ".$a;
			$r2 = mysqli_query($conn,$q2);
			if(mysqli_num_rows($r2)==0){
				$response["message"] = "All data Sent";
				echo json_encode($response);
			}
			else{
				while($row = mysqli_fetch_assoc($r2)){
					$pi["date_time"] = $row["date_time"];
					$pi["receipt_number"] = $row["receipt_number"];
					$pi["amount"] = $row["amount"];
					array_push($response["collector_transaction"],$pi);
				}
				$response["message"] = "Not all data sent";
				echo json_encode($response);
			}
		}
		// Page number is not set
		else{
			$response["success"] = false;
			$response["code"] = 200;
			$response["message"] = "Page number not set";
			echo json_encode($response);
		}
	}
}
else {
	$response["success"] = false;
	$response["code"] = 200;
	$response["message"] = "Data not set";
	echo json_encode($response);
}


?>