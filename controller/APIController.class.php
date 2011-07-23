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
	private $result;
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
			case 'login':
				if(!isset($_SESSION['uid']))
				{
					if(isset($_REQUEST['email']) && isset($_REQUEST['password']))
					{
						$result = $this->dbCon->checkValidUser($_REQUEST['email'], $_REQUEST['password']);
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
			case 'getAttendingEvents':
				echo json_encode($this->dbCon->getEventAttendingBy($_SESSION['uid']));
				break;
			case 'checkins':
				require_once('../api/models/Checkins.class.php');
				print('Do something!');
				break;
			case 'setProfile':
				echo $this->dbCon->updateUserProfileMobile($_REQUEST['email'],$_REQUEST['about'],$_REQUEST['zip'],$_REQUEST['cell'],$_REQUEST['twitter']);
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}