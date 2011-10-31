<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
 
class DBUtil {
	private static $instance;

	private function __construct() {
	
	}
	
	public static function getInstance() {
		if (!isset(self::$sintance)) {
			self::$instance = new DBUtil();
		}
		return self::$instance;
	}
	
	/**
	 * Converts the list of user Array from the DB into list of User object
	 *
	 * @param $dbUser Array the list of users from the DB
	 *
	 * @return Array of the User object
	 */
	public function getUsers($dbUsers) {
		$users = array();
		for ($i = 0; $i < sizeof($dbUsers); ++$i) {
			array_push($users, new User($dbUsers[$i]));
		}
		return $users;
	}
}