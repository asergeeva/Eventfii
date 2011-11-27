<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/PanelController.class.php');
 
class CreateController extends PanelController {
	public function __construct() {
	
	}
	
	public function __destruct() {
	
	}
	
	/**
	 * Controller for the following prefixes: 
	 *		1. /event/create
	 * @return false when it does not match the prefix
	 */
	public function getView($current_page) {
		if (preg_match("/event\/create.*/", $current_page) > 0) {
			switch ($current_page) {
				case "/event/create":
					// If there are some of the fields that needs to be prefilled
					$event_field = array();
					if (isset($_POST['title']) && strtolower($_POST['title']) != "name of event") {
						$event_field['title'] = stripslashes($_POST['title']);
					}
					if (isset($_POST['goal']) && strtolower($_POST['goal']) != "max") {
						$event_field['goal'] = stripslashes($_POST['goal']);
					}
					EFCommon::$smarty->assign('event_field', $event_field);
				
					EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[0]);
					EFCommon::$smarty->assign('step', 1);
					EFCommon::$smarty->display('create.tpl');
					break;
				case "/event/create/details":
					$newEvent = new Event(NULL, true);
					
					$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
					
					if (!$is_valid) {
						EFCommon::$smarty->assign('error', $newEvent->error);
						$this->saveEventFields( $newEvent );
						
						EFCommon::$smarty->assign('step', 1);
						EFCommon::$smarty->display('create.tpl');
					} else {				
						EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[1]);
						EFCommon::$smarty->assign('step', 2);
						EFCommon::$smarty->display('create.tpl');
					}
					break;
				case '/event/create/guests':
					$newEvent = isset($_SESSION['newEvent']) ? $_SESSION['newEvent'] : new Event(NULL);
					
					$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
					
					if (!$is_valid) {
						EFCommon::$smarty->assign('error', $newEvent->error);
						
						$this->saveEventFields( $newEvent );
						
						EFCommon::$smarty->assign('step', 2);
						EFCommon::$smarty->display('create.tpl');
					} else {
						EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[2]);
						if (!isset($_SESSION['newEvent'])) {
							$this->makeNewEvent( $newEvent );
						}
						
						if ( isset($_POST['submit']) ) {
							$guest_emails = $_SESSION['newEvent']->submitGuests();
							if ( sizeof($guest_emails) == 0 ) {
								EFCommon::$smarty->assign('error', "No guests added.");
							} else {
								EFCommon::$smarty->assign('notification', "Yay!");
							}
						}
						
						$this->assignInvited($_SESSION['newEvent']->eid);
						$this->assignContacts();
						
						EFCommon::$smarty->assign('finishSubmit', CURHOST.'/event/a/'.
																	$_SESSION['newEvent']->alias.
																	'?created=true');
						EFCommon::$smarty->assign('step', 3);
						EFCommon::$smarty->assign('addButton', true);
						EFCommon::$smarty->assign('event', $_SESSION['newEvent']);
						
						if (isset($_GET['option'])) {
						EFCommon::$smarty->assign('submitTo', CURHOST . "/event/create/guests?eventId=" . 
																$_SESSION['newEvent']->eid . 
																" &option=".$_GET['option']);
						}
						EFCommon::$smarty->display('create_addguest.tpl');
					}
					break;
			}
			
			/* IMPORTANT: WE NEED TO EXIT AFTER USING SUB-CONTROLLERS */
			exit;
		}
		return false;
	}
}