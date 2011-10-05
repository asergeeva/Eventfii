<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/DBConfig.class.php');

class DBFB extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function getEventByRequestId($requestId) {
		$GET_EVENT = "SELECT * FROM fb_invited i, ef_events e 
						WHERE i.event_id = e.id AND i.request_id = '".mysql_real_escape_string($requestId)."'";
		return $this->executeQuery($GET_EVENT);
	}
}