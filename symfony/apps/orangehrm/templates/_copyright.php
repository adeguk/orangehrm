<?php 
$rootPath = realpath(dirname(__FILE__)."/../../../../");

if (@include_once $rootPath."/lib/confs/sysConf.php") {
    $conf = new sysConf();
    $version = $conf->getVersion();
}
$prodName = 'MonetDT';
$copyrightYear = date('Y');

?>
<?php echo $prodName; ?><br/>
&copy; 2018 - <?php echo $copyrightYear;?> <a href="http://www.orangehrm.com" target="_blank">MonetDT, Inc</a>. All rights reserved.
