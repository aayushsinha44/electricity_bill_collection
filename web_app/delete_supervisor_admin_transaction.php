<?php
include('api/config/conn.php');
if(isset($_GET["id"])){
          $id = $_GET["id"];
          $q = "SELECT * FROM supervisor_admin_transaction WHERE id='$id'";
          $r = mysqli_query($conn,$q);
          $row = mysqli_fetch_assoc($r);
          $amount = $row["amount"];
          $supervisor_id = $row["supervisor_id"];

          // Reset data in supervisor table
          $q = "SELECT * FROM supervisor WHERE user_id = '$supervisor_id'";
          $r = mysqli_query($conn,$q);
          $row = mysqli_fetch_assoc($r);
          $cash_in_hand = $row["cash_in_hand"];

          //Update supervisor table
          $cash_in_hand = $cash_in_hand + $amount;
          $q = "UPDATE supervisor SET cash_in_hand = '$cash_in_hand' WHERE user_id='$supervisor_id'";
          $r = mysqli_query($conn,$q);

          $q2 = "DELETE FROM supervisor_admin_transaction WHERE id='$id'";
          $r2 = mysqli_query($conn,$q2);

          header("Location: supervisor_admin_transaction.php?success_delete=$r2");
          
        
}
?>