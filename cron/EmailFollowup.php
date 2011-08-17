<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 *
 * How to run this:
 *      php EmailFollowup.php <email_template> <interval> <subject>
 *      e.g. php EmailReminder.php reminder_attendee 1 'This is a reminder'
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
	private $interval;
	private $subject;
		
	public function __construct($template, $interval, $subject) {
		$this->template = $template;
		$this->interval = $interval;
		$this->subject = $subject;
	
		$this->dbCon = new DBConfig();
		$this->mailer = new EFMail();
	}
	
	public function __destruct() {
		
	}
	
	public function sendFollowups() {
		$GET_EVENT = "SELECT e.* FROM ef_events e
						WHERE e.event_datetime BETWEEN DATE_SUB(CURDATE(), INTERVAL ".$this->interval."+1 DAY) AND CURDATE()";
		
		$events = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		for ($i = 0; $i < sizeof($events); ++$i) {
			$event = new Event($events[$i]);
						
			$this->mailer->sendGuestsHtmlEmailByEvent($this->template, $event, $this->subject);
			print("Sent reminder email for event_id = ".$event->eid."\n");
		}
		print("-- Cron job for sending Email reminders COMPLETED --\n");
	}
}

$common = new EFCommon();

$emailCron = new EmailFollowup($argv[1], $argv[2], $argv[3]);
$emailCron->sendFollowups();
