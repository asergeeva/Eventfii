<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('libs/Mailgun/Mailgun.php');
require_once('db/DBConfig.class.php');

class EmailReminder {
	private $dbCon;
	private $mailer;
		
	function __construct() {
		$this->dbCon = new DBConfig();
		$this->mailer = new EFMail();
	}
	
	function __destruct() {
		
	}
	
	public function sendReminders() {
		$GET_REMINDER_MESSAGES = "SELECT * FROM ef_event_messages m, ef_events e, ef_attendance a, ef_users u WHERE 
																m.event_id = e.id AND
																e.id = a.event_id AND
																a.user_id = u.id AND
																m.delivery_time BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
		$messages = $this->dbCon->getQueryResultAssoc($GET_REMINDER_MESSAGES);
		for ($i = 0; $i < sizeof($messages); ++$i) {
			$this->mailer->sendReminder($messages['email'], $messages['title'], $messages['url']);
			print("Sent reminder email to ".$messages['email']." for event_id = ".$messages['event_id']);
		}
		print("-- Cron job for sending reminders COMPLETED --");
	}
}

$emailCron = new EmailReminder();
$emailCron->sendReminders();