<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
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
		if ( $eventId == NULL ) {
			$eventId = $_GET['eventId'];
		}
		$user_array = EFCommon::$dbCon->getAttendeesByEvent($eventId);
		foreach($user_array as $userInfo) {
			$attendees[] = new AbstractUser($userInfo);
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
			header("Location: " . CURHOST . "/login");
			exit;
		}
		
		EFCommon::$dbCon->createNewEvent($newEvent);
		
		$_SESSION['newEvent'] = EFCommon::$dbCon->getLastEventCreatedBy($_SESSION['user']->id);
		$_SESSION['newEvent']->setGuests(NULL);
		$this->attendEvent($_SESSION['newEvent']->eid, 90, false);
				
		header("Location: " . CURHOST . "/event/create/guests?eventId=" . $_SESSION['newEvent']->eid );
		exit;
	}

	// checkUserCreationForm
	public function checkUserCreationForm($userInfo) {
		$flag = 1;
		$email = $userInfo['email'];
		$password = $userInfo['password'];
		$fname = $userInfo['fname'];
		$lname = $userInfo['lname'];
		$phone = $userInfo['phone'];
		$zip = $userInfo['zip'];

		$email_val = 	$this->valEmail(
							$email,
							"email",
							"Email entered is invalid."
						);
		if ( strlen($password) < 6 ) {
			$flag = 2;
			$error['password'] = "Password should be at least 6 characters";
			EFCommon::$smarty->append("error", $error, true);
		}
		$f_name_val = 	$this->valUsingRegExp(
							$fname,
							"/^[A-Za-z']*$/",
							"fname",
							"First name should only contain letters"
						);
		$l_name_val = 	$this->valUsingRegExp(
							$lname,
							"/^[A-Za-z']*$/",
							"lname",
							"Last name should only contain letters"
						);
		$ph_val = 		$this->valUsingRegExp(
							$phone,
							"/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/",
							"phone",
							"Phone number is not in valid format"
						);
		$zipcode_val = 	$this->valUsingRegExp(
							$zip, 
							"/^\d{5}(-\d{4})?$/", 
							"zip", 
							"Please enter a valid zip code"
						);

		if ( $f_name_val == 2 || $l_name_val == 2 || $email_val == 2 || $ph_val == 2 || $zipcode_val == 2 ) {
			$flag = 2;
		}
		
		// Flag = 2, error = true, else, error = false
		return ( $flag == 2 ) ? true : false;
	}

	// BEGIN OLD FUNCTIONS
	//////////////////////
	public function validateSaveEmail($req) {
		$msg="<br />";
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
			$msg.="Date must be in the future<br />";
			$flag=1;
		}

		$res=filter_var($req['content'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[\{A-Za-z 0-9'\}]*$/")));
		if(!($res)) {
			$flag=1;
			$msg.="Content can only contain characters A-Z or numbers 0-9 <br />";
		}

		if($flag==0) {
			$msg="Success";
		}

		return $msg;
	}

	public function valEmail($email, $type, $msg) {
		$flag = 1;
		if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$error[$type] = $msg;
			EFCommon::$smarty->append('error', $error, true);
			$flag = 2;
		}
		return $flag;
	}

	public function valUsingRegExp($val,$regex,$type,$msg) {
		$flag = 1;
		$res = filter_var( $val, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp" => $regex ) ) );
		if( ! $res ) {
			$error[$type] = $msg;
			EFCommon::$smarty->append('error', $error, true);
			$flag = 2;
		}
		return $flag;
	}

	////////////////
	// End OLD FUNCTIONS
	
	/***** USER PROFILE ASSIGN EVENTS ********/
	private function assignProfileEvents($uid) {
		$this->assignCreatedEventsProfile($uid);
		$this->assignAttendingEventsProfile($uid);
	}
	
	private function assignCreatedEventsProfile($uid) {
		$created_event = EFCommon::$dbCon->getEventByEO($uid, true);
		$createdEvents = NULL;
		foreach ( $created_event as $event ) {
			$createdEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('createdEvents', $createdEvents);
	}
	
	private function assignAttendingEventsProfile($uid) {
		$attending_event = EFCommon::$dbCon->getEventAttendingByUid($uid, true);
		$attendingEvents = NULL;
		foreach( $attending_event as $event ) {
			$attendingEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('attendingEvents', $attendingEvents);
	}

	/***** CONTROL PANEL ASSIGN EVENTS ********/
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
		
		$eventId = $_GET['eventId'];
		$event = new Event($eventId);
		
		$eventAttendees = EFCommon::$dbCon->getAttendeesByEvent($eventId);

		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}

		EFCommon::$smarty->assign('trsvpVal', EFCommon::$core->getTrueRSVP($event));
		EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
		EFCommon::$smarty->assign('eventInfo', $eventInfo);
		EFCommon::$smarty->display('manage_event_on.tpl');
	}
	
	public function assignManageVars($eventId) {
		$event = new Event($eventId);
	
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
		
		EFCommon::$smarty->assign('guestimate', EFCommon::$core->computeGuestimate($eventId));
		EFCommon::$smarty->assign('trsvpVal', EFCommon::$core->getTrueRSVP($event));
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
	private function checkCreateEventSession() {
		if ( isset($_SESSION['newEvent']) && ! isset($_SESSION['newEvent']->eid) ) {
			if ( $this->validateEventInfo( $_SESSION['newEvent'] )) {
				$newEvent = $_SESSION['newEvent'];
				$newEvent->organizer = $_SESSION['user'];
				$this->makeNewEvent( $newEvent );
			}
		}
	}
	
	private function getRedirectUrl() {
		if (isset($_SESSION['ref'])) {
			$inviteReference = EFCommon::$dbCon->getInviteReference($_SESSION['ref'], $_POST['email']);
			if (is_numeric($inviteReference['event_id'])) {
				$url = CURHOST."/event/".$inviteReference['event_id'];
			}
			unset($_SESSION['ref']);
		} else {	
			switch ($_GET['redirect']) {
				case 'cp':
					$url = CURHOST . "?loggedIn=true";
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

	/*
	 * Redirect the user home if he's logged in
     */
	private function loggedInRedirect() {
		
		// if the user already logged in
		if ( isset($_SESSION['user']) ) {
			// If there is a page to be redirected to
			if (isset($_SESSION['eventViewed'])) {
				header("Location: " . EVENT_URL . "/a/".$_SESSION['eventViewed']->alias);
			} else if (isset($_SESSION['page_redirect'])) { 
				header("Location: " . $_SESSION['page_redirect']);
				unset($_SESSION['page_redirect']);
			} else if(isset($_SESSION['fb'])) {
				header("Location: " . CURHOST . "/home?loggedIn=true");
			} else {
				header("Location: " . CURHOST . "/home?loggedIn=false");
			}
			exit;
		}
	}
	
	/*
	 * The user has just logged in to FB,
	 * so let's take him to his profile page
	 */
	private function handleFBLogin() {
		$userInfo = EFCommon::$dbCon->facebookConnect( $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['fbid'], 
													   $_POST['fb_access_token'], $_POST['fb_session_key'] );
		if ( $userInfo ) {
			$_SESSION['fb'] = new User($userInfo);
			
			echo 3;
		} else {
			echo 0;
		}
	}
	
	/**
	 * Create the user object for each user that is retrieved from the DB
	 * &$array  Array  the reference array that we want to append the user object into
	 * $userDb  Array  the array of users from the DB
	 */
	private function appendGuests(&$array, $userDb) {
		for ($i = 0; $i < sizeof($userDb); ++$i) {
			array_push($array, new User($userDb[$i]));
		}
	}
	
	/**
	 * Given the current page URI, get its alias
	 * @return String alias of the event URI
	 */
	private function getAliasByUri($requestUri) {
		$alias = explode('/', $requestUri);
		
		// Verify that format of URL is http://{$CURHOST}/event/a/{$alias}
		if (sizeof($alias) != 4 ) {
			return false;
		}
		
		$alias = $alias[sizeof($alias) - 1];
		
		return $alias;
	}
	
	/**
	 * Display the error page with the specified error message
	 * @param $error_message  String  the error message to be displayed
	 */
	private function displayError($error_message) {
		EFCommon::$smarty->assign('error_message', $error_message);
		EFCommon::$smarty->display('error.tpl');
	}
	
	/**
	 * Make sure that the user is valid seeing the current page
	 */
	private function securityValidate($current_page) {
		// /event/manage/* pages are protected
		if (preg_match("/event\/manage*/", $current_page) > 0) {
			if (!isset($_SESSION['user'])) {
				header("Location: ".CURHOST."/login");
			}
			else if (!EFCommon::$dbCon->checkValidHost($_REQUEST['eventId'], $_SESSION['user']->id)) {
				$this->displayError("You're not the host of this event");
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Displaying the event given its ID
	 * @param $eventId  Integer event ID
	 */
	private function displayEventById($eventId) {
		$event = $this->buildEvent($eventId);
		$_SESSION['eventViewed'] = $event;
		
		if (isset($_SESSION['attemptValue']) && isset($_SESSION['user']) && $event->rsvp_days_left >= 0) {
			// Make sure that it only select one choice
			if (sizeof($_SESSION['attemptValue']) == 1) {
				foreach ($_SESSION['attemptValue'] as $eid => $conf) {
					$this->attendEvent($eid, $conf, false);
					unset($_SESSION['attemptValue']);
				}
			}
		}
		
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
			
			// If the deadline passed, disable the input
			if ($event->rsvp_days_left < 0) {
				EFCommon::$smarty->assign('disabled', ' disabled="disabled"');
				EFCommon::$smarty->assign('loggedIn', true);
			}
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
	}
	
	private function displayUserById($userId) {
		$profile = new User($userId);
		if ( ! $profile->exists ) {
			EFCommon::$smarty->display('error_user_notexist.tpl');
		} else {
			// $is_following = isset($_SESSION['user']) ? EFCommon::$dbCon->isFollowing($_SESSION['user']->id, $profile->id) : 0;
			// EFCommon::$smarty->assign("is_following", $is_following);
			$this->assignProfileEvents($userId);
			EFCommon::$smarty->assign("profile", $profile);
			EFCommon::$smarty->display('profile.tpl');
		}
	}
	
	/**
	 * The current user session would like to sign up specified event 
	 * 		with specified confidence
	 *
	 * @param $eid  Integer event ID
	 * @param $conf Integer confidence value
	 *
	 */
	private function attendEvent($eid, $conf, $isAjax = true) {
		$event = $this->buildEvent($eid);
		$notification = NULL;
		
		// Waiting list
		if (EFCommon::$core->getTrueRSVP($event) >= $event->goal) {
			switch($event->reach_goal) {
				case 1:
					EFCommon::$dbCon->eventSignUp($_SESSION['user']->id, $event, $conf);
					$notification = "Thanks for your RSVP! Look forward to seeing you at the event!";
					break;
				case 2:
					$notification = "Sorry! The event has reached capacity.";
					break;
				case 3:
					EFCommon::$dbCon->eventWaitlist($_SESSION['user']->id, $event, $conf);
					$notification = "You have been added to the waiting list for this event";
					break;
			}
		} else {
			EFCommon::$dbCon->eventSignUp($_SESSION['user']->id, $event, $conf);
			$notification = "Thanks for your RSVP! Look forward to seeing you at the event!";
		}
		
		if (isset($notification)) {
			if ($isAjax) {
				print($notification);
			} else {
				EFCommon::$smarty->assign('attendNotification', $notification);
			}
		}
	}
	
	public static function clearExtraUserSessions() {
		unset($_SESSION['newEvent']);
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
		
		// Check for cookie
		if (isset($_COOKIE[USER_COOKIE])) {
			$userCookie = EFCommon::$dbCon->getUserByCookie($_COOKIE[USER_COOKIE]);
			if (isset($userCookie)) {
				$_SESION['user'] = new User($userCookie);
			}
		}
		
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
		
		// Security validation
		if (!$this->securityValidate($current_page)) {
			return;
		}

		// If event has an alias URL
		if (preg_match("/event\/a\/.*/", $current_page) > 0) {
			$alias = $this->getAliasByUri($current_page);
			if ( ! $alias ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$eventDb = EFCommon::$dbCon->getEventByURIAlias($alias);
			$this->displayEventById($eventDb['id']);
			return;
		}
	
		// If /event in URI, display all event pages
		if (preg_match("/event\/\d+/", $current_page) > 0) {
			
			$eventId = $this->getEventIdByUri( $current_page );
			if ( ! $eventId ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$this->displayEventById($eventId);
			return;
		} // END /event
		
		// Quick check for permissions for editing events
		if ( preg_match("/event\/manage*/", $current_page) > 0 ) {
			if ( ! isset ( $_REQUEST['eventId'] ) ) {
				EFCommon::$smarty->display('error.tpl');
				return;
			}
			$eventId = $_REQUEST['eventId'];
			if ( ! isset ( $_SESSION['user'] ) ) {
				if ( $eventId ) {
					header("Location: " . CURHOST . "/login?redirect=manage&eventId=" . $eid);
				} else {
					header("Location: " . CURHOST . "/login");
				}
				exit;
			}
			
			// Fetch the event information if necessary
			$event = $this->buildEvent($eventId, true);
			
			$page['manage'] = true;
			EFCommon::$smarty->assign('page', $page);
		}
		
		// If the user has an alias URL
		if (preg_match("/user\/a\/.*/", $current_page) > 0) {
			$alias = $this->getAliasByUri($current_page);
			if ( ! $alias ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$userDb = EFCommon::$dbCon->getUserByURIAlias($alias);
			$this->displayUserById($userDb['id']);
			return;
		}
		
		// User public profile page
		if (preg_match("/user\/\d+/", $current_page) > 0) {
			$userId = $this->getUserIdByUri( $current_page );
			$this->displayUserById($userId);			
			return;
		}


		switch ($current_page) {
			case '/':
			case '/home':
				if (isset($_SESSION['isNewUser'])) {
					EFCommon::$smarty->assign('isNewUser', true);
					unset($_SESSION['isNewUser']);
				}
				if (isset($_SESSION['user'])) {
					$page['cp'] = true;
					EFCommon::$smarty->assign('page', $page);

					// Check new event
					$this->checkCreateEventSession();
			
					unset($_SESSION['newEvent']);
					unset($_SESSION['new_eid']);
					unset($_SESSION['manage_event']);
					unset($_SESSION['contact_form']);
					unset($_SESSION['gref']);
					unset($_SESSION['eventViewed']);
					
					$this->assignCPEvents($_SESSION['user']->id);
					
					EFCommon::$smarty->display('cp.tpl');
				} else {
					EFCommon::$smarty->display('index.tpl');
				}
				break;
			case '/demo':
				header("Location: ".EVENT_URL."/a/1af");
				break;
			case '/media':
				EFCommon::$smarty->display('media.tpl');
				break;
			case '/terms':
				EFCommon::$smarty->display('terms.tpl');
				break;
			case '/privacy':
				EFCommon::$smarty->display('privacy.tpl');
				break;
			case '/contact':
				// if the form's been submitted, send its contents
				if ( isset($_POST['submit']) && ! isset($_GET['success']) ) {
					
					// Validate input
					$error = NULL;
					
					// Check user credentials
					if ( ! isset($_SESSION['user']) ) {
						if ( ! isset($_POST['email']) || $_POST['email'] === '' ) {
							$error['email'] = 'You must enter an email address in this field so we can get back to you!';
						} else if ( !preg_match('/^[A-Za-z0-9._%+]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,5}$/', $_POST['email']) ){
							$error['email'] = 'The email address you entered appears to be invalid!';
						}	
					}
					
					// Verify message was entered
					if ( ! isset($_POST['message']) || $_POST['message'] === '' ) {
						$error['message'] = 'You must enter a message!';
					}

					// Assign errors
					
					
					// at this point we know whether the input is valid or not
					if ( $error === NULL ){
						$subject = ( isset($_POST['subject']) ) ? $_POST['subject'] : "";
						$rawMime = "X-Mailgun-Tag: truersvp\n" . 
								   "Content-Type: plaintext;charset=UTF-8\n" . 
								   "From: " . $_POST['name'] . "<" . $_POST['email'] . ">\n" . 
								   "To: support@truersvp.com\n" . 
								   "Subject: [trueRSVP Support] " . $subject . "\n\n". $_POST['message'];
						MailgunMessage::send_raw($_POST['email'], 'support@truersvp.com', $rawMime);

						// let's thank the user for contacting us
						header("Location: " . CURHOST . "/contact?success=true");
					} else {
						EFCommon::$smarty->assign('error', $error);
					}
				} else if ( $_GET['success'] == true ) {
					EFCommon::$smarty->assign('notification', 'Thank you for your feedback!');
				}
				
				EFCommon::$smarty->display('contact.tpl');
				
				break;
			case '/share':
				EFCommon::$smarty->display('share.tpl');
				break;
			case '/method':
				EFCommon::$smarty->display('method.tpl');
				break;
			case '/feedback/send':
				EFCommon::$mailer->sendFeedback();
				break;
			case '/contacts':
				$page['contacts'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				$contacts = array();
				$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
				for ($i = 0; $i < sizeof($contactList); ++$i) {
					$contact = new User($contactList[$i]);
					array_push($contacts, $contact);
				}
				
				if ( sizeof($contacts) > 0 )
					EFCommon::$smarty->assign('contacts', $contacts);
				else
					EFCommon::$smarty->assign('contacts', NULL);
				
				EFCommon::$smarty->display('cp_contacts.tpl');
				break;
			case '/contacts/add':
				$page['addcontacts'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if (isset($_POST['submit'])) {
					$_SESSION['user']->addContacts();
				}
				
				EFCommon::$smarty->assign('fbSubmit', CURHOST . '/contacts/add?option=fb&gref='.$event->global_ref);
				EFCommon::$smarty->assign('submitTo', CURHOST . '/contacts/add');
				EFCommon::$smarty->display('cp_contacts.tpl');
				break;
			case '/settings':
				if ( ! isset($_SESSION['user']) ) {
					header("Location: " . CURHOST);
					exit;
				} 
				
				$page['settings'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if ( isset($_POST['submit']) ) {
					$responseMsg = array();
					
					$user = new User(NULL);
					$error = $user->get_errors();
					
					// UPDATE DB
					if ( ! $error ) {
						$notification = "User settings updated successfully.";
						EFCommon::$smarty->assign('notification', $notification);
					} else {
						EFCommon::$smarty->assign("error", $error);
					}
					
					// RESET PASSWORD
					if ( $_REQUEST['user-curpass'] != '' || $_REQUEST['user-newpass'] != '' || $_REQUEST['user-confpass'] != '' ) {
						if ( EFCommon::$dbCon->resetPassword( md5($_REQUEST['user-curpass']), md5($_REQUEST['user-newpass']), md5($_REQUEST['user-confpass']) )) {
							$responseMsg['password_success'] = 'Password has been updated';
						} else {
							$responseMsg['password_error'] = 'Invalid password';
						}
					}
					EFCommon::$smarty->assign('responseMsg', $responseMsg);
				}
				
				EFCommon::$smarty->display('cp_settings.tpl');
				break;
			case '/event/create':
				$this->clearExtraUserSessions();
			
				if ( ! isset ( $_POST['step2'] ) && ! isset ( $_POST['step3'] ) ) {
					// Check to see if coming off of the index page
					if ( isset($_POST['submit']) ) {
						if (isset($_POST['title']) && strtolower($_POST['title']) != "name of event") {
							$event_field['title'] = stripslashes($_POST['title']);
						}
						if (isset($_POST['goal']) && strtolower($_POST['goal']) != "max") {
							$event_field['goal'] = stripslashes($_POST['goal']);
						}
						
						EFCommon::$smarty->assign('event_field', $event_field);
					} else if ( isset($_POST['step1']) ) {
						$newEvent = new Event(NULL, true);
						
						$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
						
						// Invalid Step 1
						if ( ! $is_valid ) {
							EFCommon::$smarty->assign('error', $newEvent->error);
							$this->saveEventFields( $newEvent );
						// Valid Step 1
						} else {
							// Display Step 2
							EFCommon::$smarty->assign('step', 2);
							EFCommon::$smarty->display('create.tpl');
							break;
						}
					} else if ( isset($_SESSION['user']) ) {
						unset($_SESSION['newEvent']);
					}
					
					// Display Step 1
					EFCommon::$smarty->assign('step', 1);
					EFCommon::$smarty->display('create.tpl');
					break;
				
				// Check Step 2
				} else if ( isset($_POST['step2']) ) {
					$newEvent = new Event(NULL);
					
					$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
										
					// Invalid Step 2
					if ( ! $is_valid ) {
						EFCommon::$smarty->assign('error', $newEvent->error);
						$this->saveEventFields( $newEvent );
						
						// Display Step 2
						EFCommon::$smarty->assign('step', 2);
						EFCommon::$smarty->display('create.tpl');
						break;
						
					// Valid Step 2
					} else {
						// Display Step 3
						$this->makeNewEvent( $newEvent );
					}
				}
				break;
			case '/event/create/guests':
				if ( ! isset($_GET['eventId']) ) {
					header("Location: " . CURHOST . "/event/create");
					exit;
				}
				
				if ( ! isset($_SESSION['newEvent']) ) {
					$_SESSION['newEvent'] = new Event($_GET['eventId']);
				}
								
				if ( isset($_POST['submit']) ) {
					$guest_emails = $_SESSION['newEvent']->submitGuests();
					if ( sizeof($guest_emails) == 0 ) {
						EFCommon::$smarty->assign('error', "No guests added.");
					} else {
						EFCommon::$smarty->assign('notification', "Yay!");
					}
				}
								
				$signedUp = $this->getAttendees(NULL);
				EFCommon::$smarty->assign('signedUp', $signedUp);
				
				$contacts = array();
				$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
				for ($i = 0; $i < sizeof($contactList); ++$i) {
					$contact = new User($contactList[$i]);
					array_push($contacts, $contact);
				}
				
				if ( sizeof($contacts) > 0 )
					EFCommon::$smarty->assign('contacts', $contacts);
				else
					EFCommon::$smarty->assign('contacts', NULL);
				
				EFCommon::$smarty->assign('finishSubmit', CURHOST.'/event/a/'.$_SESSION['newEvent']->alias.'?created=true');
				EFCommon::$smarty->assign('step', 3);
				EFCommon::$smarty->assign('addButton', true);
				EFCommon::$smarty->assign('event', $_SESSION['newEvent']);
				EFCommon::$smarty->assign('submitTo', CURHOST . "/event/create/guests?eventId=" . $_SESSION['newEvent']->eid . " &option=".$_GET['option']);
				EFCommon::$smarty->display('create_guest.tpl');
				break;
			case '/event/manage/cancel':
				if (EFcommon::$dbCon->deleteEvent($_POST['eventId'])) {
					print("Event is successfully deleted");
					// Add successful template for event cancellation
					break;
				}
				// Add an error template for the invalid host
				print("You are not the host for this event");
				break;
			case '/event/image/upload':
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
				$this->attendEvent($_POST['eid'], $_POST['conf']);
				break;
			case '/event/attend/attempt':
				unset($_SESSION['attemptValue']);
				$_SESSION['attemptValue'] = array($_POST['eid'] => $_POST['conf']);
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
				$page['cp'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				$this->buildEvent( $_GET['eventId'], true );
				
				$this->assignManageVars( $_GET['eventId'] );
				
				EFCommon::$smarty->display('manage.tpl');
				break;
			case '/event/manage/attendees':
				$page['attendeelist'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
			
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($_GET['eventId']);
				$eventAttendees = array();
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$attendee = new User($attendees[$i]);
					
					$attendee->friendly_confidence = EFCommon::$confidenceMap[$attendees[$i]['confidence']];
					$attendee->confidence = $attendees[$i]['confidence'];
					$eventAttendees[] = $attendee;
				}
				
				EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
				EFCommon::$smarty->display('manage_attendees.tpl');
				break;
			case '/event/manage/checkin':
				$page['checkin'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				EFCommon::$smarty->display('manage_checkin.tpl');
				break;
			case '/event/manage/confirm':
				$page['confirm'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
			
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
				$page['addguests'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				$event = $this->buildEvent( $_GET['eventId'], true );
				EFCommon::$smarty->assign("event", $event);
								
				if ( isset($_POST['submit']) ) {
					$message = $event->submitGuests();
					EFCommon::$smarty->assign("message", $message);
				}
				
				// Fetch the users who have signed up
				$invited_users_array = EFCommon::$dbCon->getAttendeesByEvent($event->eid);

				$contacts = array();
				$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
				for ($i = 0; $i < sizeof($contactList); ++$i) {
					$contact = new User($contactList[$i]);
					array_push($contacts, $contact);
				}
				
				$signedUp = $this->getAttendees(NULL);
				EFCommon::$smarty->assign('signedUp', $signedUp);
				
				if ( sizeof($contacts) > 0 )
					EFCommon::$smarty->assign('contacts', $contacts);
				else
					EFCommon::$smarty->assign('contacts', NULL);
				
				if( $event->numErrors > 0 ) {
					EFcommon::$smarty->assign( 'error', $event->error );
				}
				
				EFCommon::$smarty->assign('event', $event);
				EFCommon::$smarty->assign('finishSubmit', CURHOST.'/event/manage/guests?eventId='.$event->eid.'&option='.$_GET['option']);
				EFCommon::$smarty->assign('fbSubmit', CURHOST.'/event/manage/guests?eventId='.$event->eid."&option=fb&gref=".$event->global_ref);
				EFCommon::$smarty->assign('submitTo', CURHOST.'/event/manage/guests?eventId='.$event->eid.'&option='.$_GET['option']);
				EFCommon::$smarty->assign('addButton', true);
				EFCommon::$smarty->display('manage_guests.tpl');
				break;
			case '/guest/inviter':
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
					if ($_REQUEST['provider'] == 'truersvp') {
						$contacts = array();
						$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
						for ($i = 0; $i < sizeof($contactList); ++$i) {
							$contact = new User($contactList[$i]);
							array_push($contacts, $contact);
						}
						
						if ( sizeof($contacts) > 0 )
							EFCommon::$smarty->assign('contacts', $contacts);
						else
							EFCommon::$smarty->assign('contacts', NULL);
						
						EFCommon::$smarty->assign('addButton', true);
						EFCommon::$smarty->display('contacts.tpl');
					} else {
						EFCommon::$smarty->assign('provider', $_REQUEST['provider']);
						EFCommon::$smarty->display('event_add_guest_right.tpl');
					}
				}
				break;
			case '/event/manage/email':
				$page['email'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				EFCommon::$smarty->display('manage_email.tpl');
				break;
			case '/event/email/send':
				$event = $_SESSION['manage_event'];
				
				// Determine whether the auto reminder is activated
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				// Validation
				$email = new EFEmailMessage($_POST['reminderSubject'],
									   		$_POST['reminderContent'],
									   		$_POST['reminderTime'],
									   		$_POST['reminderDate'],
									   		$_POST['reminderRecipient']);
				
				if($email->get_errors($autoReminder)) {
					die($email->print_errors());
				}
				
				// If the auto reminder is enabled, we will only save it (sending it through cron job)
				if ($autoReminder == 1) {
					$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
					$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
					EFCommon::$dbCon->saveEmail( $_POST['eid'], 
												 $_POST['reminderContent'], 
												 $dateTime, 
												 $_POST['reminderSubject'], 
												 $autoReminder, 
												 $_POST['reminderRecipient'],
												 $_POST['isFollowup'] );
					echo("Saved");
					return;
				}
				
				// If the auto reminder is not enabled, we will send it right away
				$guests = array();
				switch ($_POST['reminderRecipient']) {
					case 1:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuests($_POST['eid']));
						break;
					case 2:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT1));
						break;
					case 3:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT2));											$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT3));
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT4));
						break;
					case 4:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT5));
						break;
					case 5:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFELSE));
						break;
				}
				
				// Send email to all of the guests specified
				for ($i = 0; $i < sizeof($guests); ++$i) {
					EFCommon::$mailer->sendHtmlEmail('general', $guests[$i], $_POST['reminderSubject'], $event, $_POST['reminderContent']);
				}
				echo("Sent");
				break;
			case '/event/manage/text':
				$page['text'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				EFCommon::$smarty->display('manage_text.tpl');
				break;
			case '/event/text/send':
				$event = $_SESSION['manage_event'];
				
				// Determine whether the auto reminder is activated
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				// Validation
				$sms = new AbstractMessage($_POST['reminderContent'],
									   	   $_POST['reminderTime'],
									   	   $_POST['reminderDate'],
									   	   $_POST['reminderRecipient']);
				
				if($sms->get_errors($autoReminder)) {
					die($sms->print_errors());
				}
				
				// If the auto reminder is enabled, we will only save it (sending it through cron job)
				if ($autoReminder == 1) {
					$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
					$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
					EFCommon::$dbCon->saveText( $_POST['eid'], 
												 $_POST['reminderContent'], 
												 $dateTime, 
												 $autoReminder, 
												 $_POST['reminderRecipient'] );
					echo("Saved");
					return;
				}
				
				// If the auto reminder is not enabled, we will send it right away
				$guests = array();
				switch ($_POST['reminderRecipient']) {
					case 1:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuests($_POST['eid']));
						break;
					case 2:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT1));
						break;
					case 3:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT2));											$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT3));
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT4));
						break;
					case 4:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT5));
						break;
					case 5:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFELSE));
						break;
				}
				
				EFCommon::$sms->sendSMSReminder($guests, $event, EFCommon::$mailer->mapText($_REQUEST['reminderContent'], $event->eid));
				echo("Sent");
				break;
			case '/event/text/save':
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
				
				$retval = $this->validateSaveEmail($req);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				EFCommon::$dbCon->saveText($event->eid, $_REQUEST['reminderContent'], $dateTime, SMS_REMINDER_TYPE, $autoReminder);
				echo("Success");
				break;
			case '/event/manage/followup':
				$page['followup'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				EFCommon::$smarty->assign('is_followup', true);

				EFCommon::$smarty->display('manage_email.tpl');
				break;				
			case '/fb/user/update':
				EFCommon::$dbCon->facebookAdd($_REQUEST['fbid']);
				break;
			case '/fb/friends':
				$fbFriends = json_decode(stripslashes($_POST['fbFriends']));
				EFCommon::$dbCon->saveFBFriends($fbFriends->data, $_SESSION['user']->id);
				break;
			case '/fb/invite':
				break;
			case '/register':
				// Logged in user doesn't need to create an account!
				$this->loggedInRedirect();
				
				// Make sure the user is properly redirected
				if ( isset($params) ) {
					EFCommon::$smarty->assign('redirect', $params);
				}

				// If this is a Facebook form login
				if ( isset($_POST['isFB']) ) {
					$this->handleFBLogin();
					break;
				// if the user submits the register form
				} else if ( isset ( $_POST['register'] ) ) {
					$userInfo['email'] = $_POST['email'];
					$userInfo['password'] = $_POST['password'];
					$userInfo['fname'] = $_POST['fname']; 	
					$userInfo['lname'] = $_POST['lname'];
					$userInfo['phone'] = $_POST['phone'];
					$userInfo['zip'] = $_POST['zip'];
					$errors = $this->checkUserCreationForm($userInfo);
					
					// Check if any errors
					if( $errors ) {
						EFCommon::$smarty->display('create_account.tpl');
						break;
					}
					
					// Create the new user
					$userInfo = EFCommon::$dbCon->createNewUser( $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone'], md5($_POST['password']), $_POST['zip'] );
					
					if (isset($userInfo)) {
						// Assign user's SESSION variables
						$_SESSION['user'] = new User($userInfo);
						
						// Send welcome email
						EFCommon::$mailer->sendHtmlEmail('welcome', $_SESSION['user'], 'Welcome to trueRSVP {Guest name}');
						
						// Check on which page the user should be redirected to
						$this->loggedInRedirect();
					} else {
						EFCommon::$smarty->assign('user_create_email', 'This email has been used');
						EFCommon::$smarty->display('create_account.tpl');
						break;
					}
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
					// If currently looking at the event page
					if (preg_match("/event\/\d+/", $_POST['curPage']) > 0) {
						$_SESSION['page_redirect'] = $_POST['curPage'];
					}
					$this->handleFBLogin();
					break;
				// if the user submits the login form
				} else if ( isset( $_POST['login'] ) ) {
					if ( strlen($_POST['email']) == 0 ) {
						$error['email'] = "Please enter an e-mail";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					} else if ( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
						$error['email'] = "Please enter a valid e-mail address";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					} else if ( ! EFCommon::$dbCon->isUserEmailExist( $_POST['email'] ) ) {
						$error['email'] = "E-mail is not registered in our system";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					if ( strlen($_POST['password']) == 0 ) {
						$error['password'] = "Please enter a password";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					
					$userInfo = EFCommon::$dbCon->checkValidUser( $_POST['email'], $_POST['password'] );
					
					if ( ! $userInfo ) {
						// Invalid e-mail/password combination
						$error['login'] = "Invalid e-mail or password";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					
					$_SESSION['user'] = new User($userInfo);
					setcookie(USER_COOKIE, $_SESSION['user']->cookie);
					
					// Create user's event if valid
					if ( isset($_SESSION['newEvent']) ) {
						$newEvent = $_SESSION['newEvent'];
						if ( $this->validateEventInfo( $newEvent ) == true ) {
							$this->makeNewEvent( $newEvent );
						}
					}
					
					// Check if the user needs to be redirected instead
					$this->loggedInRedirect();
					
					header("Location: " . CURHOST . "/home?loggedin=true");
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
				if (strlen($_REQUEST['login_forgot_newpass']) < 6) {
						EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
						EFCommon::$smarty->assign('errorMsg', 'Password must be at least 6 characters');
						EFCommon::$smarty->display('login_reset.tpl');
				} else {
					if ($_REQUEST['login_forgot_newpass'] == $_REQUEST['login_forgot_newpass_conf']) {
						EFCommon::$dbCon->resetPasswordByEmail($_REQUEST['login_forgot_newpass'], $_REQUEST['login_forgot_ref']);
						EFCommon::$smarty->display('login_reset_confirmed.tpl');
					} else {
						EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
						EFCommon::$smarty->assign('errorMsg', 'New password is not confirmed');
						EFCommon::$smarty->display('login_reset.tpl');
					}
				}
				break;
			case '/login/forgot':
				EFCommon::$smarty->display('login_forgot.tpl');
				break;
			case '/login/forgot/submit':
				$hash_key = md5(time().$_REQUEST['login_forgot_email']);
				$user = EFCommon::$dbCon->requestPasswordReset($hash_key, $_REQUEST['login_forgot_email']);
				
				if (isset($user)) {
					EFCommon::$mailer->sendHtmlEmail('general', $user, "Reset Password", NULL, "This is the link to reset your password: ".CURHOST."/login/reset?ref=".$hash_key);
					header('Location: ' . CURHOST . '/login/forgot/sent');
					exit;
				} else {
					EFCommon::$smarty->display('login_forgot_invalid.tpl');
				}
				break;
			case '/login/forgot/sent':
				EFCommon::$smarty->display('login_forgot_confirmed.tpl');
				break;
			case '/user/image/upload':
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
				if ($_REQUEST['value'] != "Click here to edit") {
					EFCommon::$dbCon->updateUserStatus($_REQUEST['value']);
					$_SESSION['user']->about = $_REQUEST['value'];
					echo($_REQUEST['value']);
				}
				break;
			case '/user/profile/update':
				// EFCommon::$dbCon->updatePaypalEmail($_SESSION['user']->id, $_REQUEST['paypal_email']);

				EFCommon::$smarty->display('user_profile.tpl');
				break;
			case '/user/profile-dtls/update':
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
					header('Location: ' . CURHOST);
					break;
				}
				session_unset();
				session_destroy();
				
				// Remove cookies
				unset($_COOKIE[USER_COOKIE]);
				setcookie(USER_COOKIE, NULL, -1);
				
				EFCommon::$smarty->display('index.tpl');
				break;
			case '/notyet':
				if ( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
					EFCommon::$dbCon->storeNotyet($_POST['email']);
					print("Thank you!");
				} else {
					print("Invalid email");
				}
				break;
			case '/calendar/ics':
				$event = $this->buildEvent( $_GET['eventId'] );
				$event->getICS();
				break;
			case '/calendar/vcs':
				$event = $this->buildEvent( $_GET['eventId'] );
				$event->getVCS();
				break;
			case '/service/autocomplete':
				break;
			case '/twitter/update':
				$responseMsg['user_success'] = "Your Twitter account is now connected.";
				EFCommon::$smarty->assign('responseMsg', $responseMsg);
				break;
			default:
				EFCommon::$smarty->assign('current_page', $current_page);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}
