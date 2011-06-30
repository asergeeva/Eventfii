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
		
		// Make sure that address is not empty
		if ($newEvent->address == "") {
			$this->smarty->display('create_event_form.tpl');
		} else {
			if (isset($_SESSION['uid'])) {
				if (isset($_SESSION['newEvent']) && isset($_SESSION['newEvent']['organizer'])) {
					require_once('models/EFMail.class.php');
					
					$newEvent = json_decode($_SESSION['newEvent'], true);
					$this->dbCon->createNewEvent($newEvent);
					
					// INVITE GUESTS USING EMAIL
					$mailer = new EFMail();
	
					$eid = explode('/', $newEvent['url']);
					$newEvent['eid'] = $eid[sizeof($eid) - 1];
					
					$this->dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
					$mailer->sendEmail($newEvent['guests'], $newEvent['eid'], $newEvent['title'], $newEvent['url']);
				}
				
				$this->assignCPEvents($_SESSION['uid']);
				
				if ($loadCp) {
					$this->smarty->display('cp.tpl');
				} else {
					$this->smarty->display('cp_container.tpl');	
				}
			} else {
				$this->smarty->display('login_form.tpl');
			}
		}
	}
	
	public function checkGuests(&$eventInfo) {
		$eid = explode('/', $eventInfo->url);
		$eid = $eid[sizeof($eid) - 1];
		$csvFile = CSV_UPLOAD_PATH.'/'.$eid.'.csv';
		
		if ($_REQUEST['guest_email'] != '') {
			$eventInfo->setGuests($_REQUEST['guest_email']);
		} else if (file_exists($csvFile)) {
			$eventInfo->setGuestsFromCSV($csvFile);
		}
	}
	
	public function displayAttendeePage($eventId) {
		require_once('models/EFCore.class.php');
		$efCore = new EFCore();
				
		$eventAttendees = $this->dbCon->getAttendeesByEvent($eventId);
		$eventInfo = $this->dbCon->getEventInfo($eventId);
		
		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}
		
		$this->smarty->assign('trsvpVal', $efCore->computeTrueRSVP($eventId));
		$this->smarty->assign('eventAttendees', $eventAttendees);
		$this->smarty->assign('eventInfo', $eventInfo);
		$this->smarty->display('manage_event_on.tpl');
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		
		// Check for email invite reference
		if (isset($_REQUEST['ref'])) {
			$_SESSION['ref'] = $_REQUEST['ref'];
		}
		
		// Event profile page
		if (preg_match("/event\/\d+/", $requestUri) > 0) {
			$eventId = explode('/', $requestUri);
			$eventId = $eventId[sizeof($eventId)-1];
			
			$eventId = explode('?', $eventId);
			$eventId = $eventId[0];
			
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
				if (intval($eventInfo['is_public']) == 1 || $this->dbCon->isInvited($_SESSION['uid'], $eventId)) {
					$userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
					$this->smarty->assign('userInfo', $userInfo);
					$this->smarty->display('event.tpl');
				} else {
					$this->smarty->display('event_private.tpl');
				}
				return;
			}
			
			if (intval($eventInfo['is_public']) == 1) {
				$this->smarty->display('event_guest.tpl');
			} else {
				$this->smarty->display('event_guest_private.tpl');
			}
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
															 $_REQUEST['gets'],
															 $_REQUEST['type']);
				
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
															$_REQUEST['gets'],
															$_REQUEST['type']);
				
				$this->checkGuests($newEvent);

				$this->checkNewEvent($newEvent, false);
				break;
			case '/event/image/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");
				
				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;
				
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/images/', TRUE);
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/event/csv/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("csv");
				
				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;
				
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/csv/', TRUE);
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
				
				// Event type presentation
				$eventType = array(
					't1' => "",
					't2' => "",
					't3' => "",
					't4' => "",
					't5' => "",
					't6' => "",
					't7' => "",
					't8' => "",
					't9' => "",
					't10' => "",
					't11' => "",
					't12' => "",
					't13' => "",
					't14' => "",
					't15' => "",
					't16' => ""
				);
				$eventType['t'.$eventInfo['type']] = 'selected = "selected"';
				
				$this->smarty->assign('isEventPublic', $isEventPublic);
				$this->smarty->assign('isEventPrivate', $isEventPrivate);
				
				$this->smarty->assign('eventType', $eventType);
				$this->smarty->assign('eventDate', $eventDate);
				$this->smarty->assign('eventTime', $eventTime);
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->display('update_event_form.tpl');
				break;
			case '/event/edit/guest':
				$eventAttendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
	
				$this->smarty->assign('eventInfo', $eventInfo);
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
				require_once('models/Event.class.php');
				require_once('models/EFMail.class.php');
				
				$eventInfoDB = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$eventInfo = new Event($_SESSION['uid'],
															 $eventInfoDB['title'], 
															 $eventInfoDB['url'], 
															 $eventInfoDB['goal'],
															 $eventInfoDB['address'], 
															 $eventInfoDB['date'],
															 $eventInfoDB['time'],
															 $eventInfoDB['deadline'],
															 $eventInfoDB['description'], 
															 $eventInfoDB['cost'],
															 $eventInfoDB['is_public'],
															 $eventInfoDB['gets']);
				$eventInfo->eid = $_REQUEST['eventId'];
				
				$this->checkGuests($eventInfo);
				
				$mailer = new EFMail();
				$mailer->sendEmail($eventInfo->guests, $eventInfo->eid, $eventInfo->title, $eventInfo->url);
				$this->dbCon->storeGuests($eventInfo->guests, $_REQUEST['eventId'], $_SESSION['uid']);
				break;
			case '/event/manage':
				require_once('models/EFCore.class.php');
				$efCore = new EFCore();
			
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$numGuestConf1 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT1);
				$numGuestConf2 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT2);
				$numGuestConf3 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT3);
				$numGuestConf4 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT4);
				$numGuestConf5 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT5);
				$numGuestConf6 = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFOPT6);
				
				$numGuestNoResp = $this->dbCon->getNumAttendeesByConfidence($_REQUEST['eventId'], CONFELSE);
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('guestConf1', $numGuestConf1['guest_num']);
				$this->smarty->assign('guestConf2', $numGuestConf2['guest_num']);
				$this->smarty->assign('guestConf3', $numGuestConf3['guest_num']);
				$this->smarty->assign('guestConf4', $numGuestConf4['guest_num']);
				$this->smarty->assign('guestConf5', $numGuestConf5['guest_num']);
				$this->smarty->assign('guestConf6', $numGuestConf6['guest_num']);
				$this->smarty->assign('guestNoResp', $numGuestNoResp['guest_num']);
				
				$this->smarty->assign('guestimate', $efCore->computeGuestimate($_REQUEST['eventId']));
				$this->smarty->assign('trsvpVal', $efCore->computeTrueRSVP($_REQUEST['eventId']));
				
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
				require_once('models/EFCore.class.php');
				$efCore = new EFCore();
				
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventResult = $this->dbCon->getEventResult($_REQUEST['eventId']);
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('eventResult', $eventResult['guest_num']);
				$this->smarty->assign('trsvpVal', $efCore->computeTrueRSVP($_REQUEST['eventId']));
				
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
				$userInfo = $this->dbCon->createNewUser($_REQUEST['fname'], 
																								$_REQUEST['lname'], 
																								$_REQUEST['email'], 
																								$_REQUEST['phone'], 
																								$_REQUEST['pass']);
				
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
					if (isset($userId)) {
						$_SESSION['uid'] = $userId;
					}
					
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
			case '/login/reset':
				if ($this->dbCon->isValidPassResetRequest($_REQUEST['ref'])) {
					$this->smarty->assign('ref', $_REQUEST['ref']);
					$this->smarty->display('login_reset.tpl');
				} else {
					$this->smarty->display('login_reset_invalid.tpl');
				}
				break;
			case '/login/reset/submit':
				if ($_REQUEST['login_forgot_newpass'] == $_REQUEST['login_forgot_newpass_conf']) {
					$this->dbCon->resetPasswordByEmail($_REQUEST['login_forgot_newpass'], $_REQUEST['login_forgot_ref']);
					$this->smarty->display('login_reset_confirmed.tpl');
				} else {
					$this->smarty->assign('ref', $_REQUEST['ref']);
					$this->smarty->assign('errorMsg', 'New password is not confirmed');
					$this->smarty->display('login_reset.tpl');
				}
				break;
			case '/login/forgot':
				$this->smarty->display('login_forgot.tpl');
				break;
			case '/login/forgot/submit':
				require_once('models/EFMail.class.php');
				$mailer = new EFMail();
				
				$hash_key = md5(time().$_REQUEST['login_forgot_email']);
				
				if ($this->dbCon->requestPasswordReset($hash_key, $_REQUEST['login_forgot_email'])) {
					$mailer->sendResetPassLink('/login/reset', $hash_key, $_REQUEST['login_forgot_email']);
					$this->smarty->display('login_forgot_confirmed.tpl');
				} else {
					$this->smarty->display('login_forgot_invalid.tpl');
				}
				break;
			case '/logout':
				session_unset();
				session_destroy();
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