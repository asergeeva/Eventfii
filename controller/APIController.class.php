<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('../db/DBAPI.class.php');

class APIController {
	private $smarty;
	private $dbCon;
	private $DEBUG = true;
	
	public function __construct($smarty) {
		$this->smarty = $smarty;
		$this->dbCon = new DBAPI();
	}
	
	public function __destruct() {
		
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		$requestUri = $requestUri[2];
		
		switch ($requestUri) {
			case 'checkins':
				require_once('../api/models/Checkins.class.php');
				print('Do something!');
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}