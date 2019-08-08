<?php
define("DB_HOST", "localhost");
define("DB_NAME", "career.loggcity");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "thir3a6-i");

function connect()
{
    $connection = mysqli_connect( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
    if (!$connection) {
        die( 'Could not connect database' );
    }
    return $connection;
}
$connection = connect();
    if(isset($_GET['id'])) {
        $sql = "SELECT file_type,file_data,file_name,extension,file_size FROM ohrm_certificates WHERE id=" . $_GET['id'];

        $result = mysqli_query($connection, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["file_type"]);
        header('Content-Disposition: attachment; filename="'.$row['file_name'].'"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0');
        header('Pragma: no-cache');
        header("Content-Length: ".$row['file_size']);
        echo $row["file_data"];
	}
	mysqli_close($connection);
?>