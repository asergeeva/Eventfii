<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/AdminDB.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');

class AdminController {
	private $dbCon;
	
	public function __construct() {
		$this->dbCon = new AdminDB();
	}
	
	public function __destruct() {
		
	}
	
	private function displayEvents() {
		EFCommon::$smarty->assign('num_events', $this->dbCon->admin_getNumEvents());	
		EFCommon::$smarty->assign('num_users', $this->dbCon->admin_getNumUsers());	
		EFCommon::$smarty->assign('num_invites', $this->dbCon->admin_getNumInvites());	
		EFCommon::$smarty->assign('events', $this->dbCon->admin_getEventList());
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		$requestUri = '/'.$requestUri[2];
				
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$current_page = substr($requestUri, 0, $getParamStartPos);
			$params = substr($requestUri, $getParamStartPos, strlen($requestUri) - 1 );
		} else {
			$current_page = $requestUri;
		}
		
		switch ($current_page) {
			case '/':
				$this->displayEvents();
				EFCommon::$smarty->display('admin/index.tpl');
				break;
			default:
				EFCommon::$smarty->assign('requestUri', $requestUri);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}