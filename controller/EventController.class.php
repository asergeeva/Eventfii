<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/PanelController.class.php');
require_once(realpath(dirname(__FILE__)).'/../db/AdminDB.class.php');

class EventController extends PanelController {
	public function __construct() {
		$this->dbConn = new AdminDB();
	}
	
	public function __destruct() {
	
	}
	
	/**
	 * Displaying the event given its ID
	 * @param $eventId  Integer event ID
	 */
	private function getEventImage($eid)
	{
		return $image = $this->dbConn->admin_getEventImage($eid);
		exit;
	}
	/*Check whether email already exist*/
	private function checkGuestEmail($email)
	{
		return EFCommon::$dbCon->isUserEmailExist($email);
	}
	/**Function to add attendance
	*/
	private function addAttendance($event_id, $conf)
	{
		$user_id_to_post = $_SESSION['user']->id;
		$event = $this->buildEvent($event_id);
		for($i=1;$i<=$_SESSION['total_rsvps'];$i++)
		{
			if(isset($_SESSION['guest_email_'.$i]) && $_SESSION['guest_email_'.$i] != '' && $_SESSION['guest_email_'.$i] != 'Email')
			{
				$guestName = $_SESSION['guest_name_'.$i];
				unset($_SESSION['guest_name_'.$i]);
				$guestEmail = $_SESSION['guest_email_'.$i];
				unset($_SESSION['guest_email_'.$i]);
				$exist = $this->checkGuestEmail($guestEmail);
				if($exist)
				{
					$userInfo = EFCommon::$dbCon->getUserInfoByEmail($guestEmail);
					$userBuilded = new User($userInfo);
					$recordAttendance = EFCommon::$dbCon->eventSignUpWithOutEmail($userInfo['id'], $event, $conf, $user_id_to_post);
					EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $userBuilded, $event, 'Thank you for RSVPing to {Event name}');
				}else
				{
					$userInfo = EFCommon::$dbCon->createNewUser($guestName, NULL, $guestEmail);
					$userBuilded = new User($userInfo);
					$recordAttendance = EFCommon::$dbCon->eventSignUpWithOutEmail($userInfo['id'], $event, $conf, $user_id_to_post);
					EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $userBuilded, $event, 'Thank you for RSVPing to {Event name}');
				}
			}
		}
		unset($_SESSION['total_rsvps']);
	}
	
	private function displayEventById($eventId) {
		$event = $this->buildEvent($eventId);
		$_SESSION['eventViewed'] = $event;
		$extraParam = '';
		if (isset($_SESSION['attemptValue']) && isset($_SESSION['user']) && $event->rsvp_days_left >= 0) {
			// Make sure that it only select one choice
			if (sizeof($_SESSION['attemptValue']) == 1) {
				foreach ($_SESSION['attemptValue'] as $eid => $conf) {
					$event->currentUserAttend($conf, false);
					$this->addAttendance($eid, $conf);
					unset($_SESSION['attemptValue']);
				}
				$extraParam = '1';
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
		$you_rsvpd = 'false';
		foreach($event_attendees as $guest) {
			$attending[] = new User($guest);
		}
		EFCommon::$smarty->assign( 'showUpload', 'no' );
		$user_refered = NULL;
		if(isset($_SESSION['user']))
		{
			$user_refered = EFCommon::$dbCon->getConfirmedGuestsByUser($_SESSION['user']->id, $eventId);
			foreach($attending as $value)
			{
				if($value->email == $_SESSION['user']->email)
				{
					EFCommon::$smarty->assign( 'showUpload', 'yes' );	
					$you_rsvpd = 'true';
					break;
				}
			}		
		}
		
		EFCommon::$smarty->assign( 'user_refered',  $user_refered);
		EFCommon::$smarty->assign( 'you_rsvpd',  $you_rsvpd);
		EFCommon::$smarty->assign( 'attending', $attending );
		if(isset($_SESSION['user']))
			EFCommon::$smarty->assign( 'userid', json_encode(array('uid'=>$_SESSION['user']->id,'eid'=>$eventId)) );
		else
			EFCommon::$smarty->assign( 'userid', json_encode(array('uid'=>0,'eid'=>$eventId)) );
		EFCommon::$smarty->assign('event_image', $this->getEventImage($eventId));
		EFCommon::$smarty->assign("extraParam", $extraParam);
		EFCommon::$smarty->display('event.tpl');
	}
	
	/* function getEventIdByUri
	 * Gets the Event ID given a URI, returns
	 * false if invalid URI
	 *
	 * @param requestUri | The URI of the page to be displayed
	 * @return eventId | The event ID
	 * @return false | If invalid URI
	 */
	private function getEventIdByUri( $requestUri ) {
		$eventId = explode('/', $requestUri);
		
		// Verify that format of URL is http://{$CURHOST}/event/{$eventId}
		if (sizeof($eventId) != 3 ) {
			return false;
		}
		
		$eventId = $eventId[sizeof($eventId) - 1];
		
		return $eventId;
	}
	
	/**
	 * Controller for the following prefixes: 
	 *		1. /event/a/{event alias}
	 *		2. /event/{event id}
	 * @return false when it does not match the prefix
	 */
	public function getView($current_page) {
		// If mail invite reference, save in Session
		if (isset($_REQUEST['eref'])) {
			$_SESSION['eref'] = EFCommon::$dbCon->getReferenceEmail($_REQUEST['eref']);
		}
	
		// If event has an alias URL
		if (preg_match("/event\/a\/.*/", $current_page) > 0) {
			$alias = $this->getAliasByUri($current_page);
			if ( ! $alias ) {
				//EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$eventDb = EFCommon::$dbCon->getEventByURIAlias($alias);
			$this->displayEventById($eventDb['id']);
			exit;
		}
	
		// If /event in URI, display all event pages
		if (preg_match("/event\/\d+/", $current_page) > 0) {
			$eventId = $this->getEventIdByUri( $current_page );
			if ( ! $eventId ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$this->displayEventById($eventId);
			exit;
		} // END /event
		return false;
	}
}