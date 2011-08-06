<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/DBAPI.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCore.class.php');

class APIController {
	private $smarty;
	private $dbCon;
	private $result;
	private $efCore;
	private $DEBUG = true;
	
	public function __construct($smarty) {
		$this->smarty = $smarty;
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
		
		$this->smarty->assign('eventInfo', $eventInfo);
		
		require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');
		$event = new Event( $eventInfo );
		
		return $event;
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		$requestUri = $requestUri[2];
		
		switch ($requestUri) {
			case 'login':
				if(!isset($_SESSION['uid']))
				{
					if ( $_REQUEST['isFB'] ) 
					{
						echo $_REQUEST['fname'];
						echo $_REQUEST['lname'];
						$userInfo = $this->dbCon->facebookConnect( $_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['fbid'] );
						if ( $userInfo ) 
						{
							$_SESSION['uid'] = $userInfo['id'];
							if ( isset ($params) ) 
							{
								echo $params;
							}
						}
					}
					else
					{
						if(isset($_REQUEST['email']) && isset($_REQUEST['password']))
						{
							$result = $this->dbCon->m_checkValidUser($_REQUEST['email'], $_REQUEST['password']);
							if(!isset($result))
							{
								echo 'status_loginFailed';
							}
							else
							{
								$_SESSION['uid'] = $result;
							}
						}
					}
				}
				if(isset($_SESSION['uid']))
				{
					echo 'status_loginSuccess';
				}
				break;
			case 'getUserInfo':
				echo json_encode($this->dbCon->getUserInfo($_SESSION['uid']));
				break;
			case 'setUserInfo':
				echo $this->dbCon->m_updateUserInfo($_REQUEST['email'],$_REQUEST['about'],$_REQUEST['zip'],$_REQUEST['cell'],$_REQUEST['twitter']);
				break;
			case 'getOrganizerEmail':
				echo json_encode($this->dbCon->getUserInfo($_REQUEST['oid']));
				break;
			case 'getAttendingEvents':
				echo json_encode($this->dbCon->m_getEventAttendingBy($_SESSION['uid']));
				break;
			case 'setAttendanceForEvent':
				echo json_encode($this->dbCon->eventSignUp($_SESSION['uid'], $_REQUEST['eid'], $_REQUEST['confidence']));
				break;
			case 'getHostingEvents':
				echo json_encode($this->dbCon->getEventByEO($_SESSION['uid']));				
				break;
			case 'getGuestList':
				echo json_encode($this->dbCon->m_getGuestListByEvent($_REQUEST['eid']));
				break; 
			case 'checkInByDistance':
				$this->dbCon->checkInGuest('1', $_SESSION['uid'], $_REQUEST['eid']);
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
				$mailer = new EFMail();
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
					$mailer->sendAutomatedEmail($event, 
						$_REQUEST['reminderContent'], 
						$_REQUEST['reminderSubject'], 
						$attendees);
				}		
				if($_REQUEST['form'] == 'sms' || $_REQUEST['form'] == 'both') 
				{
					echo("Text");
					$sms->sendSMSReminder($attendees, 
						$event->eid, 
						$mailer->mapText($_REQUEST['reminderContent'], 
						$event->eid));
				}
				echo("status_emailSuccess");
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}