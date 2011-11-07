<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 *
 * How to run this:
 *      php EmailReminder.php <email_template> <interval_day> <interval_hour> <subject> <guest or host>
 *      e.g. php EmailReminder.php reminder_attendee 1 2 'This is a reminder' guest
 *			- reminder_attendee (Guest)
 *			- reminder_4days (Host)
 *			- reminder_dayof (Host)
 */
class EmailReminder {
	private $dbCon;
	private $mailer;
	private $efCom;
	
	private $logger;
		
	public function __construct() {
		$this->dbCon = new DBConfig();
		$this->mailer = new EFMail();
		$this->efCom = new EFCommon();
		
		$this->logger = fopen(realpath(dirname(__FILE__)).'/logs/'.EMAIL_REMINDER_CRON_LOG, 'a');
	}
	
	public function __destruct() {
		fclose($this->logger);
	}
	
	private function sendReminders($template, $interval_day, $interval_hour, $subject, $forGuest) {
		$isForGuest = (strtolower($forGuest) == 'guest') ? true : false;
	
		$GET_EVENT = "SELECT
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
						e.*
					  FROM ef_events e
						WHERE e.event_datetime BETWEEN 
							DATE_ADD(NOW(), INTERVAL '".$interval_day." ".$interval_hour."' DAY_HOUR)
							AND 
							DATE_ADD(NOW(), INTERVAL '".($interval_day + 1)." ".$interval_hour."' DAY_HOUR)";
		
		$events = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Sending ".sizeof($events)." email reminders --\n");
		for ($i = 0; $i < sizeof($events); ++$i) {
			print_r($events[$i]);
			$event = new Event($events[$i]);
			
			if (!$isForGuest) {
				$this->mailer->sendHtmlEmail($template, $event->organizer, $subject, $event);
				fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent host reminder email for event_id = ".$event->eid."\n");
			} else {
				$this->mailer->sendGuestsHtmlEmailByEvent($template, $event, $subject);
				fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent guest reminder email for event_id = ".$event->eid."\n");
			}
		}
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Cron job for sending Email reminders COMPLETED --\n");
	}
}
