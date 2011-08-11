<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCore.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFTwitter.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/User.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFSMS.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/FileUploader.class.php');
require_once(realpath(dirname(__FILE__)).'/../libs/OpenInviter/openinviter.php');

class PanelController {
	private $DEBUG = true;

	public function __construct() {
	
	}

	public function __destruct() {

	}
	
	/* buildEvent
	 * Returns an Event Object given an Event ID
	 *
	 * @param $eventId | The event ID
	 * @return $event | The event object
	 */
	private function buildEvent($eventId) {
		$eventInfo = EFCommon::$dbCon->getEventInfo($eventId);
		
		$eventInfo['address'] = $eventInfo['location_address'];
		
		$eventDateTime = explode(" ", $eventInfo['event_datetime']);
		
		$eventInfo['date'] = EFCommon::$dbCon->dateToRegular($eventDateTime[0]);		
        $eventInfo['deadline'] = EFCommon::$dbCon->dateToRegular($eventInfo['event_deadline']);
		
		$eventTime = explode(":", $eventDateTime[1]);
		$eventInfo['time'] = $eventTime[0].":".$eventTime[1];
		
		EFCommon::$smarty->assign('eventInfo', $eventInfo);
		
		$event = new Event( $eventInfo );
		
		$_SESSION['manage_event'] = serialize($event);
		
		return $event;
	}

	/* validateEventInfo
	 * Makes sure event info is valid
	 *
	 * @param $newEvent | The event object
	 * @return true | The information is valid
	 * @return false | Infomration is bad
	 */
	private function validateEventInfo ( &$newEvent ) {
		// Check for errors
		$error = $newEvent->get_errors();
		
		$is_valid = ( $error === false ) ? true : false;
		
		// If there are errors
		if ( ! $is_valid ) {
			if ( $error !== true )
				EFCommon::$smarty->assign('error', $error);
			return false;
		} 

		// Looks like it's valid ;)
		return true;
	}
	
	private function validateUserInfo ( &$userInfo ) {
		// Check for errors
		$error = $userInfo->get_errors();
		
		$is_valid = ( $error === false ) ? true : false;
		
		// If there are errors
		if ( ! $is_valid ) {
			if ( $error !== true )
				EFCommon::$smarty->assign('error', $error);
			return false;
		} 

		// Looks like it's valid ;)
		return true;
	}

	/* saveEventFields
     * Stores the current values for the new event
     * in an array that can be assigned in SMARTY
     * 
     * @param $newEvent | The event being saved
     * @return $event_field | The array of event information
	 */
	private function saveEventFields( $newEvent ) {

		// Save the current fields
		$event_field['title'] = $newEvent->get_title();
		$event_field['description'] = $newEvent->get_description();
		$event_field['address'] = $newEvent->get_address();
		$event_field['date'] = $newEvent->get_date();
		$event_field['time'] = $newEvent->get_time();
		$event_field['goal'] = $newEvent->get_goal();
		$event_field['deadline'] = $newEvent->get_deadline();
		$event_field['type'] = $newEvent->get_type();
		$event_field['permissions'] = $newEvent->get_permissions();
		$event_field['location_lat'] = $newEvent->get_lat();
		$event_field['location_long'] = $newEvent->get_long();
		
		EFCommon::$smarty->assign('event_field', $event_field);
	}

	/* makeNewEvent
	 * Adds the event to the database, then switches to step 2
	 *
	 * @param $newEvent | The VALIDATED event object
	 * @return true | The information is valid
	 * @return false | Infomration is bad
	 */
	private function makeNewEvent( $newEvent ) {
		// Make sure user is logged in before they can
		// create the event
		if ( ! isset($_SESSION['uid']) ) {
			$_SESSION['newEvent'] = serialize($newEvent);
			header("Location: " . CURHOST . "/login?redirect=create");
			exit;
		}
		
		EFCommon::$dbCon->createNewEvent($newEvent);
		
		$_SESSION['new_eid'] = EFCommon::$dbCon->getMaxEventId();
		
		unset($_SESSION['newEvent']);
		header("Location: " . CURHOST . "/create/guests");
		exit;
	}
	
	/* saveEvent
	 * Updates the event information in the databse
	 *
	 * @param $event | The VALIDATED event object
	 */
	private function saveEvent( $event ) {		
		EFCommon::$dbCon->updateEvent( $event );
		EFCommon::$smarty->assign("saved", true);
	}

	/*	MAIL FOR GUESTS
		require_once('models/EFMail.class.php');
		if ( is_array($newEvent) ) {
			$r = 0;
		} else {
			$newEvent = json_decode($_SESSION['newEvent'], true);
		}
		$addrss = $newEvent['address'];
		$addr = $this->check_address($addrss);	
		$newEvent['location_lat'] = $addr['lat'];
		$newEvent['location_long'] = $addr['lng'];	
		EFCommon::$dbCon->createNewEvent($newEvent);
		
		// INVITE GUESTS USING EMAIL
		$mailer = new EFMail();
		$eid = explode('/', $newEvent['url']);
		$newEvent['eid'] = $eid[sizeof($eid) - 1];

		EFCommon::$dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
		$mailer->sendEmail($newEvent['guests'], $newEvent['eid'], $newEvent['title'], $newEvent['url']);
	*/
	
	/* Potentailly reusable
	// Make sure user is logged in before they can
	// create the event
	if (isset($_SESSION['uid'])) {
		if (isset($_SESSION['newEvent'])) {
			require_once('models/EFMail.class.php');
			if ( is_array($newEvent) ) {
				$r = 0;
			} else {
				$newEvent = json_decode($_SESSION['newEvent'], true);
			}
			$addrss = $newEvent['address'];
			$addr = $this->check_address($addrss);	
			$newEvent['location_lat'] = $addr['lat'];
			$newEvent['location_long'] = $addr['lng'];	
			EFCommon::$dbCon->createNewEvent($newEvent);
			
			// INVITE GUESTS USING EMAIL
			$mailer = new EFMail();
			$eid = explode('/', $newEvent['url']);
			$newEvent['eid'] = $eid[sizeof($eid) - 1];
			
			EFCommon::$dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
			$mailer->sendEmail($newEvent['guests'], $newEvent['eid'], $newEvent['title'], $newEvent['url']);
		} */

	//checkUserCreationForm
	public function checkUserCreationForm($req) {
		$flag = 1;
		$fname = $req['fname'];
		$lname = $req['lname'];
		$email = $req['email'];
		$phone = $req['phone'];
		$pass = $req['pass'];
		$zip = $req['zip'];

		if( strlen($zip) > 0 )
			$zipcode_val = $this->valUsingRegExp($zip,"/^\d{5}(-\d{4})?$/","user_create_zipcode","Please enter a valid zip code.");

		$f_name_val = $this->valUsingRegExp($fname,"/^[A-Za-z0-9']*$/","user_create_fname","First name can only contain A-Z 0-9 '");
		$l_name_val = $this->valUsingRegExp($lname,"/^[A-Za-z0-9']*$/","user_create_lname","Last name can only contain A-Z 0-9 '");
		$email_val = $this->valEmail($email,"user_create_email","Email entered is invalid.");
		$ph_val = $this->valUsingRegExp($phone,"/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/","user_create_phone","Phone number is not in valid format");
		$pass_val = $this->valUsingRegExp($pass,"/^[A-Za-z0-9]*$/","user_create_pass","Password can only contain A-Z 0-9");

		$email_exists=EFCommon::$dbCon->emailExistsCheck($email);
		if( $f_name_val == 2 || $l_name_val == 2 || $email_val == 2 || $pass_val == 2 || $ph_val == 2 || $zipcode_val == 2 ) {
			$flag = 2;
		}

		if( strlen($email_exists) > 0 ) {
			$flag=2;
			EFCommon::$smarty->assign("user_create_email", "Email has been already registered once in the system.");
		}

		if( strlen($pass) < 6 ) {
			$flag=2;
			EFCommon::$smarty->assign("user_create_pass","Please enter a password of atleast 6 characters in length");
		}
		
		// Flag = 2, error = true, else, error = false
		return ( $flag == 2 ) ? true : false;
	}

	// BEGIN OLD FUNCTIONS
	//////////////////////
	public function validateSaveEmail($req, $isText = false) {
		$msg="<br>";
		$flag=0;
		$dt=$req['date'];
		$a_date = explode('/', $dt);
		$month = $a_date[0];
		$day = $a_date[1];
		$year = $a_date[2]; 
		if(!@checkdate($month,$day,$year)) {
			$msg.="Please enter a date in mm/dd/yyyy format. <br>";
			$flag=1;
		}
		
		// Make sure date is in the future
		$check = @mktime(0, 0, 0, $month, $day, $year,-1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"), -1);
		if( $check < $today ) {
			$msg.="Date must be in the future<br>";
			$flag=1;
		}

		if ($isText === false) {
			$res=filter_var($req['subject'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9]*$/")));
			if (!($res)) {
				$flag=1;
				$msg.="Subject can only contain characters A-Z or numbers 0-9 <br>";
			}
		}

		$res=filter_var($req['content'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z 0-9']*$/")));
		if(!($res)) {
			$flag=1;
			$msg.="Content can only contain characters A-Z or numbers 0-9 <br>";
		}

		if($flag==0) {
			$msg="Success";
		}

		return $msg;
	}

	public function valEmail($email,$tmp_var,$msg) {
		$flag=1;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			EFCommon::$smarty->assign($tmp_var,$msg);
			$flag=2;
		}
		return $flag;
	}

	public function valUsingRegExp($val,$regex,$tmp_var,$msg) {
		$flag=1;
		$res=filter_var($val, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>$regex)));
		if(!($res)) {
			EFCommon::$smarty->assign($tmp_var,$msg);
			$flag=2;
		}
		return $flag;
	}

	////////////////
	// End OLD FUNCTIONS

	// Need to add for Facebook Picture
	private function getUserImage($userId) {
		if ( file_exists("upload/user/" . $userId . ".png") ) {
			return CURHOST . "/upload/user/" . $userId . ".png";
		} else if ( file_exists("upload/user/" . $userId . ".jpg") ) {
			return CURHOST . "/upload/user/" . $userId . ".jpg";
		} else {
			return CURHOST . "/images/default_thumb.jpg";
		}
	}
	
	private function checkHome() {
		if (isset($_SESSION['uid'])) {
			// if there's new event when login using facebook
			$this->checkCreateEventSession();
	
			unset($_SESSION['new_eid']);
			unset($_SESSION['manage_event']);
			$this->assignCPEvents($_SESSION['uid']);
			EFCommon::$smarty->assign('userImage', $this->getUserImage($_SESSION['uid']));
			EFCommon::$smarty->display('cp.tpl');
		} else {
			EFCommon::$smarty->display('index.tpl');
		}
	}

	private function assignCPEvents($uid) {
		// EFCommon::$smarty->assign('maxEventId', EFCommon::$dbCon->getMaxEventId());
		if ( ! $this->assignUserProfile($uid) )
			return false;
		
		$this->assignAttendingEvents($uid);
		$this->assignCreatedEvents($uid);
		
		return true;
	}

	private function assignUserProfile($uid) {
		$userInfo = EFCommon::$dbCon->getUserInfo($uid);
		if ( ! $userInfo ) {
			return false;
		}
								  
		$userInfo['pic'] = $this->getUserImage($uid);
		EFCommon::$smarty->assign('userInfo', $userInfo);
		
		$paypalEmail = EFCommon::$dbCon->getPaypalEmail($uid);
		EFCommon::$smarty->assign('paypalEmail', $paypalEmail['pemail']);
		return true;
	}

	private function assignAttendingEvents($uid) {
		$attendingEvents = EFCommon::$dbCon->getEventAttendingBy($uid);
		if ( sizeof($attendingEvents) == 0 )
			EFCommon::$smarty->assign('attendingExists', false);
		else 
			EFCommon::$smarty->assign('attendingEvents', $attendingEvents);
	}

	private function assignCreatedEvents($uid) {
		$createdEvents = EFCommon::$dbCon->getEventByEO($uid);
		if ( sizeof($createdEvents) == 0 )
			EFCommon::$smarty->assign('createdExists', false );
		else
			EFCommon::$smarty->assign('createdEvents', $createdEvents);
	}


	/* Depreciated
	 public function displayAttendeePage($eventId) {
		require_once('models/EFCore.class.php');
		$efCore = new EFCore();

		$eventAttendees = EFCommon::$dbCon->getAttendeesByEvent($eventId);
		$eventInfo = EFCommon::$dbCon->getEventInfo($eventId);

		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}

		EFCommon::$smarty->assign('trsvpVal', $efCore->computeTrueRSVP($eventId));
		EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
		EFCommon::$smarty->assign('eventInfo', $eventInfo);
		EFCommon::$smarty->display('manage_event_on.tpl');
	} */
	
	public function assignManageVars($eventId) {
		$efCore = new EFCore();

		$numGuestConf1 = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFOPT1);
		$numGuestConf2 = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFOPT2);
		$numGuestConf3 = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFOPT3);
		$numGuestConf4 = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFOPT4);
		$numGuestConf5 = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFOPT5);
		$numGuestConf6 = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFOPT6);
		$numGuestNoResp = EFCommon::$dbCon->getNumAttendeesByConfidence($eventId, CONFELSE);

		EFCommon::$smarty->assign('guestConf1', $numGuestConf1['guest_num']);
		EFCommon::$smarty->assign('guestConf2', $numGuestConf2['guest_num']);
		EFCommon::$smarty->assign('guestConf3', $numGuestConf3['guest_num']);
		EFCommon::$smarty->assign('guestConf4', $numGuestConf4['guest_num']);
		EFCommon::$smarty->assign('guestConf5', $numGuestConf5['guest_num']);
		EFCommon::$smarty->assign('guestConf6', $numGuestConf6['guest_num']);
		EFCommon::$smarty->assign('guestNoResp', $numGuestNoResp['guest_num']);
		
		EFCommon::$smarty->assign('guestimate', $efCore->computeGuestimate($eventId));
		EFCommon::$smarty->assign('trsvpVal', $efCore->computeTrueRSVP($eventId));
	}

	/* function getEventIdByUri
	 * Gets the Event ID given a URI, returns
	 * false if invalid URI
	 *
	 * @param requestUri | The URI of the page to be displayed
	 * @return eventId | The event ID
	 * @return false | If invalid URI
	 */
	public function getEventIdByUri( $requestUri ) {
		$eventId = explode('/', $requestUri);
		
		// Verify that format of URL is http://{$CURHOST}/event/{$eventId}
		if (sizeof($eventId) != 3 ) {
			return false;
		}
		
		$eventId = $eventId[sizeof($eventId) - 1];
		
		return $eventId;
	}
	
	private function getUserIdByUri( $requestUri ) {
		$userId = explode('/', $requestUri);		

		// Verify that format of URL is http://{$CURHOST}/user/{$userId}
		if (sizeof($userId) != 3 ) {
			return false;
		}
		
		$userId = $userId[sizeof($userId)-1];
		// $userId = explode('?', $userId);
		// $userId = $eventId[0];
		
		return $userId;
	}
	
	// Check if there's a new event session
	// create that event if the session exist
	private function checkCreateEventSession() {
		if (isset($_SESSION['newEvent'])) {
			$newEvent = unserialize($_SESSION['newEvent']);
			$newEvent->organizer = $_SESSION['uid'];
			$this->makeNewEvent( $newEvent );
		}
	}
	
	private function getRedirectUrl() {
		if (isset($_SESSION['ref'])) {
			$inviteReference = EFCommon::$dbCon->getInviteReference($_SESSION['ref'], $_POST['email']);
			$url = CURHOST."/event/".$inviteReference['event_id'];
			unset($_SESSION['ref']);
		} else {	
			switch ($_GET['redirect']) {
				case 'event':
					if ( $_GET['eventId'] ) {
						$url = CURHOST . "/event/" . $_GET['eventId'];
					}
					break;
				case 'manage':
					if ( $_GET['eventId'] ) {
						$url = CURHOST . "/event/manage?eventId=" . $_GET['eventId'];
					}
					break;
				default:
					$url = CURHOST;
			}
		}
		
		return $url;
	}
	
	/**
	 * Make sure that the request is coming from 
	 * the HOST not from the client
	 */
	private function validateLocalRequest() {
		if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']) {
			//header("Location: " . CURHOST);
		}
	}
	
	/**
	 * Make sure the user logged in
	 */
	private function validateUserLogin() {
		if (!isset($_SESSION['uid'])) {
			//header("Location: " . CURHOST);
		}
	}
	
	/* function getView
	 * Determines which template files to display
	 * given a certain parameter.
	 *
	 * @param $requestUri | The URI of the current page
	 */
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		
		// If mail invite reference, save in Session
		if (isset($_REQUEST['ref'])) {
			$_SESSION['ref'] = $_REQUEST['ref'];
		}
	
		// If /event in URI, display all event pages
		if (preg_match("/event\/\d+/", $requestUri) > 0) {
			$twitter = new EFTwitter();
			$twitterHash = $twitter->getTwitterHash($eventId);
			EFCommon::$smarty->assign( 'twitterHash', $twitterHash );
			
			// Get a valid eventId
			$eventId = $this->getEventIdByUri( $requestUri );
			if ( ! $eventId ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			
			EFCommon::$smarty->assign( 'eventId', $eventId );
			
			// Fetch event information from the database
			$eventInfo = EFCommon::$dbCon->getEventInfo($eventId);
			if ( ! $eventInfo ) {
				if (isset($_SESSION['ref'])) {
					header("Location: ".CURHOST."/login?ref=".$_SESSION['ref']);
				}
				EFCommon::$smarty->display( 'error_event_notexist.tpl' );
				return;
			}
			EFCommon::$smarty->assign('eventInfo', $eventInfo); 
			 
			$organizerId = $eventInfo['organizer'];
			$organizerInfo = EFCommon::$dbCon->getUserInfo($organizerId);
			$organizerInfo['pic'] = $this->getUserImage($eventInfo['organizer']);
			EFCommon::$smarty->assign( 'organizer', $organizerInfo );
			
			$curSignUp = EFCommon::$dbCon->getCurSignup($eventId);
			EFCommon::$smarty->assign( 'curSignUp', $curSignUp );
			
			$attending = EFCommon::$dbCon->getAttendeesByEvent($eventId);
			foreach( $attending as &$user ) {
				$user['pic'] = $this->getUserImage($user['id']);
			}
			EFCommon::$smarty->assign( 'attending', $attending );
			
			// Make sure user is allowed to view the event
			if ( intval($eventInfo['is_public']) == 1 || 
				 ( isset( $_SESSION['uid'] ) &&
				     // if the user is invited to the event 
				   ( EFCommon::$dbCon->isInvited( $_SESSION['uid'], $eventId ) || 
					   // if the user is a host
						 $_SESSION['uid'] == $eventInfo['organizer'] ) ) ) {
				
				if (isset($_SESSION['uid'])) {
					// See if the user has responded
					$hasAttend = EFCommon::$dbCon->hasAttend($_SESSION['uid'], $eventId);
					
					EFCommon::$smarty->assign('conf' . $hasAttend['confidence'],  ' checked="checked"');
					EFCommon::$smarty->assign('select' . $hasAttend['confidence'], ' class="selected"');
				}

				if ( ! isset( $_SESSION['uid'] ) ) {
					EFCommon::$smarty->assign('disabled', ' disabled="disabled"');
				}
				
				EFCommon::$smarty->assign('redirect', "?redirect=event&eventId=" . $eventId);
				EFCommon::$smarty->display('event.tpl');
			} else {
				if ( ! isset( $_SESSION['uid'] ) ) {
					header("Location: " . CURHOST . "/login?redirect=event&eventId=" . $eventId);
				} else {
					EFCommon::$smarty->display('error_event_private.tpl');
				}
			}
			return;
		} // END /event
		
		// Quick check for permissions for editing events
		if ( preg_match("/event\/manage*/", $requestUri) > 0 ) {
			if ( ! isset ( $_GET['eventId'] ) ) {
				EFCommon::$smarty->display('error.tpl');
				return;
			}
			$eventId = $_GET['eventId'];
			if ( ! isset ( $_SESSION['uid'] ) ) {
				if ( $eventId ) {
					header("Location: " . CURHOST . "/login?redirect=manage&eventId=" . $eid);
				} else {
					header("Location: " . CURHOST . "/login");
				}
				exit;
			} 
		}
		
		// User public profile page
		if (preg_match("/user\/\d+/", $requestUri) > 0) {
			$userId = $this->getUserIdByUri( $requestUri );	
			$validUser = $this->assignCPEvents($userId);
			
			if ( ! $validUser )
				EFCommon::$smarty->display('error_user_notexist.tpl');
			else
				EFCommon::$smarty->display('profile.tpl');
			
			return;
		}
		
		// Remove GET parameters
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$current_page = substr($requestUri, 0, $getParamStartPos);
			$params = substr($requestUri, $getParamStartPos, strlen($requestUri) - 1 );
		} else {
			$current_page = $requestUri;
		}

		switch ($current_page) {
			case '/':
				$this->checkHome();
				break;
			case '/contact':
				EFCommon::$smarty->display('contact.tpl');
				break;
			case '/share':
				EFCommon::$smarty->display('share.tpl');
				break;
			case '/method':
				EFCommon::$smarty->display('method.tpl');
				break;
			case '/settings':
				if (isset($_POST['submit'])) {
					$responseMsg = array();
					$user = new User(NULL);
					if ( $this->validateEventInfo( $user ) === true ) {
						EFCommon::$dbCon->updateUserInfo($user->fname, 
																				 $user->lname, 
																				 $user->email, 
																				 $user->phone, 
																				 $user->zip, 
																				 $user->twitter,
																				 $user->notif_opt1, 
																				 $user->notif_opt2, 
																				 $user->notif_opt3);
						$responseMsg['user_success'] = 'User info has been updated';
					} 
					
					if ( $_REQUEST['user-curpass'] != '' || $_REQUEST['user-newpass'] != '' || $_REQUEST['user-confpass'] != '' ) {
						if ( EFCommon::$dbCon->resetPassword( md5($_REQUEST['user-curpass']), 
																						  md5($_REQUEST['user-newpass']), 
																							md5($_REQUEST['user-confpass']) )) {
							$responseMsg['password'] = 'Password has been updated';
						} else {
							$responseMsg['password'] = 'Invalid old password, not updated';
						}
					}
					
					EFCommon::$smarty->assign('responseMsg', $responseMsg);
				}
				
				
				if (isset($_SESSION['uid'])) {
					$userInfo = EFCommon::$dbCon->getUserInfo($_SESSION['uid']);
					$userInfo['pic'] = $this->getUserImage($_SESSION['uid']);
					
					EFCommon::$smarty->assign('userInfo', $userInfo);
					EFCommon::$smarty->display('settings.tpl');
				} else {
					header("Location: " . CURHOST);
				}
				break;
			case '/event/create':
				$this->validateUserLogin();
				//
				// $eventInfo->time = date("H:i:s", strtotime($_REQUEST['time']));
				// Needs to be implemented
				//
				
				// Check to see if the user has submit the form yet
				if ( isset($_POST['submit']) ) {
					// Create an event object with the text from the form
					$newEvent = new Event(NULL);
				// See if it's their first time on the field
				} else if ( ! isset($_SESSION['newEvent']) ) {
					if (isset($_POST['title'])) {
						$event_field['title'] = $_POST['title'];
					}
					if (isset($_POST['goal'])) {
						$event_field['goal'] = $_POST['goal'];
					}
				
					EFCommon::$smarty->assign('event_field', $event_field);
					EFCommon::$smarty->assign('step1', ' class="current"');
					EFCommon::$smarty->display('create.tpl');
					break;
				// Check to see if they were working on the event before
				} else {
					$newEvent = unserialize($_SESSION['newEvent']);
				}

				// Check to see if the new event is valid.
				if ( $this->validateEventInfo( $newEvent ) === false ) {
					// Save the current information for the next visit
					$_SESSION['newEvent'] = serialize($newEvent);
          
					// Prepare the current values for display on the template
					$this->saveEventFields( $newEvent );
                    
					EFCommon::$smarty->assign('step1', ' class="current"');
					EFCommon::$smarty->display('create.tpl');
				} else {
					$this->makeNewEvent( $newEvent );
				}
				break;
			case '/create/guests':
				$this->validateUserLogin();
				$mailer = new EFMail();
				EFCommon::$smarty->assign('step2', ' class="current"');
				
				$event = $this->buildEvent($_SESSION['new_eid']);
				
				if (isset($_POST['submit'])) {
					$event = $this->buildEvent($_SESSION['new_eid']);
					$event->submitGuests();
					header("Location: " . CURHOST . "/event/manage?eventId=".$_SESSION['new_eid']);
					exit;
				}
				
				EFCommon::$smarty->assign('eventInfo', $event);
				EFCommon::$smarty->assign('submitTo', '/create/guests');
				EFCommon::$smarty->display('create.tpl');
				break;
			case '/event/image/upload':
				$this->validateLocalRequest();
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
				$this->validateLocalRequest();
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
				$this->validateLocalRequest();
				$_SESSION['attend_event'] = EFCommon::$dbCon->getEventInfo($_POST['eid']);
				EFCommon::$dbCon->eventSignUp($_SESSION['uid'], $_POST['eid'], $_POST['conf']);
				break;
      case '/event/checkin':
				$isAttend = 1;
				if ($_REQUEST['checkin'] == 'false') {
					$isAttend = 0;
				}
				EFCommon::$dbCon->checkInGuest( $isAttend, $_REQUEST['guestId'], $_REQUEST['eventId'] );
				break;
			case '/event/print':
				$this->validateUserLogin();
				$this->displayAttendeePage( $_REQUEST['eventId'] );
				break;
			case '/event/manage':
				$this->validateUserLogin();
				$page['manage'] = ' class="current"';
				EFCommon::$smarty->assign('page', $page);
				
				$event = $this->buildEvent( $_GET['eventId'] );
				
				$eventAttendees = EFCommon::$dbCon->getAttendeesByEvent($_GET['eventId']);
				for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
					if ($eventAttendees[$i]['is_attending'] == 1) {
						$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
					}
				}
				
				EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
				
				$this->assignManageVars( $_GET['eventId'] );
				
				EFCommon::$smarty->display('manage.tpl');
				break;
			case '/event/manage/edit':
				$this->validateUserLogin();
				$page['edit'] = ' class="current"';
				EFCommon::$smarty->assign('page', $page);
				
				if ( ! isset ($_POST['submit']) ) {
					// $eventInfo = EFCommon::$dbCon->getEventInfo($_GET['eventId']);
					$editEvent = $this->buildEvent($_GET['eventId']);
					$this->saveEventFields( $editEvent );
					EFCommon::$smarty->display('manage_edit.tpl');
					break;
				}
				
				// Fill in event information
				$editEvent = new Event(NULL);
				$editEvent->eid = $_GET['eventId'];
				
				// Check to see if the new event information is valid.
				if ( $this->validateEventInfo( $editEvent ) === true ) {
					$this->saveEvent( $editEvent );
				}
				
				$this->saveEventFields( $editEvent );
				EFCommon::$smarty->display('manage_edit.tpl');
                
				break;
			case '/event/manage/guests':
				$this->validateUserLogin();
				$page['manage'] = ' class="current"';
				$page['addguests'] = ' class="current"';
				EFCommon::$smarty->assign('page', $page);
				
				$event = unserialize($_SESSION['manage_event']);
				if ($_POST['submit']) {
					$event->submitGuests();
				}
				
				EFCommon::$smarty->assign('submitTo', '/event/manage/guests?eventId='.$event->eid);
				EFCommon::$smarty->display('manage_guests.tpl');
				break;
			case '/guest/inviter':
				$this->validateLocalRequest();
				$inviter = new OpenInviter();
				$oi_services = $inviter->getPlugins();

				if ( isset( $_REQUEST['oi_email'] ) && isset( $_REQUEST['oi_pass'] ) ) {
					$inviter->startPlugin($_REQUEST['oi_provider']);
					$internal = $inviter->getInternalError();
					if ( $internal && $this->DEBUG ) {
						print($internal);
					}
					$inviter->login( $_REQUEST['oi_email'], $_REQUEST['oi_pass'] );

					$_POST['oi_session_id'] = $inviter->plugin->getSessionID();
					$contactList = $inviter->getMyContacts();

					EFCommon::$smarty->assign('contactList', $contactList);
					EFCommon::$smarty->display('event_add_guest_import_contact_list.tpl');
				} else {
					EFCommon::$smarty->assign('provider', $_REQUEST['provider']);
					EFCommon::$smarty->display('event_add_guest_right.tpl');
				}
				break;
			case '/event/manage/guests/save':
				$this->validateLocalRequest();
				$event = $this->buildEvent($_GET['eventId']);

				$mailer = new EFMail();
				EFCommon::$dbCon->storeGuests($event->guests, $_REQUEST['eventId'], $_SESSION['uid']);
				break;
			case '/event/manage/email':
				$this->validateUserLogin();
				$page['manage'] = ' class="current"';
				$page['email'] = ' class="current"';
				EFCommon::$smarty->assign('page', $page);

				$event = $this->buildEvent( $_GET['eventId'] );
				
				$eventReminder = EFCommon::$dbCon->getEventEmail($event->eid, EMAIL_REMINDER_TYPE);
				if ( $eventReminder['is_activated'] == 1 ) {
					$eventReminder['isAuto'] = 'checked = "checked"';
				}
				
				$eventDatetime = explode(" ", $eventReminder['datetime']);
				$eventDate = $eventDatetime[0];
				$eventTime = explode(":", $eventDatetime[1]);
				
				if ($eventTime[0] != "" && $eventTime[1] != "") {
					$eventTime = $eventTime[0].":".$eventTime[1]." ".$eventDatetime[2];
				} else {
					$eventTime = "";
				}
				
				EFCommon::$smarty->assign('eventDate', $eventDate);
				EFCommon::$smarty->assign('eventTime', $eventTime);
	
				EFCommon::$smarty->assign('eventTimeMid', $eventTimeMid);
				
				EFCommon::$smarty->assign('eventReminder', $eventReminder);
				EFCommon::$smarty->display('manage_email.tpl');
				break;
			case '/event/email/save':
				$this->validateLocalRequest();
				$event = unserialize($_SESSION['manage_event']);
				
				$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}

				$req['content'] = $_REQUEST['reminderContent'];
				$req['subject'] = $_REQUEST['reminderSubject'];
				$req['date'] = $_REQUEST['reminderDate'];
				$retval = $this->validateSaveEmail($req);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				EFCommon::$dbCon->saveEmail( $event->eid, 
																	$_REQUEST['reminderContent'], 
																	$dateTime, 
																	$_REQUEST['reminderSubject'], 
																	EMAIL_REMINDER_TYPE, 
																	$autoReminder);
				echo("Success");
				break;
			case '/event/email/send':
				$this->validateLocalRequest();
				$mailer = new EFMail();

				$event = unserialize($_SESSION['manage_event']);
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($event->eid);
				
				$req['content'] = $_REQUEST['reminderContent'];
				$req['subject'] = $_REQUEST['reminderSubject'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				$retval = $this->validateSaveEmail($req);
					if( $retval != "Success" ) {
					die($retval);
				}
				
				$mailer->sendAutomatedEmail($event, 
																		$_REQUEST['reminderContent'], 
																		$_REQUEST['reminderSubject'], 
																		$attendees);
				echo("Success");
				break;
			case '/event/email/autosend':
				$this->validateLocalRequest();
				$event = unserialize($_SESSION['manage_event']);
				
				$isActivated = 0;
				if ($_REQUEST['autoSend'] == 'true') {
					$isActivated = 1;
				}

				EFCommon::$dbCon->setAutosend($event->eid, EMAIL_REMINDER_TYPE, $isActivated);
				break;
			case '/event/text/autosend':
				$this->validateLocalRequest();
				$event = unserialize($_SESSION['manage_event']);
				
				$isActivated = 0;
				if ($_REQUEST['autoSend'] == 'true') {
					$isActivated = 1;
				}

				EFCommon::$dbCon->setAutosend($event->eid, SMS_REMINDER_TYPE, $isActivated);
				break;
			case '/event/manage/text':
				$this->validateUserLogin();
				$page['manage'] = ' class="current"';
				$page['text'] = ' class="current"';
				EFCommon::$smarty->assign('page', $page);
				
				$event = $this->buildEvent( $_GET['eventId'] );
				
				$eventReminder = EFCommon::$dbCon->getEventEmail($event->eid, SMS_REMINDER_TYPE);
				if ( $eventReminder['is_activated'] == 1 ) {
					$eventReminder['isAuto'] = 'checked = "checked"';
				}
				
				$eventDatetime = explode(" ", $eventReminder['datetime']);
				$eventDate = $eventDatetime[0];
				$eventTime = explode(":", $eventDatetime[1]);
				
				if ($eventTime[0] != "" && $eventTime[1] != "") {
					$eventTime = $eventTime[0].":".$eventTime[1]." ".$eventDatetime[2];
				} else {
					$eventTime = "";
				}
				
				EFCommon::$smarty->assign('eventDate', $eventDate);
				EFCommon::$smarty->assign('eventTime', $eventTime);
	
				EFCommon::$smarty->assign('eventTimeMid', $eventTimeMid);
				
				EFCommon::$smarty->assign('eventReminder', $eventReminder);
				EFCommon::$smarty->display('manage_text.tpl');
				break;
			case '/event/text/send':
				$this->validateLocalRequest();
				$mailer = new EFMail();
				$sms = new EFSMS();
				
				$event = unserialize($_SESSION['manage_event']);
				
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($event->eid);
				
				
				$req['content'] = $_REQUEST['reminderContent'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				$retval = $this->validateSaveEmail($req, true);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				$sms->sendSMSReminder($attendees, $event->eid, $mailer->mapText($_REQUEST['reminderContent'], $event->eid));
				print("Success");
				break;
			case '/event/text/save':
				$this->validateLocalRequest();
				$event = unserialize($_SESSION['manage_event']);
			
				$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				$req['content'] = $_REQUEST['reminderContent'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				$retval = $this->validateSaveEmail($req, true);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				EFCommon::$dbCon->saveText($event->eid, 
															 $_REQUEST['reminderContent'], 
															 $dateTime, 
															 SMS_REMINDER_TYPE, 
															 $autoReminder);
				echo("Success");
				break;
			case '/fb/user/update':
				$this->validateLocalRequest();
				EFCommon::$dbCon->facebookAdd($_REQUEST['fbid']);
				break;
			case '/login':
				// if the user already logged in
				if ( ! isset($_SESSION['uid']) ) {
					EFCommon::$smarty->assign('redirect', $params);
					
					if ( isset($_POST['isFB']) ) {
						$userInfo = EFCommon::$dbCon->facebookConnect( $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['fbid'] );
						if ( $userInfo ) {
							$_SESSION['uid'] = $userInfo['id'];
							if ( isset ($params) ) {
								echo $params;
							} else {
								echo 1;
							}
						} else {
							echo 0;
						}
						
						break;
					// if the user submit the login form
					} else if ( isset( $_POST['login'] ) ) {
						if ( ! isset( $_POST['email'] ) ) {
							// User didn't enter e-mail
							EFCommon::$smarty->assign('user_login_email', "Please enter an e-mail");
							EFCommon::$smarty->display("login.tpl");
							break;
						}
						if ( ! isset( $_POST['pass'] ) ) {
							// User didn't enter password
							EFCommon::$smarty->assign('user_login_password', "Please enter a password");
							EFCommon::$smarty>display("login.tpl");
							break;
						} 
						
						$userId = EFCommon::$dbCon->checkValidUser( $_POST['email'], $_POST['pass'] );
						
						if ( ! isset( $userId ) ) {
							// Invalid e-mail/password combination
							EFCommon::$smarty->assign('user_login_email', "Invalid e-mail or password");
							EFCommon::$smarty->display("login.tpl");
							break;
						}
						
						$_SESSION['uid'] = $userId;
						// if there's new event session when logging in
						// $this->checkCreateEventSession();
						
						// Success, log in
						header("Location: " . $this->getRedirectUrl());
						exit;
					
					/* User used trueRSVP register */
					} else if ( isset ( $_POST['register'] ) ) {
						$req['fname'] = $_POST['fname'];
						$req['lname'] = $_POST['lname'];
						$req['email'] = $_POST['email'];
						$req['phone'] = $_POST['phone'];
						$req['pass'] = $_POST['pass'];
						$req['zip'] = $_POST['zipcode'];
						$errors = $this->checkUserCreationForm($req);
						
						// Check if any errors
						if( $errors ) {
							EFCommon::$smarty->display('login.tpl');
							break;
						}
						
						// Create the new user
						$userInfo = EFCommon::$dbCon->createNewUser( $_POST['fname'], 
																										 $_POST['lname'], 
																										 $_POST['email'], 
																										 $_POST['phone'], 
																										 md5($_POST['pass']), 
																										 $_POST['zipcode'] );
						// Assign user's SESSION variables
						$_SESSION['uid'] = $userInfo['id'];
						// if there's new event session when registering
						// $this->checkCreateEventSession();
						
						// $_SESSION['userProfilePic'] = "images/default_thumb.jpg";
					} else {
						EFCommon::$smarty->display('login.tpl');
						break;
					}
				}
				
				if ( isset ( $params ) ) {
					header("Location: " . $this->getRedirectUrl());
					exit;
				}
				
				// Logged in user doesn't need to log in!
				header("Location: " . CURHOST);
				exit;
			case '/login/reset':
				if (EFCommon::$dbCon->isValidPassResetRequest($_REQUEST['ref'])) {
					EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
					EFCommon::$smarty->display('login_reset.tpl');
				} else {
					EFCommon::$smarty->display('login_reset_invalid.tpl');
				}
				break;
			case '/login/reset/submit':
				if ($_REQUEST['login_forgot_newpass'] == $_REQUEST['login_forgot_newpass_conf']) {
					EFCommon::$dbCon->resetPasswordByEmail($_REQUEST['login_forgot_newpass'], $_REQUEST['login_forgot_ref']);
					EFCommon::$smarty->display('login_reset_confirmed.tpl');
				} else {
					EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
					EFCommon::$smarty->assign('errorMsg', 'New password is not confirmed');
					EFCommon::$smarty->display('login_reset.tpl');
				}
				break;
			case '/login/forgot':
				EFCommon::$smarty->display('login_forgot.tpl');
				break;
			case '/login/forgot/submit':
				$mailer = new EFMail();

				$hash_key = md5(time().$_REQUEST['login_forgot_email']);

				if (EFCommon::$dbCon->requestPasswordReset($hash_key, $_REQUEST['login_forgot_email'])) {
					$mailer->sendResetPassLink('/login/reset', $hash_key, $_REQUEST['login_forgot_email']);
					EFCommon::$smarty->display('login_forgot_confirmed.tpl');
				} else {
					EFCommon::$smarty->display('login_forgot_invalid.tpl');
				}
				break;
			case '/user/image/upload':
				$this->validateLocalRequest();
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");

				// max file size in bytes
				$sizeLimit = 2 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/user/', TRUE);
				// to pass data through iframe you 
				// will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

				EFCommon::$dbCon->saveUserPic();
				break;
			case '/user/status/update':
				$this->validateLocalRequest();
				EFCommon::$dbCon->updateUserStatus($_REQUEST['value']);
				echo($_REQUEST['value']);	
				break;
			case '/user/profile/update':
				$this->validateLocalRequest();
				EFCommon::$dbCon->updatePaypalEmail($_SESSION['uid'], $_REQUEST['paypal_email']);
				$this->assignUserProfile($_SESSION['uid']);

				EFCommon::$smarty->display('user_profile.tpl');
				break;
			case '/user/profile-dtls/update':
				$this->validateLocalRequest();
				$email = $_POST['email'];
				$zip = $_POST['zip'];
				$cell = $_POST['cell'];
				$res = "";
				if( ! filter_var($email, FILTER_VALIDATE_EMAIL) )
					$res = $res."1,";
				else
					$res = $res."0,";
				if( ! (filter_var($zip, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^\d{5}(-\d{4})?$/")))) )
					$res = $res."1,";
				else
					$res=$res."0,";
				if( !(filter_var($cell, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/")))) )
					$res = $res."1,";
				else
					$res = $res."0,";

				if( $res == "0,0,0," ) {
					EFCommon::$dbCon->updateUserProfileDtls($email,$zip,$cell);
					echo $res;
				} else {
					echo $res;
				}
				break;
			case '/event/payment/submit':
				require_once('models/PaypalPreapproveReceipt.class.php');

				$paypalPreapprove = new PaypalPreapproveReceipt();
				$paypalPreapprove->preapprove();
				break;
			case '/event/payment/success':
				require_once('models/PaypalPreapproveDetails.class.php');
				
				if ( EFCommon::$dbCon->eventSignUp($_SESSION['uid'], $_SESSION['attend_event']['id'] && isset($_SESSION['uid']) ) ) {
					$paypalPreapprove = new PaypalPreapproveDetails();
					$paypalPreapprove->preapprove();
					EFCommon::$dbCon->preapprovePayment(
						$_SESSION['uid'],
						$_SESSION['attend_event']['id'], 
						$paypalPreapprove->preapprovalKey, 
						$paypalPreapprove->response->senderEmail
					);

				  $userInfo = EFCommon::$dbCon->getUserInfo($_SESSION['uid']);
					EFCommon::$smarty->assign('userInfo', $userInfo);

					EFCommon::$smarty->display('payment_success.tpl');
					break;
				}
				EFCommon::$smarty->display('payment_failed.tpl');
				break;
			case '/event/payment/failed':
				EFCommon::$smarty->display('payment_failed.tpl');
				break;
			case '/payment/collect':
				require_once('models/PaypalPayReceipt.class.php');
				$paypalPay = new PaypalPayReceipt();

				$attendees = EFCommon::$dbCon->getAttendees($_REQUEST['eventId']);

				// TODO: NON-ATOMIC OPERATION
				// PayPal doesn't provide an API to receive payments from multiple senders
				// But it provides an API to send payments to multiple receivers
				// https://www.x.com/thread/52330?stqc=true
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$paypalPay->pay($attendees[$i]['pemail'], $_REQUEST['receiver_email'], 
													$attendees[$i]['cost'], $attendees[$i]['pkey']);
				}
				EFCommon::$dbCon->updateCollected($_REQUEST['eventId']);
				$this->assignCreatedEvents($_SESSION['uid']);
				EFCommon::$smarty->display('event_created.tpl');
				break;
			case '/logout':
				if ( ! isset($_SESSION['uid']) ) {
					EFCommon::$smarty->display('error.tpl');
					break;
				}
				session_unset();
				session_destroy();
				EFCommon::$smarty->display('index.tpl');
				break;
			default:
				EFCommon::$smarty->assign('current_page', $current_page);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}
