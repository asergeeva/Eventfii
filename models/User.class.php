<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
 
require_once(realpath(dirname(__FILE__)).'/../models/AbstractUser.class.php');

class User extends AbstractUser {
	public $id;
	public $fname;
	public $lname;
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
	public $contacts = array();
	
	public $is_attending;
	
	private $error;
	private $numErrors;
	
	public function __construct($userInfo) {
		if ( $userInfo === NULL ) {
			$this->fname = $_POST['fname'];
			$this->lname = $_POST['lname'];
			$this->email = $_SESSION['user']->email;
			$this->phone = $_POST['phone'];
			$this->zip = $_POST['zip'];
			$this->twitter = $_POST['twitter'];
			$this->facebook = $_SESSION['user']->facebook;
			$this->notif_opt1 = ( isset($_POST['email-feature']) ) ? 1 : 0;
			$this->notif_opt2 = ( isset($_POST['email-updates']) == 1 ) ? 1 : 0;		
			$this->notif_opt3 = ( isset($_POST['email-friend']) == 1 ) ? 1 : 0;
		} else {
			if ( ! is_array($userInfo) ) {
				if ( is_numeric($userInfo) ) {
					$userId = $userInfo;
					$userInfo = EFCommon::$dbCon->getUserInfo($userId);
				} else {
					$userEmail = $userInfo;
					$userInfo = EFCommon::$dbCon->getUserInfoByEmail($userEmail);
				}
				
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
		return EFCommon::$dbCon->getUserInfo($this->uid);
	}
	
	public function updateDb() {
		EFCommon::$dbCon->updateUserInfo( $this->fname, 
										  $this->lname, 
										  $this->email, 
										  $this->phone, 
										  $this->zip, 
										  $this->twitter, 
										  isset($this->notif_opr1) ? 1 : 0, 
										  isset($this->notif_opt2) ? 1 : 0, 
										  isset($this->notif_opt3) ? 1 : 0 );
		$_SESSION['user'] = $this;
	}
	
	private function makeUserFromArray($userInfo) {
		$this->id = $userInfo['id'];
		$this->fname = $userInfo['fname'];
		$this->lname = $userInfo['lname'];
		$this->email = $userInfo['email'];
		$this->about = $userInfo['about'];
		$this->phone = $userInfo['phone'];
		$this->zip = $userInfo['zip'];
		$this->pic = $this->setUserPic($userInfo['facebook']);
		$this->twitter = $userInfo['twitter'];
		$this->facebook = $userInfo['facebook'];
		$this->notif_opt1 = $userInfo['notif_opt1'];
		$this->notif_opt2 = $userInfo['notif_opt2'];
		$this->notif_opt3 = $userInfo['notif_opt3'];
	}
	
	private function setUserPic($facebook = NULL) {
		if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $this->id . ".png") ) {
			return CURHOST . "/upload/user/" . $this->id . ".png";
		} else if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $this->id . ".jpg") ) {
			return CURHOST . "/upload/user/" . $this->id . ".jpg";
		} else if (isset($facebook)) {
			return "http://graph.facebook.com/" . $facebook . "/picture?type=large";
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
	
	private function check_cell() {
		if ( strlen($this->phone) == 0 )
			return;
			
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
		if ( strlen($this->zip) == 0 )
			return;
	
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
		if ( strlen($this->twitter) == 0 )
			return;

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
	
	public function setContacts($contact_email) {
		$contacts = explode(",", $contact_email);
		
		// Reset the errors
		unset($this->error);
		$this->error["add_contact"] = "";
		$this->numErrors = 0;
		
		if (sizeof($contacts) > 1) {
			foreach($contacts as $contact) {
				$contact = trim($contact);
				if ( filter_var($contact, FILTER_VALIDATE_EMAIL) ) {
					$addContacts[] = $contact;
				} else {
					$this->error["add_contact"] .= "<br />" . $contact;
					$this->numErrors++;
				}
			}
		} else {
			$addContacts[] = $contact_email;
		}
		
		if ( isset($addContacts) ) {
			EFCommon::$dbCon->storeContacts($addContacts, $_SESSION['user']->id);
			return sizeof($addContacts);
		} else {
			return 0;
		}
	}
	
	public function setContactsFromCSV($csvFile) {
		$csv_contacts = array();
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
						array_push($csv_contacts, $data[$i]);
						array_push($this->contacts, new AbstractUser($data[$i]));
					}
				}
			}
			fclose($handle);
		}
		EFCommon::$dbCon->storeContacts($csv_contacts, $_SESSION['user']->id);
	}
	
	public function addContacts() {
		$csvFile = USER_CSV_UPLOAD_PATH . '/' . $this->id . '.csv';
		
		$numContacts = 0;
		
		// text area check
		if (trim($_POST['emails']) != "") {
			$numContacts = $this->setContacts($_POST['emails']);		
		// CSV file check
		} else if (file_exists($csvFile)) {
			$this->setContactsFromCSV($csvFile);
		}
		
		if ( $numContacts == 0 ) {
			return "No guests added.";
		} else {
			$plural_contact = ($numContacts == 1) ? "contact" : "contacts";
			return $numContacts . " " . $plural_contact . " added successfully";
		}
	}
}