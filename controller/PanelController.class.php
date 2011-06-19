<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('db/DBConfig.class.php');

class PanelController {
	private $smarty;
	private $dbCon;
	private $DEBUG = true;
	
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
				require_once('models/EFMail.class.php');
				
				$newEvent = json_decode($_SESSION['newEvent'], true);
				$this->dbCon->createNewEvent($newEvent);
				
				// INVITE GUESTS USING EMAIL
				$mailer = new EFMail();
				$guestEmails = "";
				$emailSize = sizeof($newEvent['guests']);

				$eid = explode('/', $newEvent['url']);
				$newEvent['eid'] = $eid[sizeof($eid) - 1];
				
				$this->dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
				for ($i = 0; $i < $emailSize; ++$i) {
					$guestEmails .= $newEvent['guests'][$i];
					if ($i < $emailSize - 1) {
						$guestEmails .= ", ";
					}
				}
				$mailer->sendEmail($guestEmails, $newEvent['title'], $newEvent['url']);
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
	
	public function checkGuests(&$eventInfo) {
		$eid = explode('/', $_REQUEST['url']);
		$eid = $eid[sizeof($eid) - 1];
		$csvFile = 'upload/event/csv-'.$eid.'.csv';
		
		if ($_REQUEST['guest_email'] != '') {
			$eventInfo->setGuests($_REQUEST['guest_email']);
		} else if (file_exists($csvFile)) {
			$eventInfo->setGuestsFromCSV($csvFile);
		}
	}
	
	public function displayAttendeePage($eventId) {
		$eventAttendees = $this->dbCon->getAttendeesByEvent($eventId);
		$eventInfo = $this->dbCon->getEventInfo($eventId);
		
		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}
		
		$this->smarty->assign('eventAttendees', $eventAttendees);
		$this->smarty->assign('eventInfo', $eventInfo);
		$this->smarty->display('manage_event_on.tpl');
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
			
			$_SESSION['ceid'] = $eventId;
			
			if (isset($_SESSION['uid'])) {
				$hasAttend = $this->dbCon->hasAttend($_SESSION['uid'], $eventId);
				
				$this->smarty->assign('conf'.$hasAttend['confidence'], 'checked = "checked"');
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
															 $_REQUEST['goal'],
															 $_REQUEST['address'], 
															 $_REQUEST['date'],
															 $_REQUEST['time'],
															 $_REQUEST['deadline'],
															 $_REQUEST['description'], 
															 $_REQUEST['cost'],
															 $_REQUEST['is_public'],
															 $_REQUEST['gets']);
				
				$this->checkGuests($eventInfo);
															
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
															$_REQUEST['goal'],
															$_REQUEST['address'], 
															$_REQUEST['date'],
															$_REQUEST['time'],
															$_REQUEST['deadline'],
															$_REQUEST['description'], 
															$_REQUEST['cost'],
															$_REQUEST['is_public'],
															$_REQUEST['gets']);
				
				$this->checkGuests($newEvent);

				$this->checkNewEvent($newEvent, false);
				break;
			case '/event/image/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg", "csv");
				
				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;
				
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/');
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/event/attend':
				$_SESSION['attend_event'] = $this->dbCon->getEventInfo($_SESSION['ceid']);
				$this->dbCon->eventSignUp($_SESSION['uid'], $_SESSION['ceid'], $_REQUEST['conf']);
				break;
			case '/event/edit':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventDateTime = explode(" ", $eventInfo['event_datetime']);
				$eventDate = $this->dbCon->dateToRegular($eventDateTime[0]);
				
				$eventTime = explode(":", $eventDateTime[1]);
				$eventTime = $eventTime[0].":".$eventTime[1];
				
				$eventInfo['event_datetime'] = $eventDate." ".$eventTime;
				$eventInfo['event_deadline'] = $this->dbCon->dateToRegular($eventInfo['event_deadline']);
				
				$isPublic = $eventInfo['is_public'];
				$isEventPrivate = '';
				$isEventPublic = '';
				if ($isPublic == 1) {
					$isEventPublic = 'checked = "checked"';
				} else {
					$isEventPrivate = 'checked = "checked"';
				}
				
				$this->smarty->assign('isEventPublic', $isEventPublic);
				$this->smarty->assign('isEventPrivate', $isEventPrivate);
				
				$this->smarty->assign('eventDate', $eventDate);
				$this->smarty->assign('eventTime', $eventTime);
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->display('update_event_form.tpl');
				break;
			case '/event/edit/guest':
				$eventAttendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$eventAttendeeEmails = "";
				for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
					$eventAttendeeEmails .= $eventAttendees[$i]['email'];
					if ($i < sizeof($eventAttendees) - 1) {
						$eventAttendeeEmails .= ", ";
					}
				}
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('eventAttendeeEmails', $eventAttendeeEmails);
				$this->smarty->assign('prevPage', $_REQUEST['prevPage']);
				$this->smarty->display('event_invite_guest_update.tpl');
				break;
			case '/event/edit/guest/inviter':
				require_once('libs/OpenInviter/openinviter.php');
				$inviter = new OpenInviter();
				$oi_services = $inviter->getPlugins();
				
				if (isset($_REQUEST['oi_email']) && isset($_REQUEST['oi_pass'])) {
					$inviter->startPlugin($_REQUEST['oi_provider']);
					$internal = $inviter->getInternalError();
					if ($internal && $this->DEBUG) {
						print($internal);
					}
					$inviter->login($_REQUEST['oi_email'], $_REQUEST['oi_pass']);
					
					$_POST['oi_session_id'] = $inviter->plugin->getSessionID();
					$contactList = $inviter->getMyContacts();
					
					$this->smarty->assign('contactList', $contactList);
					$this->smarty->display('event_add_guest_import_contact_list.tpl');
				} else {
					$this->smarty->assign('provider', $_REQUEST['provider']);
					$this->smarty->display('event_add_guest_right.tpl');
				}
				break;
			case '/event/edit/guest/save':
				
				break;
			case '/event/manage':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$numGuestConf1 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT1);
				$numGuestConf2 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT2);
				$numGuestConf3 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT3);
				$numGuestConf4 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT4);
				$numGuestConf5 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT5);
				$numGuestNoResp = $this->dbCon->getNumAttendeesNoResponse($_REQUEST['eventId']);
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('guestConf1', $numGuestConf1['guest_num']);
				$this->smarty->assign('guestConf2', $numGuestConf2['guest_num']);
				$this->smarty->assign('guestConf3', $numGuestConf3['guest_num']);
				$this->smarty->assign('guestConf4', $numGuestConf4['guest_num']);
				$this->smarty->assign('guestConf5', $numGuestConf5['guest_num']);
				$this->smarty->assign('guestNoResp', $numGuestNoResp['guest_num']);
				
				$this->smarty->display('manage_event_form.tpl');
				break;
			case '/event/manage/on':
				$this->displayAttendeePage($_REQUEST['eventId']);
				break;
			case '/event/manage/email/save':
				$sqlDate = $this->dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".$_REQUEST['reminderTime'].":00";
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				$this->dbCon->saveEmail($_REQUEST['eventId'], 
															  $_REQUEST['reminderContent'],
																$dateTime,
																$_REQUEST['reminderSubject'],
																$_REQUEST['type'],
																$autoReminder);
				break;
			case '/event/manage/email/send':
				require_once('models/EFMail.class.php');
				$mailer = new EFMail();
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$attendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				$mailer->sendAutomatedEmail($eventInfo, 
																	 $_REQUEST['reminderContent'],
																	 $_REQUEST['reminderSubject'],
																	 $attendees);
				break;
			case '/event/manage/email/autosend':
				$isActivated = 0;
				if ($_REQUEST['autoSend'] == 'true') {
					$isActivated = 1;
				}
				$this->dbCon->setAutosend($_REQUEST['eventId'], $_REQUEST['type'], $isActivated);
				break;
			case '/event/manage/after':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventResult = $this->dbCon->getEventResult($_REQUEST['eventId']);
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('eventResult', $eventResult['guest_num']);
				$this->smarty->display('manage_event_after.tpl');
				break;
			case '/event/email':
				$eventReminder = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_REMINDER_TYPE);
				$eventFollowup = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_FOLLOWUP_TYPE);
				
				if ($eventReminder['is_activated'] == 1) {
					$eventReminder['isAuto'] = 'checked = "checked"';
				}
				if ($eventFollowup['is_activated'] == 1) {
					$eventFollowup['isAuto'] = 'checked = "checked"';
				}
				
				$this->smarty->assign('eventReminder', $eventReminder);
				$this->smarty->assign('eventFollowup', $eventFollowup);
				
				$this->smarty->display('manage_event_email.tpl');
				break;
			case '/event/checkin':
				$isAttend = 1;
				if ($_REQUEST['checkin'] == 'false') {
					$isAttend = 0;
				}
				$this->dbCon->checkInGuest($isAttend, $_REQUEST['guestId'], $_REQUEST['eventId']);
				break;
			case '/event/print':
				$this->displayAttendeePage($_REQUEST['eventId']);
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