<?php
define( "DB_HOST", "localhost" );
define( "DB_NAME", "career.loggcity" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "thir3a6-i" );


function connect(){

    $connection=mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
    if(!$connection){
        die('Could not connect database');
    }
    return $connection;
}

$conn = connect();
if(isset($_POST['deduct_id'])) {
    $deduct_id = trim($_POST['deduct_id']);
    $sql = "DELETE FROM hs_hr_emp_deductions WHERE id in ($deduct_id)";
    $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
    echo $deduct_id;
}
?>