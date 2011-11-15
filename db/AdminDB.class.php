<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/DBConfig.class.php');

class AdminDB extends DBConfig {
	private $adminEmails = array (
		"sergeeva@usc.edu", "anna.b.sergeeva@gmail.com", "anna@truersvp.com", // Anna
		"fxiao05@gmail.com", "fei@truersvp.com", // Fei
		"scott@truersvp.com", "scott@organicstartup.com", // Scott
		"adamrwexler@gmail.com", "adam@truersvp.com", // Adam
		"movingincircles@gmail.com", "nick@truersvp.com", "iwantzcouponz@gmail.com", // Nick
		"laksmono@usc.edu", "grady@truersvp.com", "grady.infolab@gmail.com" // Grady
	);

	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	private function constructExcludeEmails() {
		if (!DEBUG) {
			$excludeParam = " AND ";
			for ($i = 0; $i < sizeof($this->adminEmails); ++$i) {
				if ($i < sizeof($this->adminEmails) - 1) {
					$excludeParam .= "u.email <> '".mysql_real_escape_string($this->adminEmails[$i])."' AND ";
				} else {
					$excludeParam .= "u.email <> '".mysql_real_escape_string($this->adminEmails[$i])."'";
				}
			}
			return $excludeParam;
		}
	}
	
	public function admin_getEventList() {
		$GET_EVENT_LIST = "SELECT e.*, u.fname, u.lname, u.email FROM ef_events e, ef_users u WHERE e.organizer = u.id".$this->constructExcludeEmails();
		return $this->getQueryResultAssoc($GET_EVENT_LIST);
	}
	
	public function admin_getNumInvites($eid) {
		$GET_NUM_INVITES = "SELECT COUNT(*) AS num_invites FROM ef_event_invites i WHERE i.event_id = ".mysql_real_escape_string($eid);
		$num_invites = $this->executeQuery($GET_NUM_INVITES);
		return $num_invites['num_invites'];
	}
	
	public function admin_getNumFbInvites($eid) {
		$GET_NUM_INVITES = "SELECT COUNT(*) AS num_invites FROM fb_invited i WHERE i.event_id = ".mysql_real_escape_string($eid);
		$num_invites = $this->executeQuery($GET_NUM_INVITES);
		return $num_invites['num_invites'];
	}
	
	public function admin_getNumChecked($eid) {
		$GET_CHECKED = "SELECT COUNT(*) AS num_checked FROM ef_attendance WHERE event_id = ".mysql_real_escape_string($eid)." AND is_attending = 1";
		$num_checked = $this->executeQuery($GET_CHECKED);
		return $num_checked['num_checked'];
	}
	
	public function admin_getNumEvents() {
		$GET_NUM_EVENTS = "SELECT COUNT(*) AS num_events FROM ef_events";
		$numEvents = $this->executeQuery($GET_NUM_EVENTS);
		return $numEvents['num_events'];
	}
	
	public function admin_getNumUsers() {
		$GET_NUM_USERS = "SELECT COUNT(*) AS num_users FROM ef_users WHERE fname IS NOT NULL AND lname IS NOT NULL";
		$numUsers = $this->executeQuery($GET_NUM_USERS);
		return $numUsers['num_users'];
	}
	
	public function admin_getTotalNumInvites() {
		$GET_NUM_INVITES = "SELECT COUNT(*) AS num_invites FROM ef_event_invites";
		$numInvites = $this->executeQuery($GET_NUM_INVITES);
		return $numInvites['num_invites'] + $this->admin_getTotalNumFBInvites();
	}
	
	public function admin_getTotalNumFBInvites() {
		$GET_NUM_FB_INVITES = "SELECT COUNT(*) AS fb_invites FROM fb_invited";
		$numInvites = $this->executeQuery($GET_NUM_FB_INVITES);
		return $numInvites['fb_invites'];
	}
}