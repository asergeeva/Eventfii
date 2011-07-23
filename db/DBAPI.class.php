<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('DBConfig.class.php');

class DBAPI extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function updateUserProfileMobile($email,$about,$zip,$cell,$twitter)
	{
		$uid=$_SESSION['uid'];
		$UPDATE_USER_PROFILE="update ef_users set email='$email',about='$about',zip='$zip',phone='$cell',twitter='$twitter' where id=$uid";
		$this->executeUpdateQuery($UPDATE_USER_PROFILE);
		return 'status_updateCompleted';
	}
}