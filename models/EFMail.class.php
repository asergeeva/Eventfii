<?php
class EFMail {
	private $from = "no-reply@eventfii.com";
	
	function __construct() {
		
	}
	
	function __destruct() {
		
	}
	
	public function sendEmail($to, $eventName, $eventUrl) {
		$subject = 'You are invited to '.$eventName;
		$message = 'Link: '.$eventUrl;
		$headers = 'From: '.$this->from."\r\n" .
							 'Reply-To: '.$this->from."\r\n" .
							 'X-Mailer: PHP/'.phpversion();
		
		mail($to, $subject, $message, $headers);
	}
}