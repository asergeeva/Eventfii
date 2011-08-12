<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/DBConfig.class.php');

class DBAPI extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function m_updateUserInfo($email,$about,$zip,$cell,$twitter)
	{
		$uid=$_SESSION['uid'];
		$UPDATE_USER_PROFILE="update ef_users set email='$email',about='$about',zip='$zip',phone='$cell',twitter='$twitter' where id=$uid";
		$this->executeUpdateQuery($UPDATE_USER_PROFILE);
		return 'status_updateCompleted';
	}
	
	public function m_checkValidUser($email, $pass) 
	{
		$CHECK_VALID_USER = "SELECT * FROM ef_users e WHERE e.email = '".$email."' AND e.password = '".$pass."'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if (isset($userInfo['id'])) {
			return $userInfo['id'];
		}
		return NULL;
	}
	
	public function m_getEventAttendingBy($uid) 
	{
		$GET_EVENTS = "	SELECT	* 
						FROM (
								SELECT 	e.id, 
										DATEDIFF(e.event_datetime, CURDATE()) AS days_left,
										e.created, 
										e.organizer,
										e.title, 
										e.goal, 
										e.location_address, 
										e.event_datetime, 
										e.event_deadline, 
										e.description, e.is_public 
								FROM 	ef_attendance a, 
										ef_events e 
								WHERE 	a.event_id = e.id AND a.user_id = ".$uid."
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function m_getAttendees($eid) 
	{
		$GET_ATTENDEES = "SELECT DISTINCT e.user_id FROM ef_attendance e WHERE e.event_id = '$eid'";
		return $this->executeQuery($GET_ATTENDEES);
	}
	
	public function m_getGuestListByEvent($eid) 
	{
		$GET_ATTENDEES = "	SELECT	* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	public function m_getUsername($uid)
	{
		$GET_USER_INFO = "SELECT e.fname, e.lname FROM ef_users e WHERE e.id = " . $uid;
		$userInfo = $this->executeValidQuery($GET_USER_INFO);
		return $userInfo;
	}
}