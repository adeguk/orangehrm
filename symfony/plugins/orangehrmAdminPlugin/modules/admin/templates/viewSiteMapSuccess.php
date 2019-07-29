
<?php
define( "DB_HOST", "localhost" );
define( "DB_NAME", "orangehrm" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );

setlocale(LC_MONETARY,"en_US");

function connect()
{

    $connection = mysqli_connect( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
    if (!$connection) {
        die( 'Could not connect database' );
    }
    return $connection;
}
$connection = connect();
$sitemap = '';
$query = "SELECT site_map FROM ohrm_sitemap WHERE id <> 0 AND status = 1 LIMIT 1";
$result = mysqli_query($connection,$query) or die('could not fetch: '.mysqli_error($connection));
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
$sitemap = $row['site_map'];
?>
<div class="box">
    <div class="head">
        <h1><?php echo __("Company Site Map") ?></h1>
    </div>

    <div class="inner" >
        <div id="messageDiv"></div>

        <?php echo trim($sitemap); ?>
    </div>
</div>



