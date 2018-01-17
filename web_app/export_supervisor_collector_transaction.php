<?php
include 'api/config/conn.php';
if(isset($_GET["sdate"]) && isset($_GET["fdate"])){
    $sdate = $_GET["sdate"];
    $fdate = $_GET["fdate"];
    $q1 = "SELECT * FROM supervisor_collector_transaction WHERE date_time>= '$sdate' AND date_time <= '$fdate' AND supervisor_id LIKE '" . $supervisor_id . "%' AND collector_id LIKE '" . $collector_id . "%'";
                  $r1 = mysqli_query($conn,$q1);

    if(mysqli_num_rows($r1)> 0){
        $delimiter = ",";
        $filename = "supervisor_collector_transaction_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Sl. No.', 'Supervisor ID', 'Collector ID' ,'Amount', 'Date Time');
        fputcsv($f, $fields, $delimiter);
        
        //output each row of the data, format line as csv and write to file pointer
        $i = 1;
        while($row = mysqli_fetch_assoc($r1)){
            $lineData = array($i, $row['supervisor_id'], $row["collector_id"] ,$row['amount'], $row['date_time']);
            fputcsv($f, $lineData, $delimiter);
            $i = $i + 1;
        }
        
        //move back to beginning of file
        fseek($f, 0);
        
        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        
        //output all remaining data on a file pointer
        fpassthru($f);
    }
    exit;
}
else{
    header("Location: supervisor_admin_transaction.php");
}

?>