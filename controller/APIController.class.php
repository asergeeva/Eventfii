<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('../db/DBConfig.class.php');
require_once('../api/CheckInsAPI.class.php');

class APIController {
	private $smarty;
	private $dbCon;
	private $DEBUG = true;
	
	function __construct($smarty) {
		$this->smarty = $smarty;
		$this->dbCon = new DBConfig();
	}
	
	function __destruct() {
		
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		print($requestUri);
		switch ($requestUri) {
			case 'checkins':
				break;
			default:
				break;
		}
	}
}