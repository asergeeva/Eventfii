<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/DBAPI.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCore.class.php');

class APIController {
	private $dbCon;
	private $result;
	private $efCore;
	private $DEBUG = true;
	
	public function __construct() {
		$this->dbCon = new DBAPI();
		$this->efCore = new EFCore();
	}
	
	public function __destruct() {
		
	}
	
	private function buildEvent($eventId) {
		$eventInfo = $this->dbCon->getEventInfo($eventId);
			
		$eventInfo['address'] = $eventInfo['location_address'];
		
		$eventDateTime = explode(" ", $eventInfo['event_datetime']);
		
		$eventInfo['date'] = $this->dbCon->dateToRegular($eventDateTime[0]);		
        $eventInfo['deadline'] = $this->dbCon->dateToRegular($eventInfo['event_deadline']);
		
		$eventTime = explode(":", $eventDateTime[1]);
		$eventInfo['time'] = $eventTime[0].":".$eventTime[1];
		
		EFCommon::$smarty->assign('eventInfo', $eventInfo);
		
		require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');
		$event = new Event( $eventInfo );
		
		return $event;
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
			case 'login':
				if ( isset($_POST['isFB']) ) {
					$this->handleFBLogin();
					break;
				} else {
					$userId = EFCommon::$dbCon->checkValidUser( $_POST['email'], $_POST['pass'] );			
					if( isset($userId)) {
						$_SESSION['user'] = serialize(new User($userId));
						echo 'status_loginSuccess';
					}
					else {
						echo 'status_loginFailed';
					}
					
				}
				break;
			case 'getUserInfo':
				echo json_encode(unserialize($_SESSION['user']));
				break;
			case 'setUserInfo':
				echo $this->dbCon->m_updateUserInfo($_REQUEST['email'],$_REQUEST['about'],$_REQUEST['zip'],$_REQUEST['cell'],$_REQUEST['twitter']);
				break;
			case 'getOrganizerEmail':
				echo json_encode($this->dbCon->getUserInfo($_REQUEST['oid']));
				break;
			case 'getAttendingEvents':
				echo json_encode($this->dbCon->m_getEventAttendingBy(unserialize($_SESSION['user'])->id));
				break;
			case 'setAttendanceForEvent':
				echo json_encode($this->dbCon->eventSignUp(unserialize($_SESSION['user'])->id, $_REQUEST['eid'], $_REQUEST['confidence']));
				break;
			case 'getHostingEvents':
				echo json_encode($this->dbCon->getEventByEO(unserialize($_SESSION['user'])->id));				
				break;
			case 'getGuestList':
				echo json_encode($this->dbCon->m_getGuestListByEvent($_REQUEST['eid']));
				break; 
			case 'checkInByDistance':
				$this->dbCon->checkInGuest('1', unserialize($_SESSION['user'])->id, $_REQUEST['eid']);
				echo 'status_checkInSuccess';
				break;
			case 'checkIn':
				echo json_encode($this->dbCon->checkInGuest($_REQUEST['checkIn'], $_REQUEST['uid'], $_REQUEST['eid']));
				break;
			case 'computeTrueRSVP':
				echo json_encode($this->efCore->computeTrueRSVP($_REQUEST['eid']));
				break;
			case 'sendMessage':
				require_once(realpath(dirname(__FILE__)).'/../models/EFMail.class.php');
				require_once(realpath(dirname(__FILE__)).'/../models/EFSMS.class.php');
				$event = $this->buildEvent( $_REQUEST['eventId'] );		
				//$mailer = new EFMail();
				$sms = new EFSMS();
				$contacts = $_REQUEST['uid'];
				
				//Newbie php coding skills:
				$attendees = array();
				for($i=0; $i < count($contacts); $i++)
				{
					array_push($attendees, $this->dbCon->getUserInfo($contacts[$i]));
				}
				//
				if($_REQUEST['form'] == 'email' || $_REQUEST['form'] == 'both') 
				{
					echo("Email");
					EFCommon::$mailer->sendAutomatedEmail($event, 
						$_REQUEST['reminderContent'], 
						$_REQUEST['reminderSubject'], 
						$attendees);
				}		
				if($_REQUEST['form'] == 'sms' || $_REQUEST['form'] == 'both') 
				{
					echo("Text");
					$sms->sendSMSReminder($attendees, 
						$event->eid, 
						EFCommon::$mailer->mapText($_REQUEST['reminderContent'], 
						$event->eid));
				}
				echo("status_emailSuccess");
				break;
			case 'getUsername':
				echo json_encode($this->dbCon->m_getUsername($_REQUEST['uid']));
				break;
			default:
				EFCommon::$smarty->assign('requestUri', $requestUri);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}