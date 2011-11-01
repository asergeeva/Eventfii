<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/AdminDB.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');


class AdminController {
	private $dbCon;
	
	public function __construct() {
		$this->dbCon = new AdminDB();
	}
	
	public function __destruct() {
		
	}
	
	private function displayEvents() {
		EFCommon::$smarty->assign('num_events', $this->dbCon->admin_getNumEvents());	
		EFCommon::$smarty->assign('num_users', $this->dbCon->admin_getNumUsers());	
		EFCommon::$smarty->assign('num_invites', $this->dbCon->admin_getTotalNumInvites());
		
		$eventList = $this->dbCon->admin_getEventList();
		$newEvents = array();
		foreach ($eventList as $eventInfo) {
			$event = new Event($eventInfo['id']);
		
			$eventInfo['num_invites'] = $this->dbCon->admin_getNumInvites($event->eid);
			$eventInfo['fb_invite'] = $this->dbCon->admin_getNumFbInvites($event->eid);
			$eventInfo['num_checked'] = $this->dbCon->admin_getNumChecked($event->eid);
			$eventInfo['truersvp'] = EFCommon::$core->getTrueRSVP($event);
			
			$newEvents[] = $eventInfo;
		}
		EFCommon::$smarty->assign('events', $newEvents);
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		
		// Make sure that HTTPS is always on
		if (!isset($_SERVER['HTTPS'])) {
			header('Location: '.CURHOST.'/'.$requestUri[1]);
		}
		
		$requestUri = '/'.$requestUri[2];
				
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$current_page = substr($requestUri, 0, $getParamStartPos);
			$params = substr($requestUri, $getParamStartPos, strlen($requestUri) - 1 );
		} else {
			$current_page = $requestUri;
		}
		
		switch ($current_page) {
			case '/':
				$this->displayEvents();
				EFCommon::$smarty->display('admin/index.tpl');
				break;
			default:
				EFCommon::$smarty->assign('requestUri', $requestUri);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}