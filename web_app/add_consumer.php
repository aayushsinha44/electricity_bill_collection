<?php
include('api/config/conn.php');
session_start();
if(isset($_SESSION["user_id"])){

}
else{
  header("Location: index.php");
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
      <a class="navbar-brand" href="#">Add Consumer</a>
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
              <a class="nav-link active" href="add_consumer.php">Add Consumer</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          </ul>
        </nav>

        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
          <h1>Enter details of New Supervisor</h1>

          <form enctype="multipart/form-data" method="POST">
            <table border="1">
            <tr >
            <td colspan="2" align="center"><strong>Import CSV file</strong></td>
            </tr>
            <tr>
            <td align="center">CSV File:</td><td><input type="file" name="files" id="file"></td></tr>
            <tr >
            <td colspan="2" align="center"><input type="submit" name="btn" value="submit"></td>
            </tr>
            </table>
            <div>Upload only csv file</div>
            <div>Header of csv file</div>
            <data>sl.no.   name   address   email   phone_number   consumer_id   bill</data>
          </form>

            <?php
              if(isset($_POST["btn"])){
                $filename=$_FILES["files"]["name"];
                $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));
                //we check,file must be have csv extention
                if($ext==".csv")
                {
                  $success = 0;
                  $file = fopen($_FILES["files"]["tmp_name"], "r");
                  $emapData = fgetcsv($file, 100000, ",");
                         while (($emapData = fgetcsv($file, 100000, ",")) !== FALSE)
                         {
                            $q = "SELECT * FROM consumer WHERE user_id='$emapData[5]'";
                            $r = mysqli_query($conn,$q);
                            if(mysqli_num_rows($r) == 0){
                              $sql = "INSERT INTO consumer (name,address,email,phone_number,user_id,total_amount_to_be_paid) VALUES ('$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]')";
                              $r=mysqli_query($conn,$sql);  
                              if($r){
                                $success = 1;
                              }
                              else{
                                echo $emapData[0]." was not inserted.\n";
                              }
                            }
                            else{
                              $row = mysqli_fetch_assoc($r);
                              $previous_bill = $row["total_amount_to_be_paid"];
                              $emapData[6] = $emapData[6] + $previous_bill;
                              $q = "UPDATE consumer SET name='$emapData[1]', address='$emapData[2]', email='$emapData[3]', phone_number='$emapData[4]', total_amount_to_be_paid='$emapData[6]' WHERE user_id='$emapData[5]'";
                              $r = mysqli_query($conn,$q);
                              if($r){
                                  $success = 1;
                              }
                              else{
                                echo $emapData[0]." was not inserted.\n";
                              }
                            }
                            
                         }
                         fclose($file);
                         if($success==1) echo "CSV File has been successfully Imported.";
                         else echo "Something went wrong.";
                }
                else {
                    ?><script type="text/javascript">alert("Upload CSV file only");</script><?php
                }
              }


            ?>
        </main>
      </div>
    </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
</body>
</html>