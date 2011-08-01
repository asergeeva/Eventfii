<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('../db/DBAPI.class.php');
require_once('../models/EFCore.class.php');

class APIController {
	private $smarty;
	private $dbCon;
	private $result;
	private $efCore;
	private $DEBUG = true;
	
	public function __construct($smarty) {
		$this->smarty = $smarty;
		$this->dbCon = new DBAPI();
		$this->efCore = new EFCore();
	}
	
	public function __destruct() {
		
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		$requestUri = $requestUri[2];
		
		switch ($requestUri) {
			case 'login':
				if(!isset($_SESSION['uid']))
				{
					if(isset($_REQUEST['email']) && isset($_REQUEST['password']))
					{
						$result = $this->dbCon->checkValidUserMobile($_REQUEST['email'], $_REQUEST['password']);
						if(!isset($result))
						{
							echo 'status_loginFailed';
						}
						else
						{
							$_SESSION['uid'] = $result;
						}
					}
				}
				if(isset($_SESSION['uid']))
				{
					echo 'status_loginSuccess';
				}
				break;
			case 'getUserInfo':
				echo json_encode($this->dbCon->getUserInfo($_SESSION['uid']));
				break;
			case 'setUserInfo':
				echo $this->dbCon->updateUserInfoMobile($_REQUEST['email'],$_REQUEST['about'],$_REQUEST['zip'],$_REQUEST['cell'],$_REQUEST['twitter']);
				break;
			case 'getOrganizerEmail':
				echo json_encode($this->dbCon->getUserInfo($_REQUEST['oid']));
				break;
			case 'getAttendingEvents':
				echo json_encode($this->dbCon->getEventAttendingBy($_SESSION['uid']));
				break;
			case 'setAttendanceForEvent':
				echo json_encode($this->dbCon->eventSignUp($_SESSION['uid'], $_REQUEST['eid'], $_REQUEST['confidence']));
				break;
			case 'getHostingEvents':
				echo json_encode($this->dbCon->getEventByEO($_SESSION['uid']));				
				break;
			case 'getGuestList':
				echo json_encode($this->dbCon->getAttendeesByEvent($_REQUEST['eid']));
				break; 
			case 'checkInByDistance':
				$this->dbCon->checkInGuest('1', $_SESSION['uid'], $_REQUEST['eid']);
				echo 'status_checkInSuccess';
				break;
			case 'checkIn':
				echo json_encode($this->dbCon->checkInGuest($_REQUEST['checkIn'], $_REQUEST['uid'], $_REQUEST['eid']));
				break;
			case 'computeTrueRSVP':
				echo json_encode($this->efCore->computeTrueRSVP($_REQUEST['eid']));
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}