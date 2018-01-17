<?php
include('api/config/conn.php');
if(isset($_GET["id"])){
          $id = $_GET["id"];
          $q2 = "DELETE FROM collector WHERE user_id='$id'";
          $r2 = mysqli_query($conn,$q2);
          
            header("Location: collector.php?success_delete=$r2");
          
        
}
?>