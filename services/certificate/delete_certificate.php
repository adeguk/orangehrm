<?php
define("DB_HOST", "localhost");
define("DB_NAME", "career.loggcity");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "thir3a6-i");

function connect(){

    $connection=mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
    if(!$connection){
        die('Could not connect database');
    }
    return $connection;
}

$conn = connect();
if(isset($_POST['certi_id'])) {
    $certi_id = trim($_POST['certi_id']);

            $sql = "DELETE FROM ohrm_certificates WHERE id in ($certi_id)";
            $resultset = mysqli_query($conn, $sql) or die("database error:" . mysqli_error($conn));
            echo $certi_id;

}
?>
