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

require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');

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
		$GET_EVENT = "SELECT e.* FROM ef_events e
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
