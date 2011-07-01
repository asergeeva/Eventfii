<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('libs/twilio.php');

class EFSMS {
	private $ApiVersion = "2010-04-01";
	private $AccountSid = "ACadbc504a2ad22a2b5876fdbc0ab59e1f";
	private $AuthToken = "2547c0dca8f4186c381f2faf13622686";
	
	private $FROM = "415-599-2671";
	
	private $client;
	
	function __construct() {
		$this->client = new TwilioRestClient($this->AccountSid, $this->AuthToken);
	}
	
	function __destruct() {
		
	}
	
	/**
	 * Send text message to the people that is specified as the following
	 * associative array:
	 * 		$people = array(
	 *	 		"+14158675309"=>"Curious George",
	 *	 		"+14158675310"=>"Boots",
	 *   		"+14158675311"=>"Virgil",
	 * 		);
	 * $message   String the plain text message
	 * $eventInfo Event object representation from the database
	 **/
	public function sendSMS($people, $message, $eventInfo) {
		foreach ($people as $number => $name) {
			// Send a new outgoinging SMS by POSTing to the SMS resource */
			$response = $client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/SMS/Messages", 
				"POST", array(
				"To" => $number,
				"From" => $this->FROM,
				"Body" => "Don't forget to attend ".$eventInfo['title']." in 2 hours"
			));
			
			if ($response->IsError) {
				print("Error: {".$response->ErrorMessage."}");
			} else {
				print("Sent message to ".$name." for ".$eventInfo['title']);
			}
    }
	}
}