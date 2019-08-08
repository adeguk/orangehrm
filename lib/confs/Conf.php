<?php
class Conf {

	var $smtphost;
	var $dbhost;
	var $dbport;
	var $dbname;
	var $dbuser;
	var $version;

	function __construct() {

		$this->dbhost	= 'localhost';
		$this->dbport 	= '3306';
		if(defined('ENVIRNOMENT') && ENVIRNOMENT == 'test'){
		$this->dbname    = 'test_career.loggcity';		
		}else {
		$this->dbname    = 'career.loggcity';
		}
		$this->dbuser    = 'root';
		$this->dbpass	= 'thir3a6-i';
		$this->version = '4.3.1';

		$this->emailConfiguration = dirname(__FILE__).'/mailConf.php';
		$this->errorLog =  realpath(dirname(__FILE__).'/../logs/').'/';
	}
}
?>