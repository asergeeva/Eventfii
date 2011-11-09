<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../db/CronDB.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/AutoReminder.php');
require_once(realpath(dirname(__FILE__)).'/../models/EmailFollowup.php');
require_once(realpath(dirname(__FILE__)).'/../models/EmailReminder.php');

class CronController {
	const TIME_DAILY_MIDNIGHT = 'midnight';
	const TIME_DAILY_NOON = 'noon';
	const TIME_HOURLY = 'hourly';
	const TIME_15MINS = '15mins';

	private $emailReminder;
	private $autoReminder;
	private $emailFollowup;
	
	public function __construct() {
		$this->emailReminder = new EmailReminder();
		$this->autoReminder = new AutoReminder();
		$this->emailFollowup = new EmailFollowup();
	}
	
	public function __destruct() {
	
	}
	
	/**
	 * Execute the corn job on the specified name
	 */
	public function execute($timeName) {
		switch ($timeName) {
			case self::TIME_DAILY_MIDNIGHT:
				$this->emailReminder->sendReminders("reminder_attendee", 1, 0, 
									 		  		"Don't forget - {Host name}'s {Event name} is less than 24 hours away!",
									 		  		"guest");
				$this->emailReminder->sendReminders("reminder_4days", 4, 0, 
									 		  		"Reminder: {Event name} is coming up!",
									 		  		"host");
				// Database backup
				exec("mysqldump --user ".DB_USER." --password=".DB_PASS." -h ".DB_HOST." ".DB_NAME." > ".realpath(dirname(__FILE__))."/../db/backup/".DB_NAME."_bkp_".date('Y-m-d').".sql");
				break;
			case self::TIME_DAILY_NOON:
				break;
			case self::TIME_HOURLY:
				$this->emailReminder->sendReminders("reminder_dayof", 0, 1, 
									 		  		"Today is the day - {Event name} is at {Event time}",
									 		  		"host");
				$this->emailFollowup->sendFollowups("follow_up", 60, "{Guest name} - Thanks for coming to {Event name}!", "guest");
				break;
			case self::TIME_15MINS:
				$this->autoReminder->sendReminders(15, 1);
				$this->autoReminder->sendReminders(15, 2);
				$this->emailFollowup->sendFollowups("reminder_after", 15, "Congrats on making {Event name} a success!", "host");
				break;
		}
	}
}
$cronController = new CronController();
$cronController->execute($argv[1]);
