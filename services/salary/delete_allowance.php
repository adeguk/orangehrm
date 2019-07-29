<?php
define("DB_HOST", "localhost");
define("DB_NAME", "orangehrm");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

function connect(){

    $connection=mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
    if(!$connection){
        die('Could not connect database');
    }
    return $connection;
}

$conn = connect();
if(isset($_POST['allow_id'])) {
    $allow_id = trim($_POST['allow_id']);
    $sql = "DELETE FROM hs_hr_emp_allowances WHERE id in ($allow_id)";
    $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
    echo $allow_id;
}
?>