<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/DBAPI.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/User.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');
class APIController {
	private $dbCon;
	private $result;
	//private $efCore;
	private $DEBUG = true;
	
	public function __construct() {
		$this->dbCon = new DBAPI();
		//$this->efCore = new EFCore();
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
		
		$event = new Event( $eventInfo );
		
		return $event;
	}
	
	private function handleFBLogin() {
		$userInfo = EFCommon::$dbCon->facebookConnect( $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['fbid'], $_POST['access'], $_POST['session'] );
		if ( $userInfo ) {
			$_SESSION['user'] = $userInfo['id'];
			if ( isset ($params) ) {
				echo $params;
			} else {
				echo 1;
			}
		} else {
			echo 0;
		}
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
				if ( isset($_POST['isFB']) ) 
				{
					if($this->dbCon->m_checkFBUser($_POST['email']))
					{
						$this->handleFBLogin();
						echo 'status_loginSuccess';
						break;
					}
					else
					{
						echo 'status_doesNotExist';
						break;
					}
				} else {
					$userId = EFCommon::$dbCon->checkValidUser( $_POST['email'], $_POST['pass'] );			
					if( isset($userId) && is_array($userId)) {
						$_SESSION['user'] = $userId['id'];
						echo 'status_loginSuccess';
					}
					else {
						echo 'status_loginFailed';
					}
					
				}
				break;
			case 'getUserInfo':
				echo json_encode(new User($_SESSION['user']));
				break;
			/*case 'getUserID':
				echo $_SESSION['user'];
				break;*/
			/*case 'setUserInfo':
				echo $this->dbCon->m_updateUserInfo($_REQUEST['email'],$_REQUEST['about'],$_REQUEST['zip'],$_REQUEST['phone'],$_REQUEST['twitter']);
				$_SESSION['user'] = unserialize($_SESSION['user']);
				$_SESSION['user']->email = $_REQUEST['email'];
				$_SESSION['user']->about = $_REQUEST['about'];
				$_SESSION['user']->zip = $_REQUEST['zip'];
				$_SESSION['user']->phone = $_REQUEST['phone'];
				$_SESSION['user']->twitter = $_REQUEST['twitter'];
				$_SESSION['user'] = serialize($_SESSION['user']);
				break;
			case 'getOrganizerEmail':
				echo json_encode($this->dbCon->getUserInfo($_REQUEST['oid']));
				break;*/
			case 'getAttendingEvents':
				$attendingEvents = $this->dbCon->getEventAttendingByUid($_SESSION['user']);
				for($i=0; $i < count($attendingEvents); $i++)
				{
					$attendingEvents[$i]['is_attending'] = $this->dbCon->m_isAttending($_SESSION['user'], $attendingEvents[$i]['id']);
					$attendingEvents[$i]['confidence'] = $this->dbCon->m_getConfidence($_SESSION['user'], $attendingEvents[$i]['id']);
					$attendingEvents[$i]['email'] = $this->dbCon->m_getOrganizerEmail($_SESSION['user'], $attendingEvents[$i]['id']);
				}				
				echo json_encode($attendingEvents);
				break;
			/*case 'getAttendanceForEvent':
				echo json_encode($this->dbCon->hasAttend(unserialize($_SESSION['user'])->id, $_REQUEST['eid']));
				break;
			case 'setAttendanceForEvent':
				$event = new Event($_REQUEST['eid']);
				echo json_encode($this->dbCon->m_eventSignUp(unserialize($_SESSION['user'])->id, $event, $_REQUEST['confidence']));
				break;*/
			case 'setAttendanceForEventWithDate':
				$event = new Event($_REQUEST['eid']);
				echo json_encode($this->dbCon->m_eventSignUpWithDate($_SESSION['user'], $event, $_REQUEST['confidence'], $_REQUEST['date']));
				break;
			case 'getHostingEvents':
				$hostingEvents = $this->dbCon->getEventByEO($_SESSION['user']);				
				for($i=0; $i < count($hostingEvents); $i++)
				{
					$event = new Event($hostingEvents[$i]['id']);
					$hostingEvents[$i]['score'] = EFCommon::$core->getTrueRSVP($event);
					$hostingEvents[$i]['guestList'] = $this->dbCon->m_getGuestListByEvent($hostingEvents[$i]['id']);
				}
				echo json_encode($hostingEvents);
				break;
			case 'getGuestList':
				echo json_encode($this->dbCon->m_getGuestListByEvent($_REQUEST['eid']));
				break; 
			/*case 'checkInByDistance':
				$this->dbCon->checkInGuest('1', unserialize($_SESSION['user'])->id, $_REQUEST['eid']);
				echo 'status_checkInSuccess';
				break;
			case 'checkIn':
				echo json_encode($this->dbCon->checkInGuest($_REQUEST['checkIn'], $_REQUEST['uid'], $_REQUEST['eid']));
				break;*/
			case 'checkInWithDate':
				echo json_encode($this->dbCon->m_checkInGuestWithDate($_REQUEST['checkIn'], $_REQUEST['uid'], $_REQUEST['eid'], $_REQUEST['date']));
				break;		
			/*case 'getCheckInDate':
				echo json_encode($this->dbCon->m_getCheckInDate($_REQUEST['eid'], $_REQUEST['uid']));
				break;*/
			case 'computeTrueRSVP':
				echo json_encode(EFCommon::$core->getTrueRSVP($_REQUEST['eid']));
				break;
			case 'sendMessage':
				if (isset($_SESSION['user'])) {
					$temp = $_SESSION['user'];
					$_SESSION['user'] = new User($_SESSION['user']);
					$event = $this->buildEvent( $_REQUEST['eventId'] );	
					$contacts = $_REQUEST['uid'];
					$guests = array();
					for($i=0; $i < count($contacts); $i++)
					{
						$newUser = new User($this->dbCon->m_getUserInfoFromEvent($contacts[$i]));
						array_push($guests, $newUser);
					}		
					if($_REQUEST['form'] == 'email' || $_REQUEST['form'] == 'both') 
					{
						for ($i = 0; $i < sizeof($guests); ++$i) 
						{
							EFCommon::$mailer->sendHtmlEmail('general', $guests[$i], $_POST['reminderSubject'], $event, $_POST['reminderContent']);
						}
					}
					if($_REQUEST['form'] == 'text' || $_REQUEST['form'] == 'both') 
					{
						for ($i = 0; $i < sizeof($guests); ++$i) 
						{
							if(strlen($guests[$i]->phone) < 7)
							{
								unset($guests[$i]);
							}
						}
						$guests = array_values($guests);
						EFCommon::$sms->sendSMSReminder($guests, $event, EFCommon::$mailer->mapText($_REQUEST['reminderContent'], $event->eid));
					}
					echo("status_emailSuccess");
					$_SESSION['user'] = $temp;
				}
				break;
			case 'getUsername':
				echo json_encode($this->dbCon->m_getUsername($_REQUEST['uid']));
				break;
			/*case 'uploadImage':
				$_SESSION['user'] = unserialize($_SESSION['user']);
				$allowedExtensions = array("jpg");
				$sizeLimit = 2 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('../upload/user/', TRUE);
				$result['file'] = str_replace('../', '', $result['file']);
				EFCommon::$dbCon->saveUserPic($result['file']);
				
				//echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				$_SESSION['user'] = serialize($_SESSION['user']);
				break;*/
			case 'isAttending':
				//$_SESSION['user'] = unserialize($_SESSION['user']);
				echo json_encode($this->dbCon->m_isAttending($_SESSION['user'], $_REQUEST['eid']));
				//$_SESSION['user'] = serialize($_SESSION['user']);
				break;
			case 'logout':
				if ( ! isset($_SESSION['user']) ) {
					header('Location: '.CURHOST);
					break;
				}
				session_unset();
				session_destroy();
				break;
			case 'ping':
				if (isset($_SESSION['user'])) {
					echo 'pong';
				}
				break;
			case 'addGuest':
				$temp = $_SESSION['user'];
				$_SESSION['user'] = new User($_SESSION['user']);
				$checkExists = FALSE;
				if($this->dbCon->m_checkEmailExists($_REQUEST['emails']))
				{
					$checkExists = TRUE;
				}
				$event = new Event($_POST['eid']);
				$event->submitGuests();
				
				if(strlen($_REQUEST['fname']) > 0 && strlen($_REQUEST['lname']) > 0 && $checkExists)
				{
					$this->dbCon->m_updateUserNamesWithEmail($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['emails']);
				}
				$guestID = $this->dbCon->m_getIDFromEmail($_REQUEST['emails']);
				$this->dbCon->m_eventSignUpWithDate($guestID, $event, 90, $_REQUEST['date']);
				$_SESSION['user'] = $temp;
				break;
			default:
				EFCommon::$smarty->assign('requestUri', $requestUri);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}