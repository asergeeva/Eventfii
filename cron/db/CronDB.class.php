<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
class CronDB extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function recordOutgoingMessage($eventId, $senderId, $messageTemplate) {
		if ($this->isSending($eventId, $senderId, $messageTemplate)) {
			$RECORD_OUTGOING = "INSERT INTO ef_cron_messages 
								(
									event_id, 
									sender_id, 
									message_template
								) 
								VALUES 
								(
									".$eventId.",
									".$senderId.",
								   '".$messageTemplate."'
								)";
			$this->executeUpdateQuery($RECORD_OUTGOING);
			return true;
		}
		return false;
	}
	
	private function isSending($eventId, $senderId, $messageTemplate) {
		$IS_SENDING = "SELECT FROM ef_cron_messages
							WHERE event_id = ".$eventId." 
								AND sender_id = ".$senderId.",
								AND message_template = '".$messageTemplate."'
							)";
		if ($this->getRowNum($RECORD_OUTGOING) == 0) {
			return true;
		}
		return false;
	}
}