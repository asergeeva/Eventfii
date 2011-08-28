<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

class AbstractUser {
	// Base user information
	public $id;
	public $fname;
	public $lname;
	public $about;
	public $email;
	public $pic;
	
	public $exists;
	
	public $is_attending;
	public $confidence;
	
	protected $error;
	protected $numErrors;
	
	public function __construct($userInfo) {
		$this->numErrors = 0;
		
		// Settings page
		if ( $userInfo == NULL ) {
			$this->id = $_SESSION['user']->id;
			$this->set_fname();
			$this->set_lname();
			$this->email = $_SESSION['user']->email;
		} else {
			if ( ! is_array($userInfo) ) {
				if ( is_numeric($userInfo) ) {
					$userId = $userInfo;
					$userInfo = EFCommon::$dbCon->getUserInfo($userId);
				} else {
					$userEmail = $userInfo;
					$userInfo = EFCommon::$dbCon->getUserInfoByEmail($userEmail);
				}
			}
			$this->makeUserFromArray($userInfo);
		}
	}
	
	public function __destruct() {
	
	}
	
	protected function makeUserFromArray($userInfo) {
		$this->id = $userInfo['id'];
		$this->set_fname($userInfo['fname']);
		$this->set_lname($userInfo['lname']);
		$this->set_email($userInfo['email']);
		$this->about = $userInfo['about'];
		$this->pic = $this->setUserPic($userInfo['facebook']);
	}
	
	private function setUserPic($facebook = NULL) {
		if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $this->id . ".png") ) {
			return CURHOST . "/upload/user/" . $this->id . ".png";
		} else if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $this->id . ".jpg") ) {
			return CURHOST . "/upload/user/" . $this->id . ".jpg";
		} else if ( isset($facebook) ) {
			return "http://graph.facebook.com/" . $facebook . "/picture?type=large";
		} else {
			return CURHOST . "/images/default_thumb.jpg";
		}
	}
	
	public function get_errors() {		
		// Return if there are any errors
		if ( $this->numErrors == 0 )
			return false;
		else
			return $this->error;
	}
	protected function addError($name, $message) {
		$this->error[$name] = $message;
		$this->numErrors++;
	}
	
	public function set_fname($fname = NULL) {
		if ( $fname == NULL ) {
			if ( isset($_POST['fname']) ) {
				$fname = $_POST['fname']; 
			}
		}
		
		if ( strlen($fname) == 0 ) {
			$this->fname = NULL;
			$this->addError("fname", "Please enter your first name.");
			return;
		}
		
		$this->fname = $fname;
		
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
			$this->addError("fname", "Invalid first name");
		}
	}
	
	public function set_lname($lname = NULL) {
		if ( $lname == NULL ) {
			if ( isset($_POST['lname']) ) {
				$lname = $_POST['lname']; 
			}
		}
		
		if ( strlen($lname) == 0 ) {
			$this->lname = NULL;
			$this->addError("lname", "Please enter your last name.");
			return;
		}
		
		$this->lname = $lname;
		
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
			$this->addError("lname", "Invalid last name.");
		}
	}
	
	protected function set_email($email = NULL) {
		if ( $email == NULL ) {
			if ( isset($_POST['email']) ) {
				$email = $_POST['email']; 
			}
		}
		
		if ( strlen($email) == 0 ) {
			$this->email = NULL;
			$this->addError("lname", "Please enter your email.");
			return;
		}
		
		$this->email = $email;
		
 		$valid_email = filter_var(
	 		$this->email, 
	 		FILTER_VALIDATE_EMAIL
	 	);
	 	
		if( ! $valid_email ) {
			$this->addError("email", "Invalid email.");
		}	
	}
}