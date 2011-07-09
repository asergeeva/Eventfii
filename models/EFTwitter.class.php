<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */ 
require_once('db/DBConfig.class.php');

class EFTwitter {
	private $dbCon;
	private $PREFIX = "#truersvp";
	
	function __construct() {
		$this->dbCon = new DBConfig();
	}
	
	function __destruct() {
		
	}
	
	/**
	 * Generating the Twitter hash tag given the event ID
	 * $eid Integer the event ID
	 * return String
	 */
	public function getTwitterHash($eid) {
		$eventInfo = $this->dbCon->getEventInfo($eid);
		return $this->PREFIX.$eventInfo['id'];
	}
}