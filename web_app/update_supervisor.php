<?php
include('api/config/conn.php');
session_start();
if(isset($_SESSION["user_id"])){

}
else{
  header("Location: index.php");
}
$user_id = ""; $name = "a"; $address = ""; $phone_number = ""; $email = "";$collected = ""; $transfered = "";
if(isset($_GET["id"])){
  $id = $_GET["id"];
  $q1 = "SELECT * FROM supervisor WHERE user_id='$id'";
  $r1 = mysqli_query($conn,$q1);
  $row1 = mysqli_fetch_assoc($r1);
  $user_id = $row1["user_id"];
  $name = $row1["name"];
  $address = $row1["address"];
  $phone_number = $row1["phone_number"];
  $email = $row1["email"];
  $cash_in_hand = $row1["cash_in_hand"];
}
else{
  die("404 Not found");
}

if(isset($_POST["btn"])){
    $name = $_POST["name"];
    $address = $_POST["address"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];
    $q = "UPDATE supervisor SET name = '$name', address='$address', phone_number='$phone_number', email='$email'  WHERE user_id = '$user_id'";
    $r = mysqli_query($conn,$q);
    if($r){
         ?><script type="text/javascript">alert("Updated Successfully");</script><?php
    header("Location: supervisor.php?update=1");
    }
    else{
      ?><script type="text/javascript">alert("Something went wrong. Please try again");</script><?php
    }
  }



?>
<!DOCTYPE html>
<html>
<head>
  <title>Index</title>
  <link rel="stylesheet" type="text/css" href="css/home.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>


    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">SuperVisor</a>
      <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target=".col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>


    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="home.php">Admin<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="supervisor.php">Supervisors</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="collector.php">Collectors</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="consumer.php">Consumers</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="supervisor_admin_transaction.php">Supervisor - Admin Transaction</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="supervisor_collector_transaction.php">Supervisor - Collector Transaction</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="collector_consumer_transaction.php">Collector - Consumer Transaction</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="add_supervisor_transaction.php">Add Supervisor Transaction</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="add_supervisor.php">Add SuperVisor</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="add_collector.php">Add Collector</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="add_consumer.php">Add Consumer</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          </ul>
        </nav>

        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
          <h1>Update Data</h1>

          <form class="col-lg-3" method="POST">
            <div class="form-group" >
              <label for="email">Name:</label>
              <input type="text" class="form-control" name="name" required autofocus value="<?php  echo $name; ?>">
            </div>
            <div class="form-group">
              <label for="pwd">Address:</label>
              <input type="text" class="form-control" name="address" value="<?php  echo $address; ?>">
            </div>
            <div class="form-group">
              <label for="pwd">Phone Number:</label>
              <input type="number" class="form-control" name="phone_number" value="<?php  echo $phone_number; ?>">
            </div>
            <div class="form-group">
              <label for="pwd">Email ID:</label>
              <input type="email" class="form-control" name="email" value="<?php  echo $email; ?>">
            </div>
            <input type="submit" class="btn btn-default" name="btn" value="Update">

            <?php
              if($success){
                echo "<br/><br/><font color='red'>Generated User ID=".$userid."</font>";
              }

            ?>
          </form>

          
          
          
        </main>
      </div>
    </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>


</body>
</html>
