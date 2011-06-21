<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once('libs/Mailgun/Mailgun.php');

class EFMail {
	private $FROM = "no-reply@eventfii.com";
	
	function __construct() {
		mailgun_init('key-afy6amxoo2fnj$u@mc');
	}
	
	function __destruct() {
		
	}
	
	public function sendEmail($to, $eventName, $eventUrl) {
		$subject = 'You are invited to '.$eventName;
		$message = 'Link: '.$eventUrl;
		
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