<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/DBConfig.class.php');

class AdminDB extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function admin_getEventList() {
		$GET_EVENT_LIST = "SELECT ev.*, COUNT(i.event_id) AS num_invites FROM
								(SELECT e.id, e.title, e.event_datetime, u.fname, u.lname 
								FROM ef_events e, ef_users u
								WHERE e.organizer = u.id) ev
							LEFT JOIN
								ef_event_invites i
							ON ev.id = i.event_id
							GROUP BY ev.id";
		return $this->getQueryResultAssoc($GET_EVENT_LIST);
	}
	
	public function admin_getNumEvents() {
		$GET_NUM_EVENTS = "SELECT COUNT(*) AS num_events FROM ef_events";
		$numEvents = $this->executeQuery($GET_NUM_EVENTS);
		return $numEvents['num_events'];
	}
	
	public function admin_getNumUsers() {
		$GET_NUM_USERS = "SELECT COUNT(*) FROM ef_users WHERE fname IS NOT NULL AND lname IS NOT NULL";
		return $this->getRowNum($GET_NUM_USERS);
	}
	
	public function admin_getNumInvites() {
		$GET_NUM_INVITES = "SELECT COUNT(*) FROM ef_event_invites";
		return $this->getRowNum($GET_NUM_INVITES);
	}
}