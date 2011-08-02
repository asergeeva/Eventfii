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
	
	// TWILIO VERIFIED NUMBER
	// PIEDMONT, CA
  // +1408706RSVP
	private $FROM = "408-706-7787";
	
	private $client;
	
	public function __construct() {
		$this->client = new TwilioRestClient($this->AccountSid, $this->AuthToken);
	}
	
	public function __destruct() {
		
	}
	
	/**
	 * Sending SMS reminder to the guests of the event that 
	 * will be held in at least 2 hours from now
	 * $smsRecipients DB row from the result of the query
	 * http://www.twilio.com/docs/quickstart/sms/sending-via-rest
	 **/
	public function sendSMSReminder($smsRecipients, $eventInfo, $message) {
		foreach ($smsRecipients as $smsRecipient) {
			// Send a new outgoinging SMS by POSTing to the SMS resource */
			$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/SMS/Messages", 
				"POST", array(
				"To" => $smsRecipient['phone'],
				"From" => $this->FROM,
				"Body" => $message
			));
			
			if ($response->IsError) {
				print("Error: {".$response->ErrorMessage."}");
			} else {
				print("Sent message to ".$smsRecipient['phone']." for ".$eventInfo['title']);
			}
    }
	}
}