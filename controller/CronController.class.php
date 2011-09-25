<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

require_once(realpath(dirname(__FILE__)).'/../cron/AutoReminder.php');
require_once(realpath(dirname(__FILE__)).'/../cron/EmailFollowup.php');
require_once(realpath(dirname(__FILE__)).'/../cron/EmailReminder.php');

class CronController {
	public function __construct() {
	
	}
	
	public function __destruct() {
	
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		$requestUri = $requestUri[2];
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$current_page = substr($requestUri, 0, $getParamStartPos);
			$params = substr($requestUri, $getParamStartPos, strlen($requestUri) - 1 );
		} else {
			$current_page = $requestUri;
		}
		switch ($current_page) {
			case 'AutoReminder':
				//<interval_day> <interval_hour> <type>
				$emailCron = new AutoReminder($_REQUEST['interval_day'], 
											  $_REQUEST['interval_hour'], 
											  $_REQUEST['type']);
				$emailCron->sendReminders();
				break;
			case 'EmailReminder':
				//<email_template> <interval_day> <interval_hour> <subject> <guest or host>
				$emailCron = new EmailReminder($_REQUEST['email_template'], 
											   $_REQUEST['interval_day'], 
											   $_REQUEST['interval_hour'],
											   $_REQUEST['subject'], 
											   $_REQUEST['recipient']);
				$emailCron->sendReminders();
				break;
			case 'EmailFollowup':
				//<email_template> <interval_day> <interval_hour> <subject> <guest or host>
				$emailCron = new EmailFollowup($_REQUEST['email_template'], 
											   $_REQUEST['interval_day'], 
											   $_REQUEST['interval_hour'],
											   $_REQUEST['subject'], 
											   $_REQUEST['recipient']);
				$emailCron->sendFollowups();
				break;
		}
	}
}