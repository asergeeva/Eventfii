<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 *
 * This is always going to be sent for guest
 * How to run this:
 *      php AutoReminder.php <interval_day> <interval_hour> <type>
 *		type - 1 for Email, 2 for SMS
 *      e.g. php AutoReminder.php 1 2 1
 */

require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');

class AutoReminder {
	private $dbCon;
	private $sms;
	private $mailer;
	
	private $interval_day;
	private $interval_hour;
	private $type;
		
	public function __construct($interval_day, $interval_hour, $type) {
		$this->interval_day = $interval_day;
		$this->interval_hour = $interval_hour;
		$this->type = $type;
	
		$this->dbCon = new DBConfig();
		$this->sms = new EFSMS();
		$this->mailer = new EFMail();
	}
	
	public function __destruct() {
		
	}
	
	public function sendReminders() {
		$GET_EVENT = "SELECT * FROM ef_events e, ef_event_messages m 
						WHERE e.id = m.event_id AND m.type = ".$this->$type." AND 
						e.event_datetime = DATE_ADD(NOW(), INTERVAL '".$this->interval_day." ".$this->interval_hour."' DAY_HOUR)";
		
		$event_messages = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		for ($i = 0; $i < sizeof($event_messages); ++$i) {
			$event = new Event($event_messages[$i]);
			
			switch ($this->type) {
				case 1:
					for ($i = 0; $i < sizeof($event->guests); ++$i) {
						$this->mailer->sendHtmlEmail('general', 
												      $event->guests[$i], 
												      $event_message['subject'], 
												      $event, 
												      $event_message['message']);
					}
					print("Sent guests reminder Email for event_id = ".$event->eid."\n");
					break;
				case 2:
					$this->sms->sendSMSReminder($event->guests, $event, $event_message['message']);
					print("Sent guests reminder SMS for event_id = ".$event->eid."\n");
					break;
			}
		}
		print("-- Cron job for sending Automated message reminders COMPLETED --\n");
	}
}
$emailCron = new AutoReminder($argv[1], $argv[2], $argv[3]);
$emailCron->sendReminders();
