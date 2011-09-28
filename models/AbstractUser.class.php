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
	public $email;
	public $about;
	public $pic;
	public $twitter;
	public $facebook;
	
	public $verified;
	public $alias;
	
	public $is_attending;
	public $friendly_confidence;
	public $confidence;

	public $cookie;
	public $exists;
	
	protected $error;
	protected $numErrors;
	
	public function __construct($info) {
		$this->numErrors = 0;
		
		// Settings page
		if ( $info === NULL ) {
			$this->id = $_SESSION['user']->id;
			$this->set_fname();
			$this->set_lname();
			$this->email = $_SESSION['user']->email;
			$this->about = $_SESSION['user']->about;
			$this->pic = $_SESSION['user']->pic;
			$this->twitter = $_SESSION['user']->twitter;
			$this->facebook = $_SESSION['user']->facebook;
			$this->alias = $_SESSION['user']->alias;
			$this->verified = 1;
		} else {
			if ( ! is_array($info) ) {
			// Fetch info from database if necessary
				$info = EFCommon::$dbCon->getBasicUserInfo($info);
			}
		
			// Make the user object from the array
			$this->makeUserFromArray($info);
		}
	}
	
	public function __destruct() {
	
	}
	
	protected function makeUserFromArray($userInfo) {	
		$this->id = $userInfo['id'];
		$this->set_fname($userInfo['fname']);
		$this->set_lname($userInfo['lname']);
		$this->email = $userInfo['email'];
		$this->about = $userInfo['about'];
		if ( sizeof($userInfo['facebook']) == 0 ) {
			$this->pic = $this->setUserPic(NULL);
		} else {
			$this->pic = $this->setUserPic($userInfo['facebook']);
		}

		$this->twitter = $userInfo['twitter'];
		$this->facebook = $userInfo['facebook'];
		$this->alias = $userInfo['url_alias'];
		$this->verified = $userInfo['verified'];
		$this->cookie = (isset($userInfo['user_cookie'])) ? $userInfo['user_cookie'] : NULL;
		$this->is_attending = (isset($userInfo['is_attending'])) ? $userInfo['is_attending'] : NULL;
		$this->exists = true;
	}
	
	private function setUserPic($facebook = NULL) {
		if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $this->id . ".png") ) {
			return CURHOST . "/upload/user/" . $this->id . ".png";
		} else if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $this->id . ".jpg") ) {
			return CURHOST . "/upload/user/" . $this->id . ".jpg";
		} else if ( $facebook != NULL ) {
			$imageUrl = "http://graph.facebook.com/" . $facebook . "/picture?type=large";
/*
			$filename = realpath(dirname(__FILE__))."/../upload/fb/".$this->id.".png";
			$imageContent = file_get_contents($imageUrl);
			
			file_put_contents($filename, $imageContent);
			
			EFCommon::resizeImage($filename);
*/
			
			return $imageUrl;
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
	
	protected function set_fname($fname = NULL) {
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
	 				"regexp" => "/^[A-Za-z0-9\s']{2,100}$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_fname ) {
			$this->addError("fname", "Invalid first name");
		}
	}
	
	protected function set_lname($lname = NULL) {
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
	 				"regexp" => "/^[A-Za-z0-9\s']{2,100}$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_lname ) {
			$this->addError("lname", "Invalid last name.");
		}
	}
}