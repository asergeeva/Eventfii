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

require_once(realpath(dirname(__FILE__)).'/../domains/qa.truersvp.com/html/configs.php');
require_once(realpath(dirname(__FILE__)).'/../domains/qa.truersvp.com/html/models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/qa.truersvp.com/html/libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../domains/qa.truersvp.com/html/db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/qa.truersvp.com/html/models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/qa.truersvp.com/html/models/Event.class.php');

class EmailFollowup {
	private $dbCon;
	private $mailer;
	private $efCom;
	
	private $template;
	private $interval_minute;
	private $subject;
	private $forGuest;
	
	private $logger;
		
	public function __construct($template, $interval_minute, $subject, $forGuest) {
		$this->template = $template;
		$this->interval_minute = $interval_minute;
		$this->subject = $subject;
		$this->forGuest = (strtolower($forGuest) == 'guest') ? true : false;
	
		$this->dbCon = new DBConfig();
		$this->mailer = new EFMail();
		$this->efCom = new EFCommon();
		
		$this->logger = fopen(realpath(dirname(__FILE__)).'/logs/'.EMAIL_FOLLOWUP_CRON_LOG, 'a');
	}
	
	public function __destruct() {
		fclose($this->logger);
	}
	
	public function sendFollowups() {
		$GET_EVENT = "SELECT 
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
						e.*
					  FROM ef_events e
						WHERE e.event_datetime = DATE_SUB(NOW(), INTERVAL ".$this->interval_minute." MINUTE)";
		
		$events = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Sending ".sizeof($events)." email followups --\n");
		for ($i = 0; $i < sizeof($events); ++$i) {
			$event = new Event($events[$i]);
						
			if (!$this->forGuest) {
				$this->mailer->sendHtmlEmail($this->template, $event->organizer, $this->subject, $event);
				fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent host followup email for event_id = ".$event->eid."\n");
			} else {
				$this->mailer->sendGuestsHtmlEmailByEvent($this->template, $event, $this->subject);
				fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent guest followup email for event_id = ".$event->eid."\n");
			}
		}
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Cron job for sending Email followup COMPLETED --\n");
	}
}
$emailReminder = new EmailFollowup($argv[1], $argv[2], $argv[3], $argv[4]);
$emailReminder->sendFollowups();