<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once('libs/Mailgun/Mailgun.php');
require_once('db/DBConfig.class.php');

class EFMail {
	private $FROM = "no-reply@eventfii.com";
	private $dbCon;
	
	function __construct() {
		mailgun_init('key-afy6amxoo2fnj$u@mc');
		$this->dbCon = new DBConfig();
	}
	
	function __destruct() {
		
	}
	
	/**
	 * $to        Array  list of email addresses
	 * $eventName String title of the event
	 * $eventUrl  String url of the event
	 */
	public function sendEmail($to, $eventId, $eventName, $eventUrl) {
		$subject = "You are invited to ".$eventName;
		
		for ($i = 0; $i < sizeof($to); ++$i) {
			$hash_key = md5($to[$i].$eventId);
			$message = "Link: ".$eventUrl."?ref=".$hash_key;
			$RECORD_HASH_KEY = "INSERT INTO ef_event_invites (hash_key, email_to)
														VALUES ('".$hash_key."', '".$to[$i]."')";
			$this->dbCon->executeUpdateQuery($RECORD_HASH_KEY);
			MailgunMessage::send_text($this->FROM, $to[$i], $subject, $message);
		}
	}
	
	public function sendReminder($to, $eventName, $eventUrl) {
		$subject = "Reminder for ".$eventName;
		$message = "Link: ".$eventUrl;
		
		MailgunMessage::send_text($this->FROM, $to, $subject, $message);
	}
	
	/**
	 * $attendees Array  list of all attendees
	 * $eventInfo Object the Event object from the DBMS
	 */
	public function sendAutomatedEmail($eventInfo, $content, $subject, $attendees) {
		$message =  $content."\r\n".
								"Link: ".$eventInfo['url'];
		for ($i = 0; $i < sizeof($attendees); ++$i) {
			MailgunMessage::send_text($this->FROM, $attendees[$i]['email'], $subject, $message);
		}
	}
}