<?php
include('api/config/conn.php');
session_start();
if(isset($_SESSION["user_id"])){

}
else{
  header("Location: index.php");
}
if(isset($_GET["success_delete"])){
  if($_GET["success_delete"]==1){
    ?><script type="text/javascript">alert("Deleted Successfully!!!");</script><?php
  }
}
if(isset($_GET["update"])){
  if($_GET["update"]==1){
    ?><script type="text/javascript">alert("Updated Successfully!!!");</script><?php
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
      <a class="navbar-brand" href="#">Dashboard</a>
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
              <a class="nav-link " href="supervisor_admin_transaction.php">Supervisor - Admin Transaction</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="supervisor_collector_transaction.php">Supervisor - Collector Transaction</a>
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
          <h1>Dashboard</h1>

          
          <h2>Supervisor List</h2>

           <form class="col-lg-3" method="POST" action="supervisor_collector_transaction.php">
            <div class="form-group" >
              <label for="email">Start Date:<font color="red">*</font></label>
              <input type="date" class="form-control" name="sdate" required>
            </div>
            <div class="form-group">
              <label for="pwd">Finish Date:<font color="red">*</font></label>
              <input type="date" class="form-control" name="fdate" required>
            </div>
            <div class="form-group">
              <label for="pwd">Supervisor ID:</label>
              <input type="text" class="form-control" name="supervisor_id">
            </div>
             <div class="form-group">
              <label for="pwd">Collector ID:</label>
              <input type="text" class="form-control" name="collector_id" >
            </div>
            <input type="submit" class="btn btn-default" name="btn">
          </form><br/>

          <?php
            $sdate; $fdate;
            function date_diff1($date1, $date2) { 
              $current = $date1; 
              $datetime2 = date_create($date2); 
              $count = 0; 
              while(date_create($current) < $datetime2){ 
                  $current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current))); 
                  $count++; 
              } 
              return $count; 
            } 
            if(isset($_POST["btn"])){
              $sdate = $_POST["sdate"];
              $fdate = $_POST["fdate"];
              $supervisor_id = $_POST["supervisor_id"];
              $collector_id = $_POST["collector_id"];
              $diff=date_diff1($sdate,$fdate);
              if($diff>0 && $diff < 180){

                  $q1 = "SELECT * FROM supervisor_collector_transaction WHERE date_time>= '$sdate' AND date_time <= '$fdate' AND supervisor_id LIKE '" . $supervisor_id . "%' AND collector_id LIKE '" . $collector_id . "%'";
                  $r1 = mysqli_query($conn,$q1);
                  $num = mysqli_num_rows($r1);

              

          ?>
          <div style="float: right; margin: 10px;<?php if($num==0) echo "display: none;"; ?>"><a href="export_supervisor_collector_transaction.php?sdate=<?php echo $sdate ?>&fdate=<?php echo $fdate ?>&supervisor_id=<?php echo $supervisor_id ?>&collector_id=<?php echo $collector_id ?>" ><button class='btn btn-info btn-lg'>Export</button></a></div>
          <div><h3>Displaying all transaction between <?php echo $sdate ?> and <?php echo $fdate ?></h3></div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Supervisor ID</th>
                  <th>Collector ID</th>
                  <th>Amount</th>
                  <th>Date Time</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                <?php

                  include('api/config/conn.php');
                  $i=1;
                  while($row = mysqli_fetch_assoc($r1)){
                    echo "<tr><td>".$i."</td>";
                    echo "<td>".$row['supervisor_id']."</td>";
                    echo "<td>".$row['collector_id']."</td>";
                    echo "<td>".$row['amount']."</td>";
                    echo "<td>".$row['date_time']."</td>";
                    /**echo "<td><a href='update_supervisor_admin_transaction.php?id=".$row['id']."'><button class='btn btn-outline-success btn-sm'>Update</button></td>";
                    echo "<td><a href='delete_supervisor_collector_transaction.php?id=".$row['id']."'><button class='btn btn-outline-danger btn-sm'>Delete</button></a></td></tr>";**/
                    $i=$i+1;
                  }
              }
              else if($diff == 0){
                ?><script type="text/javascript">alert("Invalid Input");</script><?php
              }
              else{
                ?><script type="text/javascript">alert("Differnce between dates must be less than 6 months");</script><?php
              }
            }
                ?>
              </tbody>
            </table>
            
          </div>
        </main>
      </div>
    </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>


</body>
</html>