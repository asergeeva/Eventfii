<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 *
 * How to run this:
 *      php EmailFollowup.php <email_template> <interval_day> <interval_hour> <subject> <guest or host>
 *      e.g. php EmailFollowup.php reminder_after 1 2 'This is a followup' host
 *			- follow_up (Guest)
 *			- reminder_after (Host)
 */

require_once(realpath(dirname(__FILE__)).'/../html/configs.php');
require_once(realpath(dirname(__FILE__)).'/../html/models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../html/libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../html/db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../html/models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../html/models/Event.class.php');

class EmailFollowup {
	private $dbCon;
	private $mailer;
	
	private $template;
	private $interval_day;
	private $interval_hour;
	private $subject;
	private $forGuest;
		
	public function __construct($template, $interval_day, $interval_hour, $subject, $forGuest) {
		$this->template = $template;
		$this->interval_day = $interval_day;
		$this->interval_hour = $interval_hour;
		$this->subject = $subject;
		$this->forGuest = if (strtolower($forGuest) == 'guest') ? true : false;
	
		$this->dbCon = new DBConfig();
		$this->mailer = new EFMail();
	}
	
	public function __destruct() {
		
	}
	
	public function sendFollowups() {
		$GET_EVENT = "SELECT 
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
						e.*
					  FROM ef_events e
						WHERE e.event_datetime = DATE_SUB(NOW(), INTERVAL '".$this->interval_day." ".$this->interval_hour."' DAY_HOUR)";
		
		$events = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		for ($i = 0; $i < sizeof($events); ++$i) {
			$event = new Event($events[$i]);
						
			if (!$this->forGuest) {
				$this->mailer->sendHtmlEmail($this->template, $event->organizer, $this->subject, $event);
				print("Sent host followup email for event_id = ".$event->eid."\n");
			} else {
				$this->mailer->sendGuestsHtmlEmailByEvent($this->template, $event, $this->subject);
				print("Sent guest followup email for event_id = ".$event->eid."\n");
			}
		}
		print("-- Cron job for sending Email followup COMPLETED --\n");
	}
}

$emailCron = new EmailFollowup($argv[1], $argv[2], $argv[3], $argv[4], $argv[5]);
$emailCron->sendFollowups();
