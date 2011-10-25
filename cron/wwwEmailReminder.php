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

require_once(realpath(dirname(__FILE__)).'/../domains/truersvp.com/html/configs.php');
require_once(realpath(dirname(__FILE__)).'/../domains/truersvp.com/html/models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/truersvp.com/html/libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../domains/truersvp.com/html/db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/truersvp.com/html/models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/truersvp.com/html/models/Event.class.php');

class EmailReminder {
	private $dbCon;
	private $mailer;
	private $efCom;
	
	private $template;
	private $interval_day;
	private $interval_hour;
	private $subject;
	private $forGuest;
	
	
	private $logger;
		
	public function __construct($template, $interval_day, $interval_hour, $subject, $forGuest) {
		$this->template = $template;
		$this->interval_day = $interval_day;
		$this->interval_hour = $interval_hour;
		$this->subject = $subject;
		$this->forGuest = (strtolower($forGuest) == 'guest') ? true : false;
	
		$this->dbCon = new DBConfig();
		$this->mailer = new EFMail();
		$this->efCom = new EFCommon();
		
		$this->logger = fopen(realpath(dirname(__FILE__)).'/logs/'.EMAIL_REMINDER_CRON_LOG, 'a');
	}
	
	public function __destruct() {
		fclose($this->logger);
	}
	
	public function sendReminders() {
		$GET_EVENT = "SELECT
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
						e.*
					  FROM ef_events e
						WHERE e.event_datetime BETWEEN 
							DATE_ADD(NOW(), INTERVAL '".$this->interval_day." ".$this->interval_hour."' DAY_HOUR)
							AND 
							DATE_ADD(NOW(), INTERVAL '".($this->interval_day + 1)." ".$this->interval_hour."' DAY_HOUR)";
		
		$events = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Sending ".sizeof($events)." email reminders --\n");
		for ($i = 0; $i < sizeof($events); ++$i) {
			print_r($events[$i]);
			$event = new Event($events[$i]);
			
			if (!$this->forGuest) {
				$this->mailer->sendHtmlEmail($this->template, $event->organizer, $this->subject, $event);
				fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent host reminder email for event_id = ".$event->eid."\n");
			} else {
				$this->mailer->sendGuestsHtmlEmailByEvent($this->template, $event, $this->subject);
				fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent guest reminder email for event_id = ".$event->eid."\n");
			}
		}
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Cron job for sending Email reminders COMPLETED --\n");
	}
}
$emailReminder = new EmailReminder($argv[1], $argv[2], $argv[3], $argv[4], $argv[5]);
$emailReminder->sendReminders();
