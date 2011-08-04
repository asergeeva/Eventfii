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
	
	public function updateUserInfoMobile($email,$about,$zip,$cell,$twitter)
	{
		$uid=$_SESSION['uid'];
		$UPDATE_USER_PROFILE="update ef_users set email='$email',about='$about',zip='$zip',phone='$cell',twitter='$twitter' where id=$uid";
		$this->executeUpdateQuery($UPDATE_USER_PROFILE);
		return 'status_updateCompleted';
	}
	
	public function checkValidUserMobile($email, $pass) {
		$CHECK_VALID_USER = "SELECT * FROM ef_users e WHERE e.email = '".$email."' AND e.password = '".$pass."'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if (isset($userInfo['id'])) {
			return $userInfo['id'];
		}
		return NULL;
	}
	
	public function getAttendeesMobile($eid) {
		$GET_ATTENDEES = "SELECT DISTINCT e.user_id FROM ef_attendance e WHERE e.event_id = '$eid'";
		return $this->executeQuery($GET_ATTENDEES);
	}
}