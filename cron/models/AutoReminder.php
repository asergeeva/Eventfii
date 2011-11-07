<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 *
 * This is always going to be sent for guest
 * How to run this:
 *      php AutoReminder.php <interval_minute> <type>
 *		type - 1 for Email, 2 for SMS, 3 for Followup
 *      e.g. php AutoReminder.php 15 1
 */
class AutoReminder {
	private $dbCon;
	private $sms;
	private $mailer;
	private $efCom;
	
	private $logger;
	
	const EMAIL_TYPE = 1;
	const SMS_TYPE = 2;
		
	public function __construct() {
		$this->dbCon = new DBConfig();
		$this->sms = new EFSMS();
		$this->mailer = new EFMail();
		$this->efCom = new EFCommon();
		
		$this->logger = fopen(realpath(dirname(__FILE__)).'/logs/'.AUTO_REMINDER_CRON_LOG, 'a');
	}
	
	public function __destruct() {
		fclose($this->logger);
	}
	
	public function sendReminders($interval_minute, $type) {
		$GET_EVENT = "SELECT
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
						e.*,
						m.*
					  FROM ef_events e, ef_event_messages m 
						WHERE e.id = m.event_id AND m.type = ".$type." AND 
						e.event_datetime = DATE_ADD(NOW(), INTERVAL ".$interval_minute." MINUTE)";
		
		$event_messages = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Sending ".sizeof($event_messages)." auto reminders --\n");
		for ($i = 0; $i < sizeof($event_messages); ++$i) {
			$event = new Event($event_messages[$i]);
			
			switch ($type) {
				case self::EMAIL_TYPE:
					for ($i = 0; $i < sizeof($event->guests); ++$i) {
						$this->mailer->sendHtmlEmail('general', 
												      $event->guests[$i], 
												      $event_message['subject'], 
												      $event, 
												      $event_message['message']);
					}
					fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent guests reminder Email for event_id = ".$event->eid."\n");
					break;
				case self::SMS_TYPE:
					$this->sms->sendSMSReminder($event->guests, $event, $event_message['message']);
					fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent guests reminder SMS for event_id = ".$event->eid."\n");
					break;
			}
		}
		
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Cron job for sending Automated message reminders COMPLETED --\n");
	}
}