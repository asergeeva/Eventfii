<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');

class User {
	public $id;
	public $fname;
	public $lname;
	public $email;
	public $about;
	public $verified;
	public $referrer;
	public $phone;
	public $zip;
	public $pic;
	public $twitter;
	public $facebook;	
	public $notif_opt1;
	public $notif_opt2;
	public $notif_opt3;
	public $exists;
	
	public $is_attending;
	
	private $error;
	private $numErrors;
	
	private $dbCon;
	
	function __construct($userInfo) {
		if ( $userInfo == NULL ) {
			$this->fname = $_POST['fname'];
			$this->lname = $_POST['lname'];
			$this->email = $_POST['email'];
			$this->phone = $_POST['phone'];
			$this->zip = $_POST['zip'];
			$this->twitter = $_POST['twitter'];
			$this->notif_opt1 = ( isset($_POST['email-feature']) ) ? 1 : 0;
			$this->notif_opt2 = ( isset($_POST['email-updates']) ) ? 1 : 0;		
			$this->notif_opt3 = ( isset($_POST['email-friend']) ) ? 1 : 0;
		} else {
			$this->dbCon = new DBConfig();
			if ( ! is_array($userInfo) ) {
				$userId = $userInfo;
				$userInfo = $this->dbCon->getUserInfo($userId);
				
				// Make sure a user was pulled from the db
				if ( ! $userInfo ) {
					$this->exists = false;
				} else {
					$this->exists = true;
				}
			}
			$this->makeUserFromArray($userInfo);
		}
	}
	
	public function __destruct() {
		
	}
	
	private function getUserInfoFromDb() {
		return $this->dbCon->getUserInfo($this->uid);
	}
	
	public function updateDb() {
		$this->dbCon->updateUserInfo( $user->fname, $user->lname, $user->email, $user->phone, $user->zip, $user->twitter, $user->notif_opt1, $user->notif_opt2, $user->notif_opt3 );
	}
	
	private function makeUserFromArray($userInfo) {
		$this->id = $userInfo['id'];
		$this->fname = $userInfo['fname'];
		$this->lname = $userInfo['lname'];
		$this->email = $userInfo['email'];
		$this->about = $userInfo['about'];
		$this->phone = $userInfo['phone'];
		$this->zip = $userInfo['zip'];
		$this->pic = $this->setUserPic();
		$this->twitter = $userInfo['twitter'];
		$this->facebook = $userInfo['facebook'];
		$this->notif_opt1 = $userInfo['notif_opt1'];
		$this->notif_opt2 = $userInfo['notif_opt2'];
		$this->notif_opt3 = $userInfo['notif_opt3'];
	}
	
	private function setUserPic() {
		if ( file_exists("../upload/user/" . $this->id . ".png") ) {
			return CURHOST . "/upload/user/" . $this->id . ".png";
		} else if ( file_exists("../upload/user/" . $this->id . ".jpg") ) {
			return CURHOST . "/upload/user/" . $this->id . ".jpg";
		} else {
			return CURHOST . "/images/default_thumb.jpg";
		}
	}
	
	public function get_errors() {
		// Reset
		$this->numErrors = 0;
		unset($this->error);
	
		// Check for errors
		$this->check_fname();
		$this->check_lname();
		$this->check_email();
		$this->check_cell();
		$this->check_zip();
		$this->check_twitter();
		
		// Return if there are any errors
		if ( $this->numErrors == 0 )
			return false;
		else
			return $this->error;
	}
	
	private function check_fname() {
 		$valid_fname = filter_var(
	 		$this->fname, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^[A-Za-z0-9\s]{2,100}$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_fname ) {
			$this->error['fname'] = "Invalid first name";
			$this->numErrors++;
		}
	}
	
	private function check_lname() {
 		$valid_lname = filter_var(
	 		$this->lname, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^[A-Za-z0-9\s]{2,100}$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_lname ) {
			$this->error['lname'] = "Invalid last name";
			$this->numErrors++;
		}
	}
	
	private function check_email() {
 		$valid_email = filter_var(
	 		$this->email, 
	 		FILTER_VALIDATE_EMAIL
	 	);
	 	
		if( ! $valid_email ) {
			$this->error['email'] = "Invalid email";
			$this->numErrors++;
		}	
	}
	
	private function check_cell() {
 		$valid_cell = filter_var(
	 		$this->phone, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_cell ) {
			$this->error['phone'] = "Phone number is not in valid format";
			$this->numErrors++;
		}	
	}
	
	private function check_zip() {
 		$valid_zip = filter_var(
	 		$this->zip, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^\d{5}(-\d{4})?$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_zip ) {
			$this->error['zip'] = "Please enter a valid zip code";
			$this->numErrors++;
		}
	}
	
	private function check_twitter() {
 		$valid_twitter = filter_var(
	 		$this->twitter, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^[A-Za-z0-9\s]{2,100}$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_twitter ) {
			$this->error['twitter'] = "Please enter a valid twitter username";
			$this->numErrors++;
		}
	}
}