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
  <title>Collector</title>
  <link rel="stylesheet" type="text/css" href="css/home.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>


    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Collector</a>
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
              <a class="nav-link active" href="collector.php">Collectors</a>
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
          <h1>Dashboard</h1>

          
          <h2>Collector List</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>UserID</th>
                  <th>Phone Number</th>
                  <th>Email ID</th>
                  <th>Supervisor ID</th>
                  <th>Cash In hand</th>
                  <th>Update</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                <?php
                  $page = 1;
                  if(isset($_GET["page"])){
                    $page = $_GET["page"];
                  }

                  include('api/config/conn.php');
                  $a = 20 * ($page-1);
                  $q1 = "SELECT * FROM collector LIMIT 20 OFFSET ".$a;
                  $r1 = mysqli_query($conn,$q1);
                  $allDataLoaded = 0;
                  if(mysqli_num_rows($r1)==0) $allDataLoaded = 1;
                  $i=1;
                  while($row = mysqli_fetch_assoc($r1)){
                    echo "<tr><td>".$i."</td>";
                    echo "<td>".$row['name']."</td>";
                    echo "<td>".$row['address']."</td>";
                    echo "<td>".$row['user_id']."</td>";
                    echo "<td>".$row['phone_number']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['supervisor_id']."</td>";
                    echo "<td>".$row['cash_in_hand']."</td>";
                    echo "<td><a href='update_collector.php?id=".$row['user_id']."'><button class='btn btn-outline-success btn-sm'>Update</button></td>";
                    echo "<td><a href='delete_collector.php?id=".$row['user_id']."'><button class='btn btn-outline-danger btn-sm'>Delete</button></a></td></tr>";
                    $i=$i+1;
                  }
                ?>
              </tbody>
            </table>
             <div style="float: right; margin-right: 30px;"><a href="supervisor.php?page=<?php echo ($page+1); ?>"><button class='btn btn-info btn-lg' style="<?php if($allDataLoaded) echo "display: none" ?>">Next</button></a>
            </div>
            <div style="float: left; margin-left: 20px;"><a href="supervisor.php?page=<?php echo ($page-1); ?>"><button class='btn btn-info btn-lg' style="<?php if($page==1) echo "display: none" ?>">Previous</button></a>
            </div>

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