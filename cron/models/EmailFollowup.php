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
class EmailFollowup {
	private $dbCon;
	private $mailer;
	private $efCom;
	
	private $logger;
		
	public function __construct() {
		$this->dbCon = new CronDB();
		$this->mailer = new EFMail();
		$this->efCom = new EFCommon();
		
		$this->logger = fopen(realpath(dirname(__FILE__)).'/../logs/'.EMAIL_FOLLOWUP_CRON_LOG, 'a');
	}
	
	public function __destruct() {
		fclose($this->logger);
	}
	
	public function sendFollowups($template, $interval_minute, $subject, $forGuest) {
		$isForGuest = (strtolower($forGuest) == 'guest') ? true : false;
		
		$GET_EVENT = "SELECT 
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
						e.*
					  FROM ef_events e
						WHERE e.event_datetime BETWEEN 
						DATE_SUB(NOW(), INTERVAL ".$interval_minute." MINUTE)
						AND
						NOW()";
		
		$events = $this->dbCon->getQueryResultAssoc($GET_EVENT);
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Sending ".sizeof($events)." email followups --\n");
		for ($i = 0; $i < sizeof($events); ++$i) {
			print_r($events[$i]);
			$event = new Event($events[$i]);
			
			if ($this->dbCon->recordOutgoingMessage($event->eid, $event->organizer->id, $template)) {
				if (!$isForGuest) {
					$this->mailer->sendHtmlEmail($template, $event->organizer, $subject, $event);
					fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent host followup email for event_id = ".$event->eid."\n");
				} else {
					$this->mailer->sendGuestsHtmlEmailByEvent($template, $event, $subject);
					fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] Sent guest followup email for event_id = ".$event->eid."\n");
				}
			}
		}
		fwrite($this->logger, "[".date("Y-m-d H:i:s"). "] -- Cron job for sending Email followup COMPLETED --\n");
	}
}