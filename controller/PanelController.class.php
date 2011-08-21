<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 TrueRSVP Inc. 
 * All rights reserved
 */

class PanelController {
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
	private function buildEvent($eventId, $manage = false ) {
		
		if ( $manage ) {
			if ( isset($_SESSION['manage_event']) ) {				
				if ( $_SESSION['manage_event']->eid == $eventId ) {
					return $_SESSION['manage_event'];
				} else {
					unset( $_SESSION['manage_event'] );
				}
			}
		}
		
		$event = new Event($eventId);
		
		if($manage) {
			$_SESSION['manage_event'] = $event;
		}
		
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
	
	private function getAttendees($eventId) {
		$user_array = EFCommon::$dbCon->getAttendeesByEvent($eventId);
		foreach($user_array as $userInfo) {
			$attendees[] = new User($userInfo);
		}
		return $attendees;
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
		$event_field = $newEvent->get_array();

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
		if ( ! isset($_SESSION['user']) ) {
			$_SESSION['newEvent'] = $newEvent;
			header("Location: " . CURHOST . "/login?redirect=create");
			exit;
		}
		
		EFCommon::$dbCon->createNewEvent($newEvent);
		
		$_SESSION['new_eid'] = EFCommon::$dbCon->getMaxEventId();
		
		unset($_SESSION['newEvent']);
		header("Location: " . CURHOST . "/create/guests");
		exit;
	}

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

		if( $f_name_val == 2 || $l_name_val == 2 || $email_val == 2 || $pass_val == 2 || $ph_val == 2 || $zipcode_val == 2 ) {
			$flag = 2;
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
			$res=filter_var($req['subject'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9']*$/")));
			if (!($res)) {
				$flag=1;
				$msg.="Subject can only contain characters A-Z or numbers 0-9 <br>";
			}
		}

		$res=filter_var($req['content'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[\{A-Za-z 0-9'\}]*$/")));
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

	private function assignCPEvents($uid) {
		$this->assignCreatedEvents($uid);
		$this->assignAttendingEvents($uid);
	}
	
	private function assignCreatedEvents($uid) {
		$created_event = EFCommon::$dbCon->getEventByEO($uid);
		$createdEvents = NULL;
		foreach ( $created_event as $event ) {
			$createdEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('createdEvents', $createdEvents);
	}
	
	private function assignAttendingEvents($uid) {
		$attending_event = EFCommon::$dbCon->getEventAttendingByUid($uid);
		$attendingEvents = NULL;
		foreach( $attending_event as $event ) {
			$attendingEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('attendingEvents', $attendingEvents);
	}

	/* printEvent
	 * Used to print out an event
	 */
	public function printEvent() {
		require_once('models/EFCore.class.php');
		$efCore = new EFCore();
		
		$eventId = $_GET['eventId'];
		$event = new Event($eventId);
		
		$eventAttendees = EFCommon::$dbCon->getAttendeesByEvent($eventId);

		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}

		EFCommon::$smarty->assign('trsvpVal', $efCore->computeTrueRSVP($eventId));
		EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
		EFCommon::$smarty->assign('eventInfo', $eventInfo);
		EFCommon::$smarty->display('manage_event_on.tpl');
	}
	
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
		
		return $userId;
	}
	
	// Check if there's a new event session
	// create that event if the session exist
	/* Depreciated
	private function checkCreateEventSession() {
		if ( isset($_SESSION['newEvent']) ) {
			$newEvent = unserialize($_SESSION['newEvent']);
			// Verify that event is valid
			if ( ! $this->validateEventInfo($newEvent) ) {
				$_SESSION['newEvent'] = serialize($$newEvent);
				return false;
			}
			$newEvent->organizer = $_SESSION['user']->id;
			$this->makeNewEvent( $newEvent );
			return true;
		}
		return false;
	} */
	
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
	 // Depreciated?
	private function validateLocalRequest() {
		if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']) {
			//header("Location: " . CURHOST);
		}
	}
	
	/**
	 * Make sure the user logged in
	 */
	 // Depreciated?
	private function validateUserLogin() {
		if (!isset($_SESSION['user'])) {
			//header("Location: " . CURHOST);
		}
	}

	/*
	 * Redirect the user home if he's logged in
     */
	private function loggedInRedirect() {
		// if the user already logged in
		if ( isset($_SESSION['user']) ) {
			// Logged in user doesn't need to log in!
			header("Location: " . CURHOST);
			exit;
		}
	}
	
	/*
	 * The user has just logged in to FB,
	 * so let's take him to his profile page
	 */
	private function handleFBLogin() {
		$userInfo = EFCommon::$dbCon->facebookConnect( $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['fbid'] );
		if ( $userInfo ) {
			$_SESSION['user'] = new User($userInfo);
			if ( isset ($params) ) {
				echo $params;
			} else {
				echo 1;
			}
		} else {
			echo 0;
		}
	}
	/* function getView
	 * Determines which template files to display
	 * given a certain parameter.
	 *
	 * @param $requestUri | The URI of the current page
	 */
	public function getView($uri) {
		EFCommon::$smarty->assign("current_page", $uri);
		$requestUri = str_replace(PATH, '', $uri);
		
		// If mail invite reference, save in Session
		if (isset($_REQUEST['ref'])) {
			$_SESSION['ref'] = $_REQUEST['ref'];
		}
		
		// Remove GET parameters
		// We need to use $current_page instead of $requestUri
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$current_page = substr($requestUri, 0, $getParamStartPos);
			$params = substr($requestUri, $getParamStartPos, strlen($requestUri) - 1 );
		} else {
			$current_page = $requestUri;
		}
	
		// If /event in URI, display all event pages
		if (preg_match("/event\/\d+/", $current_page) > 0) {
			
			$eventId = $this->getEventIdByUri( $current_page );
			if ( ! $eventId ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$event = $this->buildEvent($eventId);
			EFCommon::$smarty->assign("event", $event);
			
			// Check to see if the event exists
			if ( ! $event->exists ) {
				EFCommon::$smarty->display( 'error_event_notexist.tpl' );
				return;
			}
			
			// Check permissions
			if ( ! $event->can_view(NULL) ) {
				EFCommon::$smarty->display( 'error_event_private.tpl' );
				return;
			}
		
			// Prepare the twitter feed
			$twitter = new EFTwitter();
			$twitterHash = $twitter->getTwitterHash($eventId);
			EFCommon::$smarty->assign( 'twitterHash', $twitterHash );
			
			
			// See if the user has responded
			if ( isset($_SESSION['user']->id) ) {
				$hasAttend = EFCommon::$dbCon->hasAttend($_SESSION['user']->id, $eventId);
				EFCommon::$smarty->assign('conf' . $hasAttend['confidence'],  ' checked="checked"');
				EFCommon::$smarty->assign('select' . $hasAttend['confidence'], 'true');
			} else {
				EFCommon::$smarty->assign('disabled', ' disabled="disabled"');
				EFCommon::$smarty->assign('redirect', "?redirect=event&eventId=" . $eventId);
			}
			
			$curSignUp = EFCommon::$dbCon->getCurSignup($eventId);
			EFCommon::$smarty->assign( 'curSignUp', $curSignUp );
			
			$event_attendees = EFCommon::$dbCon->getConfirmedGuests($eventId);
			$attending = NULL;
			foreach($event_attendees as $guest) {
				$attending[] = new User($guest);
			}
			EFCommon::$smarty->assign( 'attending', $attending );
			
			EFCommon::$smarty->display('event.tpl');
			return;
		} // END /event
		
		// Quick check for permissions for editing events
		if ( preg_match("/event\/manage*/", $current_page) > 0 ) {
			if ( ! isset ( $_GET['eventId'] ) ) {
				EFCommon::$smarty->display('error.tpl');
				return;
			}
			$eventId = $_GET['eventId'];
			if ( ! isset ( $_SESSION['user'] ) ) {
				if ( $eventId ) {
					header("Location: " . CURHOST . "/login?redirect=manage&eventId=" . $eid);
				} else {
					header("Location: " . CURHOST . "/login");
				}
				exit;
			} 
		}
		
		// User public profile page
		if (preg_match("/user\/\d+/", $current_page) > 0) {
			$userId = $this->getUserIdByUri( $current_page );
			$profile = new User($userId);
			if ( ! $profile->exists ) {
				EFCommon::$smarty->display('error_user_notexist.tpl');
			} else {
				$this->assignCPEvents($userId);
				EFCommon::$smarty->assign("profile", $profile);
				EFCommon::$smarty->assign("is_following", EFCommon::$dbCon->isFollowing($_SESSION['user']->id, $profile->id));
				EFCommon::$smarty->display('profile.tpl');
			}
			
			return;
		}

		switch ($current_page) {
			case '/':
				if (isset($_SESSION['user'])) {
					$page['cp'] = true;
					EFCommon::$smarty->assign('page', $page);
					// After logging in... fix this!
				
					// if there's new event when login using facebook
					// Need to make sure that event is valid before creating new event...
					// $this->checkCreateEventSession();
			
					
					unset($_SESSION['newEvent']);
					unset($_SESSION['new_eid']);
					unset($_SESSION['manage_event']);
					
					$this->assignCPEvents($_SESSION['user']->id);
					EFCommon::$smarty->display('cp.tpl');
				} else {
					EFCommon::$smarty->display('index.tpl');
				}
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
			case '/contacts':
				$page['contacts'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				// Please implement
				$contacts = array();
				$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
				for ($i = 0; $i < sizeof($contactList); ++$i) {
					$contact = new User($contactList[$i]);
					array_push($contacts, $contact);
				}
				
				EFCommon::$smarty->assign('contacts', $contacts);
				
				EFCommon::$smarty->display('cp_contacts.tpl');
				break;
			case '/contacts/add':
				$page['addcontacts'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				// No functions to add to address book
				if (isset($_POST['submit'])) {
					$_SESSION['user']->addContacts();
				}
				
				EFCommon::$smarty->assign('submitTo', '/contacts/add');
				EFCommon::$smarty->display('cp_contacts.tpl');
				break;
			case '/settings':
				$page['settings'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if ( isset($_POST['submit']) ) {
					$responseMsg = array();
					
					$user = new User(NULL);
					$user->id = $_SESSION['user']->id;
					
					$error = $user->get_errors();
					
					if ( ! $error ) {
						$user->updateDb();
						$responseMsg['user_success'] = "User settings updated successfully.";
					} else {
						EFCommon::$smarty->assign("error", $error);
					}
					
					if ( $_REQUEST['user-curpass'] != '' || $_REQUEST['user-newpass'] != '' || $_REQUEST['user-confpass'] != '' ) {
						if ( EFCommon::$dbCon->resetPassword( md5($_REQUEST['user-curpass']), md5($_REQUEST['user-newpass']), md5($_REQUEST['user-confpass']) )) {
							$responseMsg['password'] = 'Password has been updated';
						} else {
							$responseMsg['password'] = 'Invalid old password, not updated';
						}
					}
					EFCommon::$smarty->assign('responseMsg', $responseMsg);
				}
				
				
				if ( isset($_SESSION['user']) ) {
					EFCommon::$smarty->display('cp_settings.tpl');
				} else {
					header("Location: " . CURHOST);
				}
				break;
			case '/event/create':
				// $this->validateUserLogin();
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
					if (isset($_POST['title']) && $_POST['title'] != "name of event") {
						$event_field['title'] = $_POST['title'];
					}
					if (isset($_POST['goal']) && $_POST['goal'] != "goal") {
						$event_field['goal'] = $_POST['goal'];
					}
					
					if ( ! isset($event_field) )
						$event_field = NULL;
				
					EFCommon::$smarty->assign('event_field', $event_field);
					EFCommon::$smarty->assign('step1', ' class="current"');
					EFCommon::$smarty->display('create.tpl');
					break;
				// Check to see if they were working on the event before
				} else {
					$newEvent = $_SESSION['newEvent'];
				}

				
				// Check to see if the new event is valid.
				if ( $this->validateEventInfo( $newEvent ) === false ) {
				
					// Save the current information for the next visit
					$_SESSION['newEvent'] = $newEvent;
          
					// Prepare the current values for display on the template
					$this->saveEventFields( $newEvent );
                    
					EFCommon::$smarty->assign('step1', ' class="current"');
					EFCommon::$smarty->display('create.tpl');
				} else {
					$this->makeNewEvent( $newEvent );
				}
				break;
			case '/event/manage/cancel':
				if (EFcommon::$dbCon->deleteEvent($_GET['eventId'])) {
					print("Event is successfully deleted");
					// Add successful template for event cancellation
					break;
				}
				// Add an error template for the invalid host
				print("You are not the host for this event");
				break;
			case '/create/guests':
				$this->validateUserLogin();
				EFCommon::$mailer = new EFMail();
				EFCommon::$smarty->assign('step2', ' class="current"');
				// Store created event in a new session
				$event = $this->buildEvent($_SESSION['new_eid'], true);
				
				if (isset($_POST['submit'])) {
					$event->submitGuests();
					header("Location: " . CURHOST . "/event/manage?eventId=".$_SESSION['new_eid']);
					exit;
				}
				
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
			case '/user/csv/upload':
				$this->validateLocalRequest();
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("csv");

				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/user/csv/', TRUE);
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/user/follow':
				if ($_SESSION['user']->id != $_POST['fid']) {
					print(EFCommon::$dbCon->followUser($_SESSION['user']->id, $_POST['fid']));
				} else {
					print(0);
				}
				break;
			case '/event/attend':
				$this->validateLocalRequest();
				EFCommon::$dbCon->eventSignUp($_SESSION['user']->id, $this->buildEvent($_POST['eid']), $_POST['conf']);
				break;
			case '/event/checkin':
				$isAttend = 1;
				if ($_REQUEST['checkin'] == 'false') {
					$isAttend = 0;
				}
				EFCommon::$dbCon->checkInGuest( $isAttend, $_REQUEST['guestId'], $_REQUEST['eventId'] );
				break;
			case '/event/print':
				$this->printEvent();
				break;
			case '/event/manage':
				$this->validateUserLogin();
				$page['manage'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				$this->buildEvent( $_GET['eventId'], true );
								
				$this->assignManageVars( $_GET['eventId'] );
				
				EFCommon::$smarty->display('manage.tpl');
				break;
			case '/event/manage/attendees':
				$page['attendeelist'] = true;
				$page['manage'] = true;
				EFCommon::$smarty->assign('page', $page);
			
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($_GET['eventId']);
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$attendee = new User($attendees[$i]);
					
					if ($attendees[$i]['is_attending'] == 1) {
						// $attendee->checkedIn = true;
						$attendee->confidence = $attendees[$i]['confidence'];
						$eventAttendees[] = $attendee;
					}
				}
				
				EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
				EFCommon::$smarty->display('manage_attendees.tpl');
				break;
			case '/event/manage/checkin':
				EFCommon::$smarty->display('manage_checkin.tpl');
				break;
			case '/event/manage/confirm':
				$page['confirm'] = true;
				$page['manage'] = true;
				EFCommon::$smarty->assign('page', $page);
			
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($_GET['eventId']);
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$attendee = new User($attendees[$i]);
					
					if ($attendees[$i]['is_attending'] == 1) {
						// $attendee->checkedIn = true;
						$attendee->confidence = $attendees[$i]['confidence'];
						$eventAttendees[] = $attendee;
					}
				}
				
				EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
				EFCommon::$smarty->display('manage_attendees.tpl');
				break;
			case '/event/manage/edit':
				$this->validateUserLogin();
				$page['edit'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if ( ! isset ($_POST['submit']) ) {
					$editEvent = $this->buildEvent($_GET['eventId'], true);
					$this->saveEventFields( $editEvent );
					EFCommon::$smarty->display('manage_edit.tpl');
					break;
				}
				
				// Fill in event information
				$editEvent = new Event(NULL);
				$editEvent->eid = $_GET['eventId'];
				
				// Check to see if the new event information is valid.
				if ( $this->validateEventInfo( $editEvent ) === true ) {
					EFCommon::$dbCon->updateEvent( $editEvent );
					$_SESSION['manage_event'] = new Event($editEvent->eid);
					EFCommon::$smarty->assign("saved", true);
				}
				
				$this->saveEventFields( $editEvent );
				EFCommon::$smarty->display('manage_edit.tpl');
                
				break;
			case '/event/manage/guests':
				$this->validateUserLogin();
				$page['manage'] = true;
				$page['addguests'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				$event = $this->buildEvent( $_GET['eventId'], true );
				
				if ( isset($_POST['submit']) ) {
					$message = $event->submitGuests();
					EFCommon::$smarty->assign("message", $message);
				}
				
				// Fetch the users who have signed up
				$curSignUp = EFCommon::$dbCon->getAttendeesByEvent($eventId);

				$signedUp = NULL;
				foreach( $curSignUp as $guest ) {
					$signedUp[] = new User($guest);
				}
				EFCommon::$smarty->assign( 'signedUp', $signedUp );
				
				if( $event->numErrors > 0 ) {
					EFcommon::$smarty->assign( 'error', $event->error );
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
					if ( $internal && DEBUG ) {
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
			case '/event/manage/email':
				$this->validateUserLogin();
				$page['manage'] = true;
				$page['email'] = true;
				EFCommon::$smarty->assign('page', $page);

				$event = $this->buildEvent( $_GET['eventId'], true );
				
				EFCommon::$smarty->display('manage_email.tpl');
				break;
			case '/event/email/save':
				$this->validateLocalRequest();
				$event = $_SESSION['manage_event'];
				
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
				
				EFCommon::$dbCon->saveEmail( $event->eid, $_REQUEST['reminderContent'], $dateTime, $_REQUEST['reminderSubject'], EMAIL_REMINDER_TYPE, $autoReminder);
				echo("Success");
				break;
			case '/event/email/send':
				$this->validateLocalRequest();

				$event = $_SESSION['manage_event'];
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($event->eid);
				
				$req['content'] = $_REQUEST['reminderContent'];
				$req['subject'] = $_REQUEST['reminderSubject'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				$retval = $this->validateSaveEmail($req);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				EFCommon::$mailer->sendAutomatedEmail($event, $_REQUEST['reminderContent'], $_REQUEST['reminderSubject'], $attendees);
				echo("Success");
				break;
			case '/event/email/autosend':
				$this->validateLocalRequest();
				$event = $_SESSION['manage_event'];
				
				$isActivated = 0;
				if ($_REQUEST['autoSend'] == 'true') {
					$isActivated = 1;
				}

				EFCommon::$dbCon->setAutosend($event->eid, EMAIL_REMINDER_TYPE, $isActivated);
				break;
			case '/event/text/autosend':
				$this->validateLocalRequest();
				$event = $_SESSION['manage_event'];
				
				$isActivated = 0;
				if ($_REQUEST['autoSend'] == 'true') {
					$isActivated = 1;
				}

				EFCommon::$dbCon->setAutosend($event->eid, SMS_REMINDER_TYPE, $isActivated);
				break;
			case '/event/manage/text':
				$this->validateUserLogin();
				$page['manage'] = true;
				$page['text'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				$event = $this->buildEvent( $_GET['eventId'], true );
				
				$eventReminder = EFCommon::$dbCon->getEventEmail($event->eid, SMS_REMINDER_TYPE);
				if ( $eventReminder['is_activated'] == 1 ) {
					$eventReminder['isAuto'] = true;
				}
				
				if ( isset($eventReminder['datetime']) ) {
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
				}
				
				EFCommon::$smarty->display('manage_text.tpl');
				break;
			case '/event/text/send':
				$this->validateLocalRequest();
				$sms = new EFSMS();
				
				$event = $_SESSION['manage_event'];
				
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($event->eid);
				
				
				$req['content'] = $_REQUEST['reminderContent'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				$retval = $this->validateSaveEmail($req, true);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				$sms->sendSMSReminder($attendees, $event->eid, EFCommon::$mailer->mapText($_REQUEST['reminderContent'], $event->eid));
				print("Success");
				break;
			case '/event/text/save':
				$this->validateLocalRequest();
				$event = $_SESSION['manage_event'];
			
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
			case '/event/manage/followup':
				$page['manage'] = true;
				$page['followup'] = true;
				EFCommon::$smarty->assign('page', $page);

				$event = $this->buildEvent( $_GET['eventId'], true );
				
				$eventReminder = EFCommon::$dbCon->getEventEmail($event->eid, EMAIL_REMINDER_TYPE);
				if ( $eventReminder['is_activated'] == 1 ) {
					$eventReminder['isAuto'] = true;
				}
				
				if ( isset($eventReminder['datetime']) ) {
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
				}
				EFCommon::$smarty->display('manage_email.tpl');
				break;				
			case '/fb/user/update':
				$this->validateLocalRequest();
				EFCommon::$dbCon->facebookAdd($_REQUEST['fbid']);
				break;
			case '/register':
				// Logged in user doesn't need to create an account!
				$this->loggedInRedirect();

				// Make sure the user is properly redirected
				if ( isset($params) ) {
					EFCommon::$smarty->assign('redirect', $params);
				}

				if ( isset($_POST['isFB']) ) {
					$this->handleFBLogin();
					break;
				// if the user submits the register form
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
						EFCommon::$smarty->display('create_account.tpl');
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
					$_SESSION['user'] = new User($userInfo);
					
					// Send welcome email
					EFCommon::$mailer->sendHtmlEmail('welcome', $_SESSION['user'], 'Welcome to trueRSVP {Guest name}');
				} else {
					EFCommon::$smarty->display('create_account.tpl');
					break;
				}


				if ( isset ( $params ) ) {
					header("Location: " . $this->getRedirectUrl());
					exit;
				}
				
				header("Location: " . CURHOST);
				break;
			case '/login':
				$this->loggedInRedirect();
				
				// Make sure the user is properly redirected
				if ( isset($params) ) {
					EFCommon::$smarty->assign('redirect', $params);
				}
				
				if ( isset($_POST['isFB']) ) {
					$this->handleFBLogin();
					break;
				// if the user submits the login form
				} else if ( isset( $_POST['login'] ) ) {
					if ( ! isset( $_POST['email'] ) ) {
						EFCommon::$smarty->assign('user_login_email', "Please enter an e-mail");
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					if ( ! isset( $_POST['pass'] ) ) {
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
					
					$_SESSION['user'] = new User($userId);
					// if there's new event session when logging in
					// $this->checkCreateEventSession();
					
					// Success, log in
					header("Location: " . $this->getRedirectUrl());
					exit;

				/* User used trueRSVP register */
				} else {
					EFCommon::$smarty->display('login.tpl');
					break;
				}
				
				if ( isset ( $params ) ) {
					header("Location: " . $this->getRedirectUrl());
					exit;
				}
				
				header("Location: " . CURHOST);
				break;
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
				$hash_key = md5(time().$_REQUEST['login_forgot_email']);

				if (EFCommon::$dbCon->requestPasswordReset($hash_key, $_REQUEST['login_forgot_email'])) {
					EFCommon::$mailer->sendResetPassLink('/login/reset', $hash_key, $_REQUEST['login_forgot_email']);
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
				
				EFCommon::$dbCon->saveUserPic($result['file']);
				
				// to pass data through iframe you 
				// will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/user/status/update':
				$this->validateLocalRequest();
				EFCommon::$dbCon->updateUserStatus($_REQUEST['value']);
				echo($_REQUEST['value']);	
				break;
			case '/user/profile/update':
				$this->validateLocalRequest();
				// EFCommon::$dbCon->updatePaypalEmail($_SESSION['user']->id, $_REQUEST['paypal_email']);

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
			case '/logout':
				if ( ! isset($_SESSION['user']) ) {
					EFCommon::$smarty->display('error.tpl');
					break;
				}
				session_unset();
				session_destroy();
				EFCommon::$smarty->display('index.tpl');
				break;
			case '/calendar/ics':
				$event = $this->buildEvent( $_GET['eventId'] );
				$event->getICS();
				break;
			case '/calendar/vcs':
				$event = $this->buildEvent( $_GET['eventId'] );
				$event->getVCS();
				break;
			default:
				EFCommon::$smarty->assign('current_page', $current_page);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}
