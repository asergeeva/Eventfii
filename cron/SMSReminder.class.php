<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('db/DBConfig.class.php');
require_once('models/EFSMS.class.php');

class SMSReminder {
	private $dbCon;
	private $sms;
	
	function __construct() {
		$this->dbCon = new DBConfig();
		$this->sms = new EFSMS();
	}
	
	function __destruct() {
		
	}
	
	public function sendReminder() {
		
		print("-- Cron job for sending SMS reminders COMPLETED --");
	}
}