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
		$GET_ATTENDEES = "SELECT * FROM ef_events e, ef_attendance a, ef_users u WHERE 
																							e.id = a.event_id AND a.user_id = u.id AND 
																							u.phone IS NOT NULL AND 
																							e.event_datetime BETWEEN NOW() + INTERVAL 2 HOUR AND NOW() + INTERVAL 3 HOUR";
		$smsRecipients = $this->dbCon->getQueryResultAssoc($GET_ATTENDEES);
		$this->sms->sendSMSReminder($smsRecipients);
		print("-- Cron job for sending SMS reminders COMPLETED --");
	}
}