<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../../db/DBFB.class.php');
require_once(realpath(dirname(__FILE__)).'/../../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../../models/Event.class.php');

class FBController {
	public static $fbUserInfo;
	public static $userInfo;
	
	public static $requestIds;
	public static $event;
	public static $dbfb;
	
	public static $fbFriends;
	
	public function __construct() {
	    self::$dbfb = new DBFB();
	    
	    self::$fbUserInfo = EFCommon::$facebook->api('/me');
	    if (isset($_GET['request_id'])) {
	    	self::$requestIds = array($_GET['request_ids']);
	    } else {
	    	self::$requestIds = explode(',', $_GET['request_ids']);
	    }
	    
	    // Create an account for the user
		self::$userInfo = EFCommon::$dbCon->facebookConnect( self::$fbUserInfo['first_name'], 
													   self::$fbUserInfo['last_name'], 
													   self::$fbUserInfo['email'], 
													   self::$fbUserInfo['id'] );

		// Get all of the friends
		self::$fbFriends = EFCommon::$facebook->api('/me/friends');
		self::$fbFriends = self::$fbFriends['data'];
		
		EFCommon::$dbCon->saveFBFriends(self::$fbFriends, self::$userInfo['id']);
	    self::$event = new Event(self::$dbfb->getEventByRequestId(self::$requestIds[0]));
	}
	
	public function __destruct() {
	
	}
	
	public static function deleteRequests() {
	   //for each request_id, build the full_request_id and delete request  
	   foreach (self::$requestIds as $request_id)
	   {
	      $full_request_id = $request_id."_".self::$fbUserInfo['id'];  	  
	      try {
	        $delete_success = EFCommon::$facebook->api("/$full_request_id", 'DELETE');
	        if (DEBUG) {
		        if ($delete_success) {
					echo "Successfully deleted " . $full_request_id. "<br />";
		        } else {
					echo "Delete failed".$full_request_id. "<br />";
		        }
	        }
	      } catch (FacebookApiException $e) {
			// echo "error";
	      }
	    }
	}
	
	public static function renderPage() {
	    EFCommon::$smarty->assign('fbUser', self::$fbUserInfo);
	    EFCommon::$smarty->assign('event', self::$event);
	    
	    EFCommon::$smarty->display('facebook_index.tpl');
	    
	    // Always do this at the end
	    self::deleteRequests();
	}
}