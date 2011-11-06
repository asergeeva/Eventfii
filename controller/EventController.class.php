<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/PanelController.class.php');
 
class EventController extends PanelController {
	public function __construct() {
	
	}
	
	public function __destruct() {
	
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
					$event->currentUserAttend($conf, false);
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
	 *		1. /event/create
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
				EFCommon::$smarty->display( 'error.tpl' );
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