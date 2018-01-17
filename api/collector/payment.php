<?php
include('conn.php');
include('config.php');
date_default_timezone_set('Asia/Kolkata');
if(isset($_POST['token']) && isset($_POST['consumer_id']) && isset($_POST['amount_collected']) && isset($_POST['receipt_number'])){
	$token = $_POST['token'];
	$collector_id = validateToken($token);
	$consumer_id = $_POST['consumer_id'];
	$amount_collected = $_POST['amount_collected'];
	$receipt_number = $_POST['receipt_number'];
	if($collector_id=='Invalid Token'){
		$response['success'] = false;
		$response['status'] = 400;
		$response['message'] = "Invalid Token";
		echo json_encode($response);
	}
	else{
		$date_time = date("Y-m-d h:i:sa");
		$q1 = "SELECT * FROM consumer WHERE user_id='$consumer_id' AND collector_id='$collector_id'";
		$r1 = mysqli_query($conn,$q1);
		if(mysqli_num_rows($r1)<1){
			$response['success'] = false;
			$response['status'] = 400;
			$response['message'] = "Invalid Consumer";
			echo json_encode($response);
		}
		else{
			$row1 = mysqli_fetch_assoc($r1);
			$adjustment = $row1['adjustment'];
			$amount_to_be_collected = $row1['total_amount_to_be_paid'];
			$total_amount_paid = $row1['total_amount_paid'] + $amount_collected;
			$current_bill = $row1['current_bill'];

			
			$q7 = "SELECT * FROM collector WHERE user_id='$collector_id'";
			$r7 = mysqli_query($conn,$q7);
			$row7 = mysqli_fetch_assoc($r7);
			$supervisor_id = $row7["supervisor_id"];


			/**Add data to Transaction Table collector**/
			$q2 = "INSERT INTO collector_transaction(collector_id,consumer_id,date_time,amount,receipt_number,supervisor_id) VALUES ('$collector_id','$consumer_id','$date_time','$amount_collected','$receipt_number','$supervisor_id')";
			mysqli_query($conn,$q2);

			/**Update Consumer Information**/
            $adjustment = $adjustment + ($amount_collected - $amount_to_be_collected);
            $am = $current_bill - $total_amount_paid;
			$q6 = "UPDATE consumer SET total_amount_paid='$total_amount_paid', adjustment='$adjustment', total_amount_to_be_paid='$am' WHERE user_id='$consumer_id'";
			mysqli_query($conn,$q6);

			/** Update supervisor data **/
			/**$q8 = "SELECT * FROM supervisor WHERE user_id='$supervisor_id'";
			$r8 = mysqli_query($conn,$q8);
			$row8 = mysqli_fetch_assoc($r8);
			$supervisor_target = $row8["collected"];
			$supervisor_target = $supervisor_target + $amount_collected;

			//Update supervisor table
			$q9 = "UPDATE supervisor SET target='$supervisor_target' WHERE user_id='$supervisor_id'";
			mysqli_query($conn,$q9);**/


			/** Update collectors data**/

			//Acquire previous collected amount by the collector
			$q5 = "SELECT * FROM collector WHERE user_id='$collector_id'";
			$r5 = mysqli_query($conn,$q5);
			$row5 = mysqli_fetch_assoc($r5);
			$cash_in_hand= $amount_collected + $row5['cash_in_hand'];

			//Update Collector Table
			$q4 = "UPDATE collector SET cash_in_hand = '$cash_in_hand' WHERE user_id='$collector_id'";
			mysqli_query($conn,$q4);

			$response['success'] = true;
			$response['status'] = 200;
			$response['message'] = "Data updated successfully";
			echo json_encode($response);
		}
	}
}
else{
	$response['success'] = false;
	$response['status'] = 400;
	$response['message'] = "Data not set";
	echo json_encode($response);
}
?>