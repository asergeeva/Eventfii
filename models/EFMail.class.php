<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
class EFMail {
	private $FROM = "no-reply@eventfii.com";
	
	function __construct() {
		
	}
	
	function __destruct() {
		
	}
	
	public function sendEmail($to, $eventName, $eventUrl) {
		$subject = 'You are invited to '.$eventName;
		$message = 'Link: '.$eventUrl;
		$headers = 'From: '.$this->FROM."\r\n".
							 'Reply-To: '.$this->FROM."\r\n".
							 'X-Mailer: PHP/'.phpversion();
		
		mail($to, $subject, $message, $headers);
	}
	
	/**
	 * $attendees Array  list of all attendees
	 * $eventInfo Object the Event object from the DBMS
	 */
	public function sendAutomatedEmail($eventInfo, $content, $subject, $attendees) {
		$message =  $content."\r\n".
								"Link: ".$eventInfo['url'];
		$headers = 'From: '.$this->FROM."\r\n".
							 'Reply-To: '.$this->FROM."\r\n".
							 'X-Mailer: PHP/'.phpversion();
		for ($i = 0; $i < sizeof($attendees); ++$i) {
			mail($attendees[$i]['email'], $subject, $message, $headers);
		}
	}
}