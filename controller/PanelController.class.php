<?php
require('db/DBConfig.class.php');

class PanelController {
	private $smarty;
	private $dbCon;
	
	function __construct($smarty) {
		$this->smarty = $smarty;
		$this->dbCon = new DBConfig();
	}
	
	function __destruct() {
		
	}
	
	private function assignCPEvents($uid) {
		$createdEvents = $this->dbCon->getEventByEO($uid);
		$attendingEvents = $this->dbCon->getEventAttendingBy($uid);
		$userInfo = $this->dbCon->getUserInfo($uid);
		
		$this->smarty->assign('maxEventId', $this->dbCon->getMaxEventId());
		
		$this->smarty->assign('createdEvents', $createdEvents);
		$this->smarty->assign('attendingEvents', $attendingEvents);
		$this->smarty->assign('userInfo', $userInfo);
	}
	
	private function checkHome() {
		if (isset($_SESSION['uid'])) {
			$this->assignCPEvents($_SESSION['uid']);
			
			$this->smarty->display('cp.tpl');
		} else {
			$this->smarty->display('home.tpl');
		}
	}
	
	public function checkNewEvent($newEvent) {
		if (isset($newEvent)) {
			$_SESSION['newEvent'] = json_encode($newEvent);
		}
		
		if (isset($_SESSION['uid'])) {
			if (isset($_SESSION['newEvent']) && isset($_SESSION['newEvent']['organizer'])) {
				$newEvent = json_decode($_SESSION['newEvent'], true);
				$this->dbCon->createNewEvent($newEvent);
			}
			
			$this->assignCPEvents($_SESSION['uid']);
			$this->smarty->display('cp_container.tpl');
		} else {
			$this->smarty->display('login.tpl');
		}
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		
		if (preg_match("/event\/\d+/", $requestUri) > 0) {
			$eventId = explode('/', $requestUri);
			$eventId = $eventId[sizeof($eventId)-1];
			
			$eventInfo = $this->dbCon->getEventInfo($eventId);
			$organizer = $this->dbCon->getUserInfo($eventInfo['organizer']);
			$curSignUp = $this->dbCon->getCurSignup($eventId);
			
			$this->smarty->assign('organizer', $organizer);
			$this->smarty->assign('eventInfo', $eventInfo);
			$this->smarty->assign('eventId', $eventId);
			$this->smarty->assign('curSignUp', $curSignUp);
			
			if (isset($_SESSION['uid'])) {
				$userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
				$this->smarty->assign('userInfo', $userInfo);
				$this->smarty->display('event.tpl');
				return;
			}
			$this->smarty->display('event_guest.tpl');
			return;
		}
		
		// Remove GET parameters
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$requestUri = substr($requestUri, 0, $getParamStartPos);
		}
		
		switch ($requestUri) {
			case '/':
				$this->checkHome();
				break;
			case '/home':
				$this->smarty->assign('eventTitle', $_REQUEST['eventTitle']);
				$this->smarty->assign('maxEventId', $this->dbCon->getMaxEventId());
				$this->smarty->display('create_event_home.tpl');
				break;
			case '/event/create':
				require('models/Event.class.php');
				
				$this->smarty->assign('eventTitle', $_REQUEST['eventTitle']);
				$this->smarty->assign('eventId', $this->dbCon->getMaxEventId());
				$this->smarty->assign('domain', CURHOST);
				$this->smarty->display('cp.tpl');
				break;
			case '/event/update':
				require('models/Event.class.php');
				$eventInfo = new Event($_SESSION['uid'],
															 $_REQUEST['title'], 
															 $_REQUEST['url'], 
															 $_REQUEST['min_spot'],
															 $_REQUEST['max_spot'], 
															 $_REQUEST['address'], 
															 $_REQUEST['date'],
															 $_REQUEST['time'],
															 $_REQUEST['deadline'],
															 $_REQUEST['description'], 
															 $_REQUEST['cost'],
															 $_REQUEST['is_public'],
															 $_REQUEST['gets']);
															
				$eventInfo->eid = $_REQUEST['eventId'];
				$this->dbCon->updateEvent($eventInfo);
				$this->assignCPEvents($_SESSION['uid']);
				$this->smarty->display('cp_container.tpl');
				break;
			case '/event/submit':
				require('models/Event.class.php');
				require('models/Location.class.php');
				
				$newEvent = new Event($_SESSION['uid'],
															$_REQUEST['title'], 
															$_REQUEST['url'], 
															$_REQUEST['min_spot'],
															$_REQUEST['max_spot'], 
															$_REQUEST['address'], 
															$_REQUEST['date'],
															$_REQUEST['time'],
															$_REQUEST['deadline'],
															$_REQUEST['description'], 
															$_REQUEST['cost'],
															$_REQUEST['is_public'],
															$_REQUEST['gets']);
				$this->checkNewEvent($newEvent);
				break;
			case '/event/attend':
				if ($this->dbCon->eventSignUp($_REQUEST['uid'], $_REQUEST['eid'])) {
					$this->smarty->display('event_signup_success.tpl');
					break;
				}
				$this->smarty->display('event_signup_failed.tpl');
				break;
			case '/event/edit':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventDateTime = explode(" ", $eventInfo['event_datetime']);
				$eventDate = $this->dbCon->dateToRegular($eventDateTime[0]);
				$eventTime = $eventDateTime[1];
				
				$eventInfo['event_datetime'] = $eventDate." ".$eventTime;
				$eventInfo['event_deadline'] = $this->dbCon->dateToRegular($eventInfo['event_deadline']);
				
				print(json_encode($eventInfo));
				break;
			case '/user/create':
				$this->dbCon->createNewUser($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['pass']);
				$userId = $this->dbCon->checkValidUser($_REQUEST['email'], $_REQUEST['pass']);
				
				if (isset($_SESSION['newEvent'])) {	
					$newEvent = json_decode($_SESSION['newEvent'], true);
					$newEvent['organizer'] = $userId;
				}
				
				$_SESSION['uid'] = $userId;
				
				$this->checkNewEvent($newEvent);
				break;
			case '/login':
				if (!isset($_SESSION['uid'])) {
					$userId = $this->dbCon->checkValidUser($_REQUEST['email'], $_REQUEST['pass']);
					$_SESSION['uid'] = $userId;
					
					if (isset($_SESSION['newEvent'])) {
						$newEvent = json_decode($_SESSION['newEvent'], true);
						$newEvent['organizer'] = $userId;
						$this->checkNewEvent($newEvent);
						break;
					}
					$this->checkNewEvent(NULL);
					break;
				}
				$this->checkHome();
				break;
			case '/logout':
				session_unset();
				$this->smarty->display('home.tpl');
				break;
			default:
				$this->smarty->display('error.tpl');
				break;
		}
	}
}