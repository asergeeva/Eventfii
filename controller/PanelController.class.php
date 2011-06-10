<?php
require_once('db/DBConfig.class.php');

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
		$this->smarty->assign('maxEventId', $this->dbCon->getMaxEventId());
		
		$this->assignCreatedEvents($uid);
		$this->assignUserProfile($uid);
		$this->assignAttendingEvents($uid);
	}
	
	private function assignAttendingEvents($uid) {
		$attendingEvents = $this->dbCon->getEventAttendingBy($uid);
		
		$this->smarty->assign('attendingEvents', $attendingEvents);
	}
	
	private function assignCreatedEvents($uid) {
		$createdEvents = $this->dbCon->getEventByEO($uid);
		for ($i = 0; $i < sizeof($createdEvents); ++$i) {
			if ($this->dbCon->getCurSignup($createdEvents[$i]['id']) == $createdEvents[$i]['min_spot']) {
				$createdEvents[$i]['collect_button'] = "<img src='".CURHOST."/images/up/collect.png' onclick=\"CP_EVENT.collectPaymentEvent('collect-".$createdEvents[$i]['id']."')\" />";
			}
		}
		$this->smarty->assign('createdEvents', $createdEvents);
	}
	
	private function assignUserProfile($uid) {
		$userInfo = $this->dbCon->getUserInfo($uid);
		if (isset($_SESSION['uid'])) {
			$currentUser = $this->dbCon->getUserInfo($_SESSION['uid']);
			$this->smarty->assign('currentUser', $currentUser);
		}
		$paypalEmail = $this->dbCon->getPaypalEmail($uid);
		
		$this->smarty->assign('paypalEmail', $paypalEmail['pemail']);
		$this->smarty->assign('userInfo', $userInfo);
	}
	
	private function checkHome() {
		if (isset($_SESSION['uid'])) {
			$this->assignCPEvents($_SESSION['uid']);
			
			$this->smarty->display('cp.tpl');
		} else {
			$newEvents = $this->dbCon->getNewEvents();

			$this->smarty->assign('newEvents', $newEvents);
			$this->smarty->display('home.tpl');
		}
	}
	
	public function checkNewEvent($newEvent, $loadCp) {
		if (isset($newEvent)) {
			$_SESSION['newEvent'] = json_encode($newEvent);
		}
		
		if (isset($_SESSION['uid'])) {
			if (isset($_SESSION['newEvent']) && isset($_SESSION['newEvent']['organizer'])) {
				$newEvent = json_decode($_SESSION['newEvent'], true);
				$this->dbCon->createNewEvent($newEvent);
			}
			
			$this->assignCPEvents($_SESSION['uid']);
			
			if ($loadCp) {
				$this->smarty->display('cp.tpl');
			} else {
				$this->smarty->display('cp_container.tpl');	
			}
		} else {
			$this->smarty->display('login.tpl');
		}
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		
		// Event profile page
		if (preg_match("/event\/\d+/", $requestUri) > 0) {
			$eventId = explode('/', $requestUri);
			$eventId = $eventId[sizeof($eventId)-1];
			
			$eventInfo = $this->dbCon->getEventInfo($eventId);
			$organizer = $this->dbCon->getUserInfo($eventInfo['organizer']);
			$curSignUp = $this->dbCon->getCurSignup($eventId);
			
			if (isset($_SESSION['uid'])) {
				$currentUser = $this->dbCon->getUserInfo($_SESSION['uid']);
				$this->smarty->assign('currentUser', $currentUser);
			}
			$eventInfo['description'] = stripslashes($eventInfo['description']);
			
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
		
		// User public profile page
		if (preg_match("/user\/\d+/", $requestUri) > 0) {
			$userId = explode('/', $requestUri);
			$userId = $userId[sizeof($userId)-1];
			
			$this->assignCPEvents($userId);
			if (isset($_SESSION['uid'])) {
				$this->smarty->display('user_public_profile.tpl');
				return;
			}
			$this->smarty->display('user_public_profile_guest.tpl');
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
				require_once('models/Event.class.php');
				
				$this->smarty->assign('eventTitle', $_REQUEST['eventTitle']);
				$this->smarty->assign('eventId', $this->dbCon->getMaxEventId());
				$this->smarty->assign('domain', CURHOST);
				$this->smarty->display('cp.tpl');
				break;
			case '/event/update':
				require_once('models/Event.class.php');
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
				require_once('models/Event.class.php');
				require_once('models/Location.class.php');
				
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
				
				
				$this->checkNewEvent($newEvent, false);
				break;
			case '/event/image/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");
				
				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;
				
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/');
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/event/attend':
				$_SESSION['attend_event'] = $this->dbCon->getEventInfo($_REQUEST['eid']);
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
				$userInfo = $this->dbCon->createNewUser($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['pass']);
				
				if (isset($_SESSION['newEvent'])) {	
					$newEvent = json_decode($_SESSION['newEvent'], true);
					$newEvent['organizer'] = $userInfo['id'];
				}
				
				
				$_SESSION['uid'] = $userInfo['id'];
				$this->checkNewEvent($newEvent, true);
				break;
			case '/user/profile/update':
				$this->dbCon->updatePaypalEmail($_SESSION['uid'], $_REQUEST['paypal_email']);
				$this->assignUserProfile($_SESSION['uid']);
				
				$this->smarty->display('user_profile.tpl');
				break;
			case '/event/payment/submit':
				require_once('models/PaypalPreapproveReceipt.class.php');
				
				$paypalPreapprove = new PaypalPreapproveReceipt();
				$paypalPreapprove->preapprove();
				break;
			case '/event/payment/success':
				require_once('models/PaypalPreapproveDetails.class.php');

			  if ($this->dbCon->eventSignUp($_SESSION['uid'], $_SESSION['attend_event']['id']) &&
								isset($_SESSION['uid'])) {
					$paypalPreapprove = new PaypalPreapproveDetails();
					$paypalPreapprove->preapprove();
					$this->dbCon->preapprovePayment($_SESSION['uid'],
																					$_SESSION['attend_event']['id'], 
																					$paypalPreapprove->preapprovalKey, 
																					$paypalPreapprove->response->senderEmail);
									
					$userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
					$this->smarty->assign('userInfo', $userInfo);
					
					$this->smarty->display('payment_success.tpl');
					break;
				}
				$this->smarty->display('payment_failed.tpl');
				break;
			case '/event/payment/failed':
				$this->smarty->display('payment_failed.tpl');
				break;
			case '/payment/collect':
				require_once('models/PaypalPayReceipt.class.php');
				$paypalPay = new PaypalPayReceipt();
				
				$attendees = $this->dbCon->getAttendees($_REQUEST['eventId']);
				
				// TODO: NON-ATOMIC OPERATION
				// PayPal doesn't provide an API to receive payments from multiple senders
				// But it provides an API to send payments to multiple receivers
				// https://www.x.com/thread/52330?stqc=true
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$paypalPay->pay($attendees[$i]['pemail'], $_REQUEST['receiver_email'], 
													$attendees[$i]['cost'], $attendees[$i]['pkey']);
				}
				$this->dbCon->updateCollected($_REQUEST['eventId']);
				$this->assignCreatedEvents($_SESSION['uid']);
				$this->smarty->display('event_created.tpl');
				break;
			case '/login':
				if (!isset($_SESSION['uid'])) {
					$userId = $this->dbCon->checkValidUser($_REQUEST['email'], $_REQUEST['pass']);
					$_SESSION['uid'] = $userId;
					
					if (isset($_SESSION['newEvent'])) {
						$newEvent = json_decode($_SESSION['newEvent'], true);
						$newEvent['organizer'] = $userId;
						$this->checkNewEvent($newEvent, false);
						break;
					}
					$this->checkNewEvent(NULL, false);
					break;
				}
				$this->checkHome();
				break;
			case '/logout':
				session_unset();
				$newEvents = $this->dbCon->getNewEvents();

				$this->smarty->assign('newEvents', $newEvents);
				$this->smarty->display('home.tpl');
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}