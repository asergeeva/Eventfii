<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/AdminDB.class.php');

class PanelController {
	private $dbConn;
	public function __construct() {
		$this->dbConn = new AdminDB();
	}

	public function __destruct() {

	}
	
	/* function getView
	 * Determines which template files to display
	 * given a certain parameter.
	 *
	 * @param $requestUri | The URI of the current page
	 */
	private function insertEventImages($uid, $eid, $imageName)
	{
		EFCommon::$dbCon->insertEventImages($uid, $eid, $imageName);
	}
	private function getAllEventImages($eid)
	{
		return EFCommon::$dbCon->getAllEventImages($eid);
	}
	private function getAllEventImagesByUser($eid, $uid)
	{
		return EFCommon::$dbCon->getAllEventImages($eid, $uid);
	}
	private function getUsersByImages($eid)
	{
		$users_dropdown = '<select id="sort_by_dropwdown" name="sort_by_dropwdown" onchange="changeImagesView($(\'#sort_by_dropwdown :selected\').val(), $(\'#sort_by_dropwdown :selected\').text());">
							<option value="0">All</option>
							<option value="taken">Time taken</option>
							<option value="added">Date added</option>
				  				<optgroup label="All photos taken by:">';
		$user_ids = EFCommon::$dbCon->getUsersByImages($eid);
		foreach($user_ids as $user_id)
		{
			$name = EFCommon::$dbCon->getUserNameById($user_id['owner_id']);
			$users_dropdown .= '<option value="'.$user_id['owner_id'].'">'.$name.'</option>';
		}
		$users_dropdown .= '</optgroup></select>';
		return $users_dropdown;
	}
	private function getUsersByImagesSelected($eid, $uid)
	{
		$users_dropdown = '<select id="sort_by_dropwdown" name="sort_by_dropwdown" onchange="changeImagesView($(\'#sort_by_dropwdown :selected\').val(), $(\'#sort_by_dropwdown :selected\').text());"><option value="0">All</option>';
		if($uid == 'taken')
		{
			
			$users_dropdown .= '<option value="taken" selected="selected">Time taken</option>';
		}else
		{
			$users_dropdown .= '<option value="taken">Time taken</option>';
		}
		if($uid == 'added')
		{
			$users_dropdown .= '<option value="added" selected="selected">Date added</option>';
		}
		else
		{
			$users_dropdown .= '<option value="added">Date added</option>';
		}
		$users_dropdown .= '<optgroup label="All photos taken by:">';
		$user_ids = EFCommon::$dbCon->getUsersByImages($eid);
		foreach($user_ids as $user_id)
		{
			$selected = '';
			$name = EFCommon::$dbCon->getUserNameById($user_id['owner_id']);
			if($user_id['owner_id'] == $uid)
			{
				$selected = " selected='selected'";	
			}
			$users_dropdown .= '<option value="'.$user_id['owner_id'].'" '.$selected.'>'.$name.'</option>';
		}
		$users_dropdown .= '</optgroup></select>';
		return $users_dropdown;
	}
	/*Function for checking the event owner*/
	private function checkEventOwner($uid, $eid)
	{
		return $this->dbConn->checkEventOwner($uid, $eid);
	}
	
	/*Check whether email already exist*/
	private function checkGuestEmail($email)
	{
		return EFCommon::$dbCon->isUserEmailExist($email);
	}
	
	private function get_file_extension($file_name)
	{
			  return substr(strrchr($file_name,'.'),1);
	}
	 private function displayStock()
	{
		$stockList = $this->dbConn->admin_getStockList();
		$stockPhotosCount = array();
		EFCommon::$smarty->assign('stock', $stockList);
	}
	private function getEventImage($eid)
	{
		return $image = $this->dbConn->admin_getEventImage($eid);
		exit;
	}
	public function getView($uri) {
		EFCommon::$smarty->assign("current_page", $uri);
		$requestUri = str_replace(PATH, '', $uri);
		// Check for cookie
		if (isset($_COOKIE[USER_COOKIE])) {
			$userCookie = EFCommon::$dbCon->getUserByCookie($_COOKIE[USER_COOKIE]);
			if (isset($userCookie)) {
				$_SESION['user'] = new User($userCookie);
			}
		}
		
		// Remove GET parameters
		// We need to use $current_page instead of $requestUri
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$current_page = substr($requestUri, 0, $getParamStartPos);
			$params = substr($requestUri, $getParamStartPos, strlen($requestUri) - 1 );
		} else {
			$current_page = $requestUri;
		}
		
		// Security validation
		if (!$this->securityValidate($current_page)) {
			return;
		}
		
		$this->checkAPIEntry($current_page);
		
		/* Check whether we're on the event profile page */
		$eventController = new EventController();
		$eventController->getView($current_page);
		
		// Quick check for permissions for editing events
		if ( preg_match("/event\/manage*/", $current_page) > 0 ) {
			if ( ! isset ( $_REQUEST['eventId'] ) ) {
				EFCommon::$smarty->display('error.tpl');
				return;
			}
			$eventId = $_REQUEST['eventId'];
			if ( ! isset ( $_SESSION['user'] ) ) {
				if ( $eventId ) {
					header("Location: " . CURHOST . "/login?redirect=manage&eventId=" . $eid);
				} else {
					header("Location: " . CURHOST . "/login");
				}
				exit;
			}
			
			// Fetch the event information if necessary
			$this->buildEvent($eventId, true);
			
			$page['manage'] = true;
			EFCommon::$smarty->assign('page', $page);
		}
		
		// If the user has an alias URL
		if (preg_match("/user\/a\/.*/", $current_page) > 0) {
			$alias = $this->getAliasByUri($current_page);
			if ( ! $alias ) {
				EFCommon::$smarty->display( 'error.tpl' );
				return;
			}
			$userDb = EFCommon::$dbCon->getUserByURIAlias($alias);
			$this->displayUserById($userDb['id']);
			return;
		}
		
		// User public profile page
		if (preg_match("/user\/\d+/", $current_page) > 0) {
			$userId = $this->getUserIdByUri( $current_page );
			$this->displayUserById($userId);			
			return;
		}
		
		// Check if there's a create event
		$createController = new CreateController();
		$createController->getView($current_page);
		switch ($current_page) {
			case '/':
			case '/home':
				if (isset($_SESSION['isNewUser'])) {
					EFCommon::$smarty->assign('isNewUser', true);
					unset($_SESSION['isNewUser']);
				}
				if (isset($_SESSION['user'])) {
					$page['cp'] = true;
					EFCommon::$smarty->assign('page', $page);

					// Check if there's a new event session
					// create that event if the session exist
					if ( isset($_SESSION['newEvent']) && ! isset($_SESSION['newEvent']->eid) ) {
						if ( $this->validateEventInfo( $_SESSION['newEvent'] )) {
							$newEvent = $_SESSION['newEvent'];
							$newEvent->organizer = $_SESSION['user'];
							$this->makeNewEvent( $newEvent );
							header("Location: " . CURHOST . "/event/create/invite");
							exit;
						}
					}
			
					unset($_SESSION['newEvent']);
					unset($_SESSION['new_eid']);
					unset($_SESSION['manage_event']);
					unset($_SESSION['contact_form']);
					unset($_SESSION['gref']);
					unset($_SESSION['eventViewed']);
					
					$this->assignCPEvents($_SESSION['user']->id);
					
					EFCommon::$smarty->display('cp.tpl');
				} else {
					EFCommon::$smarty->display('index.tpl');
				}
				break;
			case '/upload/event/images':
				if (!empty($_FILES))
				{
					$file		=  $_FILES['Filedata'];
					$tmp_name	=  $file['tmp_name'];
					$filename   =  $file['name'];
					//$ext = $this->get_file_extension($filename);
					$uid		=  $_REQUEST['uid'];
					$eid		=  $_REQUEST['eid'];
					$uploadPath = './eventimages/';
					$uploadPathThumb = './eventimages/thumbs/';
					$upfile  = $uid.'_'.$eid.'_'.str_replace(" ", "_", $filename);
					move_uploaded_file($tmp_name,$uploadPath.$upfile);
					$this->insertEventImages($uid, $eid, $upfile);
					@chmod($uploadPath.$upfile, 0777);
					/*Image Resize CI Library*/			
					include("./libs/image_resize/resize_class.php");
					$image_resize = new CI_Image_lib();
					$source_image = $upfile;
					$medium_image = $upfile;
					$config['source_image'] = $uploadPath.$source_image;
					$config['new_image'] = $uploadPath.$medium_image;
					$config['quality'] = '80';
					$width = '540';
					$height = '540';
					if(file_exists($uploadPath.$source_image))
					{
						list($awidth, $aheight) = getimagesize($uploadPath.$source_image);
						if($awidth < $width)
						{
							$width = $awidth;
						}
					}
					$config['width'] = $width;
					$config['height'] = $height;
					$config['maintain_ratio'] = TRUE;
					$config['image_library'] = 'gd2';
					$image_resize->initialize($config); 
					if ( ! $image_resize->resize())
					{								
					}
					@chmod($uploadPath.$upfile, 0777);
					$source_image = $upfile;
					$medium_image = $upfile;
					$config['source_image'] = $uploadPath.$source_image;
					$config['new_image'] = $uploadPathThumb.$medium_image;
					$config['quality'] = '80';
					$width = '150';
					$height = '150';
					if(file_exists($uploadPath.$source_image))
					{
						list($awidth, $aheight) = getimagesize($uploadPath.$source_image);
						if($awidth < $width)
						{
							$width = $awidth;
						}
					}
					$config['width'] = $width;
					$config['height'] = $height;
					$config['maintain_ratio'] = TRUE;
					$config['image_library'] = 'gd2';
					$image_resize->initialize($config); 
					if ( ! $image_resize->resize())
					{								
					}
					@chmod($uploadPathThumb.$source_image, 0777);
					echo $upfile;
					//upload Here	
				}
				break;
			case '/event/reloadImages':
				$allImages = $this->getAllEventImages($_POST['eid']);
				$users_dropdown = $this->getUsersByImages($_POST['eid']);
				$event_name = EFCommon::$dbCon->getEventName($_POST['eid']);
				$event = $this->buildEvent($_POST['eid']);
				$html = '';
				$slideshow = '';
				$count_images = 0;
				foreach($allImages as $image)
				{
					$count_images++;
					if($slideshow == '')
					{
						$slideshow = CURHOST.'/eventimages/'.$image['image'];
					}
					$name = EFCommon::$dbCon->getUserNameById($image['owner_id']);
					$html .= '<span><a href="'.CURHOST.'/eventimages/'.$image['image'].'" class="fancybox-buttons" data-fancybox-group="button"><img src="'.CURHOST.'/eventimages/thumbs/'.$image['image'].'" /></a>';
					$html .= '<div class="show_links"></div>';
                    $html .= '<div class="show_links_up lnk_blu">';
                    $html .= '<div class="fl" style="max-width:80px;">by <span id="by_name_'.$count_images.'">'.$name.'</span><a href="javascript:void(0);" onclick="changeImagesView('.$image['owner_id'].', \''.$name.'\')">See all</a></div>';
                    $html .= '<div class="fr" style="text-align:right;">Share <img src="'.CURHOST.'/images/connect_favicon.png" class="fb_share" onclick="streamPublish(\''.CURHOST.'/event/a/'.($event->alias).'\', \'Check out this photo from '.$event_name.'\', \''.CURHOST.'/eventimages/'.$image['image'].'\');" /><a href="'.CURHOST.'/event/imageDownload/?file='.$image['image'].'">Download</a>';
					if(isset($_SESSION['user']) && $this->checkEventOwner($_SESSION['user']->id, $_POST['eid']))
					{
						$html .= '<a href="javascript:void(0);" onclick="deletePhoto('.$image['id'].');">Delete Photo</a>';
					}
					$html .= '</div>';
                    $html .= '</div>';
					$html .= '</span>';
				}
				echo json_encode(array('html'=>$html, 'slideshow'=>$slideshow, 'count_images'=>$count_images, 'users_dropdown'=>$users_dropdown));
				break;
			case '/event/reloadImagesByUser':
				$allImages = $this->getAllEventImagesByUser($_POST['eid'], $_POST['uid']);
				$users_dropdown = $this->getUsersByImagesSelected($_POST['eid'], $_POST['uid']);
				$event_name = EFCommon::$dbCon->getEventName($_POST['eid']);
				$event_name = EFCommon::$dbCon->getEventName($_POST['eid']);
				$html = '';
				$slideshow = '';
				$count_images = 0;
				foreach($allImages as $image)
				{
					$count_images++;
					if($slideshow == '')
					{
						$slideshow = CURHOST.'/eventimages/'.$image['image'];
					}
					$name = EFCommon::$dbCon->getUserNameById($image['owner_id']);
					$html .= '<span><a href="'.CURHOST.'/eventimages/'.$image['image'].'" class="fancybox-buttons" data-fancybox-group="button"><img src="'.CURHOST.'/eventimages/thumbs/'.$image['image'].'" /></a>';
					$html .= '<div class="show_links"></div>';
                    $html .= '<div class="show_links_up lnk_blu">';
                    $html .= '<div class="fl" style="max-width:80px;">by <span id="by_name_'.$count_images.'">'.$name.'</span><a href="javascript:void(0);" onclick="changeImagesView('.$image['owner_id'].', \''.$name.'\')">See all</a></div>';
                    $html .= '<div class="fr" style="text-align:right;">Share <img src="'.CURHOST.'/images/connect_favicon.png" class="fb_share" onclick="streamPublish(\''.CURHOST.'/event/a/'.($event->alias).'\', \'Check out this photo from '.$event_name.'\'\''.CURHOST.'/eventimages/'.$image['image'].'\');" /><a href="'.CURHOST.'/event/imageDownload/?file='.$image['image'].'">Download</a>';
					if(isset($_SESSION['user']) && $this->checkEventOwner($_SESSION['user']->id, $_POST['eid']))
					{
						$html .= '<a href="javascript:void(0);" onclick="deletePhoto('.$image['id'].');">Delete Photo</a>';
					}
					$html .= '</div>';
                    $html .= '</div>';
					$html .= '</span>';
				}
				echo json_encode(array('html'=>$html, 'slideshow'=>$slideshow, 'count_images'=>$count_images, 'users_dropdown'=>$users_dropdown));
				break;
			case '/event/getJsonArray':
				echo json_encode(array('val'=>$_POST));
				break;
			case '/event/imageDownload/':
					$file = CURHOST.'/eventimages/'.$_GET['file'];
					header('Content-Description: File Transfer');
					header("Content-type: image/jpg");
					header("Content-disposition: attachment; filename= ".$_GET['file']."");
					readfile($file);
				break;
			case '/event/addGuest':
				$event_id = $_POST['event_id'];
				$guest_fname = $_POST['guest_fname'];
				$guest_lname = $_POST['guest_lname'];
				$guest_email = $_POST['guest_email'];
				$event = $this->buildEvent($event_id);
				$exist = $this->checkGuestEmail($guest_email);
				if($exist)
				{
					$userInfo = EFCommon::$dbCon->getUserInfoByEmail($guest_email);
				  EFCommon::$dbCon->updateUserInfoAttend($guest_fname, $guest_lname, $userInfo['id']);
					$userBuilded = new User($userInfo);
					$recordAttendance = EFCommon::$dbCon->eventSignUpWithOutEmail($userInfo['id'], $event, 90, 0, 1);
					EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $userBuilded, $event, $subjectLine);
				}else
				{
					$userInfo = EFCommon::$dbCon->createNewUser($guest_fname, $guest_lname, $guest_email);
					$userBuilded = new User($userInfo);
					$recordAttendance = EFCommon::$dbCon->eventSignUpWithOutEmail($userInfo['id'], $event, 90, 0, 1);
					EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $userBuilded, $event, 'Thank you for RSVPing to {Event name}');
				}
				echo 'ok';
				break;
			case '/event/markChecked':
				$eid = $_POST['eid'];
				$uid = $_POST['uid'];
				EFCommon::$dbCon->markChecked($eid, $uid);
				echo 'ok';
				break;
			case '/event/unmarkChecked':
				$eid = $_POST['eid'];
				$uid = $_POST['uid'];
				EFCommon::$dbCon->unmarkChecked($eid, $uid);
				echo 'ok';
				break;
			case '/event/delImage/':
				 EFCommon::$dbCon->delImage($_POST['delId']);
				 echo '1';
				break;
			case '/demo':
				header("Location: ".EVENT_URL."/a/1af");
				break;
			case '/sw':
				header("Location: ".EVENT_URL."/a/1dc");
				break;
			case '/media':
				EFCommon::$smarty->display('static_media.tpl');
				break;
			case '/terms':
				EFCommon::$smarty->display('static_terms.tpl');
				break;
			case '/privacy':
				EFCommon::$smarty->display('static_privacy.tpl');
				break;
			case '/faq':
				EFCommon::$smarty->display('static_faq.tpl');
				break;
			case '/info':
				phpinfo();
				break;
			case '/contact':
				// if the form's been submitted, send its contents
				if ( isset($_POST['submit']) && ! isset($_GET['success']) ) {
					
					// Validate input
					$error = NULL;
					
					// Check user credentials
					if ( ! isset($_SESSION['user']) ) {
						if ( ! isset($_POST['email']) || $_POST['email'] === '' ) {
							$error['email'] = 'You must enter an email address in this field so we can get back to you!';
						} else if ( !preg_match('/^[A-Za-z0-9._%+]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,5}$/', $_POST['email']) ){
							$error['email'] = 'The email address you entered appears to be invalid!';
						}	
					}
					
					// Verify message was entered
					if ( ! isset($_POST['message']) || $_POST['message'] === '' ) {
						$error['message'] = 'You must enter a message!';
					}

					// Assign errors
					
					
					// at this point we know whether the input is valid or not
					if ( $error === NULL ){
						$subject = ( isset($_POST['subject']) ) ? $_POST['subject'] : "";
						$rawMime = "X-Mailgun-Tag: truersvp\n" . 
								   "Content-Type: plaintext;charset=UTF-8\n" . 
								   "From: " . $_POST['name'] . "<" . $_POST['email'] . ">\n" . 
								   "To: support@truersvp.com\n" . 
								   "Subject: [trueRSVP Support] " . $subject . "\n\n". $_POST['message'];
						MailgunMessage::send_raw($_POST['email'], 'support@truersvp.com', $rawMime);

						// let's thank the user for contacting us
						header("Location: " . CURHOST . "/contact?success=true");
					} else {
						EFCommon::$smarty->assign('error', $error);
					}
				} else if ( isset($_GET['success']) && $_GET['success'] == true ) {
					EFCommon::$smarty->assign('notification', 'Thank you for your feedback!');
				}
				
				EFCommon::$smarty->display('static_contact.tpl');
				
				break;
			case '/share':
				EFCommon::$smarty->display('static_share.tpl');
				break;
			case '/method':
				EFCommon::$smarty->display('static_method.tpl');
				break;
			case '/feedback/send':
				EFCommon::$mailer->sendFeedback();
				break;
			case '/contacts':
				$page['contacts'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				$contacts = array();
				$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
				for ($i = 0; $i < sizeof($contactList); ++$i) {
					$contact = new User($contactList[$i]);
					array_push($contacts, $contact);
				}
				
				if ( sizeof($contacts) > 0 )
					EFCommon::$smarty->assign('contacts', $contacts);
				else
					EFCommon::$smarty->assign('contacts', NULL);
				
				EFCommon::$smarty->display('cp_contacts.tpl');
				break;
			case '/contacts/add':
				$page['addcontacts'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if (isset($_POST['submit'])) {
					$_SESSION['user']->addContacts();
				}
				
				// EFCommon::$smarty->assign('fbSubmit', CURHOST . '/contacts/add?option=fb&gref=' . $event->global_ref);
				EFCommon::$smarty->assign('submitTo', CURHOST . '/contacts/add');
				EFCommon::$smarty->display('cp_contacts.tpl');
				break;
			case '/settings':
				if ( ! isset($_SESSION['user']) ) {
					header("Location: " . CURHOST);
					exit;
				} 
				
				$page['settings'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if ( isset($_POST['submit']) ) {
					$responseMsg = array();
					
					$user = new User(NULL);
					$error = $user->get_errors();
					
					// UPDATE DB
					if ( ! $error ) {
						$notification = "User settings updated successfully.";
						EFCommon::$smarty->assign('notification', $notification);
					} else {
						EFCommon::$smarty->assign("error", $error);
					}
					
					// RESET PASSWORD
					if ( $_REQUEST['user-curpass'] != '' || $_REQUEST['user-newpass'] != '' || $_REQUEST['user-confpass'] != '' ) {
						if ( EFCommon::$dbCon->resetPassword( md5($_REQUEST['user-curpass']), md5($_REQUEST['user-newpass']), md5($_REQUEST['user-confpass']) )) {
							$responseMsg['password_success'] = 'Password has been updated';
						} else {
							$responseMsg['password_error'] = 'Invalid password';
						}
					}
					EFCommon::$smarty->assign('responseMsg', $responseMsg);
				}
				
				EFCommon::$smarty->display('cp_settings.tpl');
				break;
			case '/settings/email/update':
				$secondaryEmail = $_POST['email'];
				$secondaryEmailId = $_POST['id'];
				
				if (filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
					if (EFCommon::$dbCon->saveSecondaryEmail($secondaryEmail, $secondaryEmailId)) {
						print(true);
					}	
					print(false);
				}
				print(false);
				break;
			case '/event/manage/cancel':
				if (EFcommon::$dbCon->deleteEvent($_POST['eventId'])) {
					$event = $this->buildEvent($_POST['eventId']);
					
					EFCommon::$mailer->sendGuestsHtmlEmailByEvent('cancel', $event, $event->title.' was canceled');
					
					print("Event is successfully deleted");
					// Add successful template for event cancellation
					break;
				}
				// Add an error template for the invalid host
				print("You are not the host for this event");
				break;
			case '/event/image/upload':
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");

				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/images/', TRUE);
				// to pass data through iframe you will need to encode all html tags
				
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/event/csv/upload':
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("csv");

				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/csv/', TRUE);
				
				// Handle the create event
				$event = NULL;
				if (isset($_SESSION['newEvent'])) {
					$event = $_SESSION['newEvent'];
				// Handle the manage event
				} else if (isset($_SESSION['manage_event'])) {
					$event = $_SESSION['manage_event'];
				}
				
				if (isset($event)) {
					$contactList = EFCommon::getContactsFromCSV(CSV_UPLOAD_PATH.'/'.$result['filename']);
					$csvList = array();
					for ($i = 0; $i < sizeof($contactList); ++$i) {
						if (trim($contactList[$i]) != '') { 
							$csvList[$contactList[$i]] = '';
						}
					}
					
					if (sizeof($csvList) > 0) {
						EFCommon::$smarty->assign('contactList', $csvList);
						EFCommon::$smarty->display('import_contact_list.tpl');
					} else {
						print(false);
					}
				}
				break;
			case '/user/follow':
				if ($_SESSION['user']->id != $_POST['fid']) {
					print(EFCommon::$dbCon->followUser($_SESSION['user']->id, $_POST['fid']));
				} else {
					print(0);
				}
				break;
			case '/event/attend':
				$event = new Event($_POST['eid']);
				$event->currentUserAttend($_POST['conf']);
				break;
			case '/event/attend/attempt':
				unset($_SESSION['attemptValue']);
				$_SESSION['attemptValue'] = array($_POST['eid'] => $_POST['conf']);
				break;
			case '/event/checkin':
				$isAttend = 1;
				if ($_REQUEST['checkin'] == 'false') {
					$isAttend = 0;
				}
				EFCommon::$dbCon->checkInGuest( $isAttend, $_REQUEST['guestId'], $_REQUEST['eventId'] );
				break;
			case '/event/print':
				$this->printEvent();
				break;
			case '/event/manage':
				$page['cp'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				// $this->buildEvent( $_GET['eventId'], true );
				
				$this->assignManageVars( $_GET['eventId'] );
				
				EFCommon::$smarty->display('manage.tpl');
				break;
			case '/event/manage/attendees':
				$page['attendeelist'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
			
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($_GET['eventId']);
				$editEvent = $this->buildEvent($_GET['eventId'], true);
				
				$eventAttendees = array();
				$noResponseAttendees = array();
				$eventReferred = array();
				
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$countAttendees = EFCommon::$dbCon->getAttendeesByEventAndUser($_GET['eventId'],$attendees[$i]['user_id'] );
					$attendee = new User($attendees[$i]);
					
					$attendee->friendly_confidence = EFCommon::$confidenceMap[$attendees[$i]['confidence']];
					$attendee->confidence = $attendees[$i]['confidence'];
					
					if ($attendee->confidence == CONFELSE) {
						$noResponseAttendees[] = $attendee;
					} else {
						$eventReferred[] = $countAttendees;
						$eventAttendees[] = $attendee;
					}
				}
				
				EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
				EFCommon::$smarty->assign('eventReferred', $eventReferred);
				EFCommon::$smarty->assign('event', $editEvent);
				EFCommon::$smarty->assign('noResponseAttendees', $noResponseAttendees);
				EFCommon::$smarty->display('manage_attendees.tpl');
				break;
			case '/event/manage/checkin':
				$page['checkin'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				EFCommon::$smarty->display('manage_checkin.tpl');
				break;
			case '/event/manage/confirm':
				$page['confirm'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
			
				$attendees = EFCommon::$dbCon->getAttendeesByEvent($_GET['eventId']);
				$eventAttendees = array();
				$noResponseAttendees = array();
				
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$attendee = new User($attendees[$i]);
					
					$attendee->friendly_confidence = EFCommon::$confidenceMap[$attendees[$i]['confidence']];
					$attendee->confidence = $attendees[$i]['confidence'];
					
					if ($attendee->confidence == CONFELSE) {
						$noResponseAttendees[] = $attendee;
					} else {
						$eventAttendees[] = $attendee;
					}
				}
				
				EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
				EFCommon::$smarty->assign('noResponseAttendees', $noResponseAttendees);
				EFCommon::$smarty->display('manage_attendees.tpl');
				break;
			case '/event/manage/edit':
				$page['edit'] = true;
				EFCommon::$smarty->assign('page', $page);
				
				if ( ! isset ($_POST['submit']) ) {
					$editEvent = $this->buildEvent($_GET['eventId'], true);
					$this->saveEventFields( $editEvent );
					$this->displayStock();
					EFCommon::$smarty->assign('eventid', json_encode(array('eid'=>$_GET['eventId'])));
					EFCommon::$smarty->assign('event_image', $this->getEventImage($_GET['eventId']));
					EFCommon::$smarty->display('manage_edit.tpl');
					break;
				}
				
				// Fill in event information
				$editEvent = new Event(NULL);
				$editEvent->eid = $_GET['eventId'];
				
				// Check to see if the new event information is valid.
				if ( $this->validateEventInfo( $editEvent ) === true ) {
					EFCommon::$dbCon->updateEvent( $editEvent );
					$_SESSION['manage_event'] = new Event($editEvent->eid);
					EFCommon::$smarty->assign("saved", true);
				}
				$this->displayStock();
				EFCommon::$smarty->assign('eventid', json_encode(array('eid'=>$_GET['eventId'])));
				$this->saveEventFields( $editEvent );
				EFCommon::$smarty->assign('event_image', $this->getEventImage($_GET['eventId']));
				EFCommon::$smarty->display('manage_edit.tpl');
				break;
			case '/event/manage/guests':
				$page['addguests'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				$event = $this->buildEvent( $_GET['eventId'], true );
				EFCommon::$smarty->assign("event", $event);
				
				if ( $_SESSION['manage_event']->days_left < 0 ) {
					EFCommon::$smarty->display("error_event_passed.tpl");
					break;
				}
				
				if ( isset($_POST['submit']) ) {
					$message = $_SESSION['manage_event']->submitGuests();
					EFCommon::$smarty->assign("message", $message);
				}
				
				$this->assignContacts();
				$this->assignInvited($event->eid);
				
				if( $_SESSION['manage_event']->numErrors > 0 ) {
					EFcommon::$smarty->assign( 'error', $event->error );
				}
				
				$optionParam = "";
				if (isset($_GET['option'])) {
					$optionParam = '&option='.$_GET['option'];
				}
				
				EFCommon::$smarty->assign('finishSubmit', CURHOST.$current_page.'?eventId='.$_SESSION['manage_event']->eid.$optionParam);
				EFCommon::$smarty->assign('submitTo', CURHOST.$current_page.'?eventId='.$_SESSION['manage_event']->eid.$optionParam);
				
				// The FB ID's that is being invited by the user
				if (isset($_REQUEST['ids']) && sizeof($_REQUEST['ids']) > 0) {
					foreach ($_REQUEST['ids'] as $fbid) {
						EFCommon::$dbCon->inviteUserFB($_SESSION['user']->id, $fbid, $_SESSION['manage_event']->eid);
					}
				}
				
				EFCommon::$smarty->assign('fbSubmit', CURHOST.'/event/manage/guests?eventId='.$_SESSION['manage_event']->eid."&option=fb&gref=".$event->global_ref);
				EFCommon::$smarty->assign('addButton', true);
				EFCommon::$smarty->display('manage_guests.tpl');
				break;
			case '/guest/inviter':			
				$inviter = new OpenInviter();
				$oi_services = $inviter->getPlugins();

				if ( isset( $_REQUEST['oi_email'] ) && isset( $_REQUEST['oi_pass'] ) ) {
					$inviter->startPlugin($_REQUEST['oi_provider']);
					$internal = $inviter->getInternalError();
					if ( $internal && DEBUG ) {
						print($internal);
					}
					$inviter->login( $_REQUEST['oi_email'], $_REQUEST['oi_pass'] );

					if (isset($inviter->plugin)) {
						$_POST['oi_session_id'] = $inviter->plugin->getSessionID();
						$contactList = $inviter->getMyContacts();
						// record sort by Alphabetical
						asort($contactList);
						if ($contactList) {
							EFCommon::$smarty->assign('contactList', $contactList);
							// variables used for search or filter record
							EFCommon::$smarty->assign('oi_email', $_REQUEST['oi_email']);
							EFCommon::$smarty->assign('oi_pass', $_REQUEST['oi_pass']);
							EFCommon::$smarty->assign('oi_provider', $_REQUEST['oi_provider']);
							EFCommon::$smarty->display('import_contact_list.tpl');
						} else {
							print("Invalid username/password");
						}
					}
				} else {
					$contacts = array();
					$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
					for ($i = 0; $i < sizeof($contactList); ++$i) {
						$contact = new User($contactList[$i]);
						$contacts[] = $contact;
					}
					
					if ( sizeof($contacts) > 0 )
						EFCommon::$smarty->assign('contacts', $contacts);
					else
						EFCommon::$smarty->assign('contacts', NULL);
					
					EFCommon::$smarty->assign('addButton', true);
					EFCommon::$smarty->display('block_contacts.tpl');
				}
				break;
				
			case '/guest/inviterFilter':			
				// case used when we filter record
				$inviter = new OpenInviter();
				$oi_services = $inviter->getPlugins();
				if ( isset( $_REQUEST['oi_email'] ) && isset( $_REQUEST['oi_pass'] ) ) {
					$inviter->startPlugin($_REQUEST['oi_provider']);
					$internal = $inviter->getInternalError();
					if ( $internal && DEBUG ) {
						print($internal);
					}
					$inviter->login( $_REQUEST['oi_email'], $_REQUEST['oi_pass'] );
					if (isset($inviter->plugin)) {
						$_POST['oi_session_id'] = $inviter->plugin->getSessionID();
						$contactList = $inviter->getMyContacts();
						$oi_filter = $_REQUEST['oi_filter'];
						$lenth = strlen($oi_filter);
						$filterRecord = array();
						// apply filter
						foreach($contactList as $key => $values)
						{
							if(substr($values,0,$lenth)==$oi_filter)
							{
								$filterRecord[$key] = $values;
							}elseif(substr($key,0,$lenth)==$oi_filter)
							{
								$filterRecord[$key] = $values;
							}
						}
						// genrate new filtered record
					  $contactList = $filterRecord;
						// reocrd sort
						asort($contactList);
						EFCommon::$smarty->assign('contactList', $contactList);
						EFCommon::$smarty->assign('oi_email', $_REQUEST['oi_email']);
						EFCommon::$smarty->assign('oi_pass', $_REQUEST['oi_pass']);
						EFCommon::$smarty->assign('oi_provider', $_REQUEST['oi_provider']);
						EFCommon::$smarty->assign('oi_filter', $_REQUEST['oi_filter']);
						EFCommon::$smarty->display('import_contact_list.tpl');
					}
				} else {
					$contacts = array();
					$contactList = EFCommon::$dbCon->getUserContacts($_SESSION['user']->id);
					for ($i = 0; $i < sizeof($contactList); ++$i) {
						$contact = new User($contactList[$i]);
						$contacts[] = $contact;
					}
					
					if ( sizeof($contacts) > 0 )
						EFCommon::$smarty->assign('contacts', $contacts);
					else
						EFCommon::$smarty->assign('contacts', NULL);
					
					EFCommon::$smarty->assign('addButton', true);
					EFCommon::$smarty->display('block_contacts.tpl');
				}
				break;	
				
			case '/event/manage/email':
				$page['email'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				if ( ! isset($_GET['create']) || ! $_GET['create'] ) {
					EFCommon::$smarty->display('manage_email.tpl');
				} else {
					EFCommon::$smarty->display('manage_email_create.tpl');
				}
				
				break;
			case '/event/email/send':
				$event = $_SESSION['manage_event'];
				// Determine whether the auto reminder is activated
				$autoReminder = 0;
				if (isset($_REQUEST['autoReminder']) && $_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				$reminderTime = isset($_POST['reminderTime']) ? $_POST['reminderTime'] : NULL;
				$reminderDate = isset($_POST['reminderDate']) ? $_POST['reminderDate'] : NULL;
				
				// Validation
				$email = new EFEmailMessage($_POST['reminderSubject'],
									   		$_POST['reminderContent'],
									   		$reminderTime,
									   		$reminderDate,
									   		$_POST['reminderRecipient']);
				
				if($email->get_errors($autoReminder)) {
					die($email->print_errors());
				}
				
				// If the auto reminder is enabled, we will only save it (sending it through cron job)
				if ($autoReminder == 1) {
					$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
					$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
					EFCommon::$dbCon->saveEmail( $_POST['eid'], 
												 $_POST['reminderContent'], 
												 $dateTime, 
												 $_POST['reminderSubject'], 
												 $autoReminder, 
												 $_POST['reminderRecipient'],
												 $_POST['isFollowup'] );
					echo("Saved");
					return;
				}
				
				// If the auto reminder is not enabled, we will send it right away
				$guests = array();
				switch ($_POST['reminderRecipient']) {
					case 1:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuests($_POST['eid']));
						break;
					case 2:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT1));
						break;
					case 3:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT2));											$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT3));
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT4));
						break;
					case 4:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT5));
						break;
					case 5:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFELSE));
						break;
				}
				
				// Send email to all of the guests specified
				for ($i = 0; $i < sizeof($guests); ++$i) {
					EFCommon::$mailer->sendHtmlEmail('general', $guests[$i], $_POST['reminderSubject'], $event, $_POST['reminderContent']);
				}
				echo("Sent");
				break;
			case '/event/manage/text':
				$page['text'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				
				EFCommon::$smarty->display('manage_text.tpl');
				break;
			case '/event/text/send':
				$event = $_SESSION['manage_event'];
				
				// Determine whether the auto reminder is activated
				if (isset($_REQUEST['autoReminder']) && $_REQUEST['autoReminder'] == 'true') {
					// Validation
					$sms = new AbstractMessage($_POST['reminderContent'],
										   	   $_POST['reminderTime'],
										   	   $_POST['reminderDate'],
										   	   $_POST['reminderRecipient']);
					
					if($sms->get_errors($autoReminder)) {
						die($sms->print_errors());
					}
				
					// If the auto reminder is enabled, we will only save it (sending it through cron job)
					$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
					$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
					EFCommon::$dbCon->saveText( $_POST['eid'], 
												 $_POST['reminderContent'], 
												 $dateTime, 
												 $autoReminder, 
												 $_POST['reminderRecipient'] );
					echo("Saved");
					return;
				}
				
				// If the auto reminder is not enabled, we will send it right away
				$guests = array();
				switch ($_POST['reminderRecipient']) {
					case 1:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuests($_POST['eid']));
						break;
					case 2:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT1));
						break;
					case 3:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT2));											$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT3));
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT4));
						break;
					case 4:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFOPT5));
						break;
					case 5:
						$this->appendGuests($guests, EFCommon::$dbCon->getConfirmedGuestsByConfidence($_POST['eid'], CONFELSE));
						break;
				}
				
				EFCommon::$sms->sendSMSReminder($guests, $event, EFCommon::$mailer->mapText($_REQUEST['reminderContent'], $event->eid));
				echo("Sent");
				break;
			case '/event/text/save':
				$event = $_SESSION['manage_event'];
			
				$sqlDate = EFCommon::$dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".date("H:i:s", strtotime($_REQUEST['reminderTime']));
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				$req['content'] = $_REQUEST['reminderContent'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				$retval = $this->validateSaveEmail($req);
				if( $retval != "Success" ) {
					die($retval);
				}
				
				EFCommon::$dbCon->saveText($event->eid, $_REQUEST['reminderContent'], $dateTime, SMS_REMINDER_TYPE, $autoReminder);
				echo("Success");
				break;
			case '/event/deleteRSVP':
				EFCommon::$dbCon->deleteRSVP($_POST['attendance_id']);			
				break;
			case '/event/saversvp':
				$user_id_posted = $_POST['user_id_posted'];
				$event_id = $_POST['event_id'];
				$conf = $_POST['conf'];
				$event = $this->buildEvent($event_id);
				$sessionUser = isset($_SESSION['user']->fname)?$_SESSION['user']->fname:"";
				for($i=0;$i<=$_POST['total_rsvps'];$i++)
				{
					if(isset($_POST['guest_email_'.$i]) && $_POST['guest_email_'.$i] != '' && $_POST['guest_email_'.$i] != 'Email')
					{
						$guestName = $_POST['guest_name_'.$i];
						$guestEmail = $_POST['guest_email_'.$i];
						$exist = $this->checkGuestEmail($guestEmail);
						$subjectLine ="";
						if($exist)
						{
							$userInfo = EFCommon::$dbCon->getUserInfoByEmail($guestEmail);
							if(isset($userInfo['id']) && $userInfo['id']== $user_id_posted)
							{
								$subjectLine = "Thank you for RSVPing to {Event name}";
							}else
							{
								$subjectLine = $sessionUser." RSVP'd you to {Event name}";
							}
							$userBuilded = new User($userInfo);
							$recordAttendance = EFCommon::$dbCon->eventSignUpWithOutEmail($userInfo['id'], $event, $conf, $user_id_posted);
							if(isset($_POST['total_guests_last_added']) && ($_POST['total_guests_last_added']==0 ||  $i > $_POST['total_guests_last_added']))
							{
								EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $userBuilded, $event, $subjectLine);
							}
						}else
						{
							$userInfo = EFCommon::$dbCon->createNewUser($guestName, NULL, $guestEmail);
							$userBuilded = new User($userInfo);
							$recordAttendance = EFCommon::$dbCon->eventSignUpWithOutEmail($userInfo['id'], $event, $conf, $user_id_posted);
							if(isset($_POST['total_guests_last_added']) && ($_POST['total_guests_last_added']==0 ||  $i > $_POST['total_guests_last_added']))
							{
								EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $userBuilded, $event, $sessionUser." RSVP'd you to {Event name}");
							}
						}
					}
				}
				echo 'Done';
				break;
			case '/event/saversvplogout':
				unset($_SESSION['attemptValue']);
				unset($_SESSION['total_rsvps']);
				$_SESSION['attemptValue'] = array($_POST['event_id'] => $_POST['conf']);
				$_SESSION['total_rsvps'] = $_POST['total_rsvps'];
				$_SESSION['total_guests_last_added'] = $_POST['total_guests_last_added'];
				for($i=1;$i<=$_POST['total_rsvps'];$i++)
				{
					$_SESSION['guest_name_'.$i] = $_POST['guest_name_'.$i];
					$_SESSION['guest_email_'.$i] = $_POST['guest_email_'.$i];
				}
				echo 'Done';
				break;
			case '/event/manage/followup':
				$page['followup'] = true;
				EFCommon::$smarty->append('page', $page, TRUE);
				EFCommon::$smarty->assign('is_followup', true);

				EFCommon::$smarty->display('manage_email.tpl');
				break;				
			case '/fb/user/update':
				EFCommon::$dbCon->facebookAdd($_REQUEST['fbid']);
				break;
			case '/fb/friends':
				$fbFriends = json_decode(stripslashes($_POST['fbFriends']));
				EFCommon::$dbCon->saveFBFriends($fbFriends->data, $_SESSION['user']->id);
				break;
			case '/fb/invite':
				// The FB ID's that is being invited by the user
				if (isset($_SESSION['manage_event'])) {
					EFCommon::$dbCon->inviteUserFB($_SESSION['user']->id,
												   $_SESSION['user']->facebook,
												   $_REQUEST['to_fbid'],
												   $_REQUEST['request_id'], 
												   isset($_REQUEST['data']) ? $_REQUEST['data'] : NULL, 
												   $_SESSION['manage_event']->eid);
				} else if (isset($_SESSION['newEvent'])) {
					EFCommon::$dbCon->inviteUserFB($_SESSION['user']->id,
												   $_SESSION['user']->facebook,
												   $_REQUEST['to_fbid'],
												   $_REQUEST['request_id'], 
												   isset($_REQUEST['data']) ? $_REQUEST['data'] : NULL, 
												   $_SESSION['newEvent']->eid);
				} else {
					die("Invalid request");
				}
				break;
			case '/register':
				// Logged in user doesn't need to create an account!
				$this->loggedInRedirect();
				
				// Make sure the user is properly redirected
				if ( isset($params) ) {
					EFCommon::$smarty->assign('redirect', $params);
				}
				// If this is a Facebook form login
				if ( isset($_POST['isFB'])) {
					$this->handleFBLogin();
					break;
				// if the user submits the register form
				} else if ( isset ( $_POST['register'] ) ) {
					$userInfo['email'] = $_POST['email'];
					$userInfo['check_email'] = $_POST['check_email'];
					$userInfo['password'] = $_POST['password'];
					$userInfo['fname'] = $_POST['fname']; 	
					$userInfo['lname'] = $_POST['lname'];
					$userInfo['phone'] = $_POST['phone'];
					$userInfo['zip'] = $_POST['zip'];
					$errors = $this->checkUserCreationForm($userInfo);
					
					// Check if any errors
					if( $errors ) {
						EFCommon::$smarty->display('register.tpl');
						break;
					}
					
					// Create the new user
					$userInfo = EFCommon::$dbCon->createNewUser( $_POST['fname'], 
															     $_POST['lname'], 
															     $_POST['email'], 
															     $_POST['phone'], 
															     md5($_POST['password']), 
															     $_POST['zip'] );
					if (isset($userInfo)) {
						// Assign user's SESSION variables
						$_SESSION['user'] = new User($userInfo);
						
						// Send welcome email
						EFCommon::$mailer->sendHtmlEmail('welcome', $_SESSION['user'], 'Welcome to trueRSVP {Guest name}');
						
						// Check on which page the user should be redirected to
						$this->loggedInRedirect();
					} else {
						EFCommon::$smarty->assign('user_create_email', 'You already have a trueRSVP account. <a href="'.CURHOST.'/login/forgot">Forgot password?</a>');
						EFCommon::$smarty->display('register.tpl');
						break;
					}
				} else {
					EFCommon::$smarty->display('register.tpl');
					break;
				}


				if ( isset ( $params ) ) {
					header("Location: " . $this->getRedirectUrl());
					exit;
				}
				
				header("Location: " . CURHOST);
				break;
			case '/login':
				// If Facebook session exists, and we send request from manage guest page then .
				if(isset($_POST['req_uri']) && trim($_POST['req_uri'])=="")
				{
					$this->loggedInRedirect();	
				}
				// Make sure the user is properly redirected
				if ( isset($params) ) {
					EFCommon::$smarty->assign('redirect', $params);
				}
				
				if ( isset($_POST['isFB']) ) {
					// If currently looking at the event page
					if (preg_match("/event\/\d+/", $_POST['curPage']) > 0) {
						$_SESSION['page_redirect'] = $_POST['curPage'];
					}
					$this->handleFBLogin();
					break;
				// if the user submits the login form
				} else if ( isset( $_POST['login'] ) ) {
					if ( strlen($_POST['email']) == 0 ) {
						$error['email'] = "Please enter an e-mail";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					} else if ( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
						$error['email'] = "Please enter a valid e-mail address";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					} else if ( ! EFCommon::$dbCon->isUserEmailExist( $_POST['email'] ) ) {
						$error['email'] = "E-mail is not registered in our system";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					if ( strlen($_POST['password']) == 0 ) {
						$error['password'] = "Please enter a password";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					
					$userInfo = EFCommon::$dbCon->checkValidUser( $_POST['email'], $_POST['password'] );
					
					if ( ! $userInfo ) {
						// Invalid e-mail/password combination
						$error['login'] = "Invalid e-mail or password";
						EFCommon::$smarty->assign('error', $error);
						EFCommon::$smarty->display("login.tpl");
						break;
					}
					
					$_SESSION['user'] = new User($userInfo);
					setcookie(USER_COOKIE, $_SESSION['user']->cookie);
					
					// Create user's event if valid
					if ( isset($_SESSION['newEvent']) ) {
						$newEvent = $_SESSION['newEvent'];
						if ( $this->validateEventInfo( $newEvent ) == true ) {
							$this->makeNewEvent( $newEvent );
							header("Location: " . CURHOST . "/event/create/invite");
							exit;
						}
					}
					
					// Check if the user needs to be redirected instead
					$this->loggedInRedirect();
					
					header("Location: " . CURHOST . "/home?loggedin=true");
					exit;

				/* User used trueRSVP register */
				} else {
					EFCommon::$smarty->display('login.tpl');
					break;
				}
				if ( isset ( $params ) ) {
					header("Location: " . $this->getRedirectUrl());
					exit;
				}
				
				header("Location: " . CURHOST);
				break;
			case '/login/reset':
				if (EFCommon::$dbCon->isValidPassResetRequest($_REQUEST['ref'])) {
					EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
					EFCommon::$smarty->display('login_reset.tpl');
				} else {
					EFCommon::$smarty->display('login_reset_invalid.tpl');
				}
				break;
			case '/login/reset/submit':
				if (strlen($_REQUEST['login_forgot_newpass']) < 6) {
						EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
						EFCommon::$smarty->assign('errorMsg', 'Password must be at least 6 characters');
						EFCommon::$smarty->display('login_reset.tpl');
				} else {
					if ($_REQUEST['login_forgot_newpass'] == $_REQUEST['login_forgot_newpass_conf']) {
						EFCommon::$dbCon->resetPasswordByEmail($_REQUEST['login_forgot_newpass'], $_REQUEST['login_forgot_ref']);
						EFCommon::$smarty->display('login_reset_confirmed.tpl');
					} else {
						EFCommon::$smarty->assign('ref', $_REQUEST['ref']);
						EFCommon::$smarty->assign('errorMsg', 'New password is not confirmed');
						EFCommon::$smarty->display('login_reset.tpl');
					}
				}
				break;
			case '/login/forgot':
				EFCommon::$smarty->display('login_forgot.tpl');
				break;
			case '/login/forgot/submit':
				$hash_key = md5(time().$_REQUEST['login_forgot_email']);
				$user = EFCommon::$dbCon->requestPasswordReset($hash_key, $_REQUEST['login_forgot_email']);
				
				if (isset($user)) {
					EFCommon::$mailer->sendHtmlEmail('forgot_pass', $user, "Reset Password", NULL, "This is the link to reset your password: ".CURHOST."/login/reset?ref=".$hash_key);
					header('Location: ' . CURHOST . '/login/forgot/sent');
					exit;
				} else {
					EFCommon::$smarty->display('login_forgot_invalid.tpl');
				}
				break;
			case '/login/forgot/sent':
				EFCommon::$smarty->display('login_forgot_confirmed.tpl');
				break;
			case '/user/image/upload':
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");

				// max file size in bytes
				$sizeLimit = 2 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/user/', TRUE);
				
				EFCommon::$dbCon->saveUserPic($result['file']);
				
				// to pass data through iframe you 
				// will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/user/status/update':
				if ($_REQUEST['value'] != "Click here to edit") {
					EFCommon::$dbCon->updateUserStatus($_REQUEST['value']);
					$_SESSION['user']->about = $_REQUEST['value'];
					//echo($_REQUEST['value']);
				}
				break;
			case '/user/profile/update':
				// EFCommon::$dbCon->updatePaypalEmail($_SESSION['user']->id, $_REQUEST['paypal_email']);

				EFCommon::$smarty->display('user_profile.tpl');
				break;
			case '/logout':
				if ( ! isset($_SESSION['user']) ) {
					header('Location: ' . CURHOST);
					break;
				}
				session_unset();
				session_destroy();
				
				// Remove cookies
				unset($_COOKIE[USER_COOKIE]);
				setcookie(USER_COOKIE, NULL, -1);
				
				EFCommon::$smarty->display('index.tpl');
				break;
			case '/notyet':
				if ( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
					EFCommon::$dbCon->storeNotyet($_POST['email']);
					print("Thank you!");
				} else {
					print("Invalid email");
				}
				break;
			case '/calendar/ics':
				$event = $this->buildEvent( $_GET['eventId'] );
				$event->getICS();
				break;
			case '/calendar/vcs':
				$event = $this->buildEvent( $_GET['eventId'] );
				$event->getVCS();
				break;
			case '/service/autocomplete':
				break;
			case '/twitter/update':
				$responseMsg['user_success'] = "Your Twitter account is now connected.";
				EFCommon::$smarty->assign('responseMsg', $responseMsg);
				break;
			default:
				EFCommon::$smarty->assign('current_page', $current_page);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
	
	/* makeNewEvent
	 * Adds the event to the database, then switches to step 2
	 *
	 * @param $newEvent | The VALIDATED event object
	 * @return true | The information is valid
	 * @return false | Infomration is bad
	 */
	protected function makeNewEvent( $newEvent ) {
		// Make sure user is logged in before they can
		// create the event
		if ( ! isset($_SESSION['user']) ) {
			$_SESSION['newEvent'] = $newEvent;
			header("Location: " . CURHOST . "/login");
			exit;
		}
		
		EFCommon::$dbCon->createNewEvent($newEvent);
		
		$newEvent = EFCommon::$dbCon->getLastEventCreatedBy($_SESSION['user']->id);
		$newEvent->setGuests(NULL);
		$newEvent->currentUserAttend(90, false);
			
		$_SESSION['newEvent'] = $newEvent;
	}
	protected function addEventImage($eid, $imageURL)
	{
		if ( ! isset($_SESSION['user']) ) {
			$_SESSION['newEvent'] = $newEvent;
			header("Location: " . CURHOST . "/login");
			exit;
		}
		EFCommon::$dbCon->addEventImage($eid, $imageURL);
			
	}
	
	/* saveEventFields
     * Stores the current values for the new event
     * in an array that can be assigned in SMARTY
     * 
     * @param $newEvent | The event being saved
     * @return $event_field | The array of event information
	 */
	protected function saveEventFields( $newEvent ) {

		// Save the current fields
		$event_field = $newEvent->get_array();
		EFCommon::$smarty->assign('event_field', $event_field);
	}
	
	private function checkAPIEntry($current_page) {
		if (preg_match("/api\/.*/", $current_page) > 0) {
			$apiController = new APIController();
			$apiController->getView($current_page);
			exit;
		}
	}
	
	/**
	 * Create the user object for each user that is retrieved from the DB
	 * &$array  Array  the reference array that we want to append the user object into
	 * $userDb  Array  the array of users from the DB
	 */
	private function appendGuests(&$array, $userDb) {
		for ($i = 0; $i < sizeof($userDb); ++$i) {
			array_push($array, new User($userDb[$i]));
		}
	}
	
	/* Permission and login functions */
		
	private function getRedirectUrl() {
		if (isset($_SESSION['eref'])) {
			$url = CURHOST."/event/".$_SESSION['eref']['event_id'];
			unset($_SESSION['eref']);
		} else {	
			switch ($_GET['redirect']) {
				case 'cp':
					$url = CURHOST . "?loggedIn=true";
				case 'event':
					if ( $_GET['eventId'] ) {
						$url = CURHOST . "/event/" . $_GET['eventId'];
					}
					break;
				case 'manage':
					if ( $_GET['eventId'] ) {
						$url = CURHOST . "/event/manage?eventId=" . $_GET['eventId'];
					}
					break;
				default:
					$url = CURHOST;
			}
		}
		
		return $url;
	}

	/*
	 * Redirect the user home if he's logged in
     */
	private function loggedInRedirect() {
		// if the user already logged in
		if ( isset($_SESSION['user']) ) {
			// If there is a page to be redirected to
			if (isset($_SESSION['eventViewed'])) {
					header("Location: " . EVENT_URL . "/a/" . $_SESSION['eventViewed']->alias);
			} else if (isset($_SESSION['page_redirect'])) { 
				$redirect = $_SESSION['page_redirect'];
				unset($_SESSION['page_redirect']);
				header("Location: " . $redirect);
			} else if(isset($_SESSION['fb'])) {
				header("Location: " . CURHOST . "/home?loggedIn=true");
			} else {
				header("Location: " . CURHOST . "/home?loggedIn=false");
			}
			exit;
		}
	}
	
	/*
	 * The user has just logged in to FB,
	 * so let's take him to his profile page
	 */
	private function handleFBLogin() {
		$response = 8;
		if(isset($_SESSION['fb']->facebook) && $_SESSION['fb']->facebook!='')
		{
			$response = 9;
		}
		
		$userInfo = EFCommon::$dbCon->facebookConnect( $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['fbid'], 
													   $_POST['fb_access_token'], $_POST['fb_session_key'] );
		if ($userInfo ) {
			$_SESSION['fb'] = new User($userInfo);
			if(isset($_POST['req_uri']) && trim($_POST['req_uri'])==""){
				echo 3;
			}else{
				// If Facebook session exists, and we send request from manage guest page.
				echo $response;
			}
		} else {
			if(isset($_POST['req_uri']) && trim($_POST['req_uri'])==""){
				echo 0;
			}else{
				// If Facebook session exists, and we send request from manage guest page.
				echo $response;
			}
		}
	}
	
	/* Display functions */
	
	/* printEvent
	 * Used to print out an event
	 */
	public function printEvent() {
		require_once('models/EFCore.class.php');
		
		$eventId = $_GET['eventId'];
		$event = new Event($eventId);
		
		$eventAttendees = EFCommon::$dbCon->getAttendeesByEvent($eventId);

		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}

		EFCommon::$smarty->assign('trsvpVal', EFCommon::$core->getTrueRSVP($event));
		EFCommon::$smarty->assign('eventAttendees', $eventAttendees);
		EFCommon::$smarty->assign('eventInfo', $eventInfo);
		EFCommon::$smarty->display('manage_event_on.tpl');
	}
	
	private function displayUserById($userId) {
		$profile = new User($userId);
		if ( ! $profile->exists ) {
			EFCommon::$smarty->display('error_user_notexist.tpl');
		} else {
			// $is_following = isset($_SESSION['user']) ? EFCommon::$dbCon->isFollowing($_SESSION['user']->id, $profile->id) : 0;
			// EFCommon::$smarty->assign("is_following", $is_following);
			$this->assignProfileEvents($userId);
			EFCommon::$smarty->assign("profile", $profile);
			EFCommon::$smarty->display('profile.tpl');
		}
	}
	
	/**
	 * Display the error page with the specified error message
	 * @param $error_message  String  the error message to be displayed
	 */
	private function displayError($error_message) {
		EFCommon::$smarty->assign('error_message', $error_message);
		EFCommon::$smarty->display('error.tpl');
	}
	
	/* DB Query functions */
	
	/* buildEvent
	 * Returns an Event Object given an Event ID
	 *
	 * @param $eventId | The event ID
	 * @return $event | The event object
	 */
	protected function buildEvent($eventId, $manage = false ) {
		
		if ( $manage ) {
			if ( isset($_SESSION['manage_event']) ) {				
				if ( $_SESSION['manage_event']->eid == $eventId ) {
					return $_SESSION['manage_event'];
				} else {
					unset( $_SESSION['manage_event'] );
				}
			}
		}
		
		$event = new Event($eventId);
		
		if($manage) {
			$_SESSION['manage_event'] = $event;
		}
		
		return $event;
	}
	
	/* getAttendees
	 * Grabs the invited users for a given event
	 *
	 * @param	$eventId | The ID of the event, or null if using $_GET['eventId']
	 * @return	$attendees | An array of AbstractUsers who are invited to the Event
	 */
	private function getAttendees($eventId) {
		if ( $eventId == NULL ) {
			$eventId = $_GET['eventId'];
		}
		$user_array = EFCommon::$dbCon->getUnionInvitedByEvent($eventId);
		$attendees = array();
		foreach($user_array as $userInfo) {
			$attendees[] = new Contact($userInfo);
		}
		return $attendees;
	}
	
	/**
	 * Assign all of the invited guests.
	 * Including FB and email address contacts.
	 */
	protected function assignInvited($eventId) {
		$invited = EFCommon::$dbCon->getUnionInvitedByEvent($eventId);
		$guests = array();
		for ($i = 0; $i < sizeof($invited); ++$i) {
			$guest = new Contact($invited[$i]['name'], $invited[$i]['id']);
			$guests[] = $guest;
		}
		EFCommon::$smarty->assign('guests', $guests);
	}
	
	/**
	 * Assign all of the contacts of the logged in user.
	 * Including FB and email address contacts.
	 */
	protected function assignContacts() {
		// Build the user contact list
		$contactList = EFCommon::$dbCon->getContactsUnion($_SESSION['user']->id);
		$contacts = array();
		for ($i = 0; $i < sizeof($contactList); ++$i) {
			$contact = new Contact($contactList[$i]['name'], $contactList[$i]['id']);
			$contacts[] = $contact;
		}
		if ( sizeof($contacts) > 0 ) {
			EFCommon::$smarty->assign('contacts', $contacts);
		} else {
			EFCommon::$smarty->assign('contacts', NULL);
		}
	}
	
	/***** PROFILE ASSIGN EVENTS ********/
	private function assignProfileEvents($uid) {
		$this->assignCreatedEvents($uid, true);
		$this->assignAttendingEvents($uid, true);
	}
	
	/***** CONTROL PANEL ASSIGN EVENTS ********/
	private function assignCPEvents($uid) {
		$this->assignCreatedEvents($uid);
		$this->assignAttendingEvents($uid);
		$this->assignInvitedEvents($uid);
		$this->assignAttendedEvents($uid);
		$this->assignPastCreatedEvents($uid);
	}
	
	private function assignPastCreatedEvents($uid) {
		$past_created_event = EFCommon::$dbCon->getPastEventByEO($uid);
		$pastCreatedEvents = NULL;
		foreach ( $past_created_event as $event ) {
			$pastCreatedEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('pastCreatedEvents', $pastCreatedEvents);
	}
	
	private function assignCreatedEvents($uid, $publicOnly = false) {
		$created_event = EFCommon::$dbCon->getEventByEO($uid, $publicOnly);
		$createdEvents = NULL;
		foreach ( $created_event as $event ) {
			$createdEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('createdEvents', $createdEvents);
	}
	
	private function assignAttendingEvents($uid, $publicOnly = false) {
		$attending_event = EFCommon::$dbCon->getEventAttendingByUid($uid, $publicOnly);
		$attendingEvents = NULL;
		foreach( $attending_event as $event ) {
			$attendingEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('attendingEvents', $attendingEvents);
	}
	
	private function assignInvitedEvents($uid) {
		$invited_event = EFCommon::$dbCon->getEventInvited($uid);
		$invitedEvents = NULL;
		foreach( $invited_event as $event ) {
			$invitedEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('invitedEvents', $invitedEvents);
	}
	
	private function assignAttendedEvents($uid) {
		$attended_event = EFCommon::$dbCon->getEventAttended($uid);
		$attendedEvents = NULL;
		foreach( $attended_event as $event ) {
			$attendedEvents[] = new Event($event);
		}
		EFCommon::$smarty->assign('attendedEvents', $attendedEvents);
	}
	
	public function assignManageVars($eventId) {
		$event = new Event($eventId);
	
		$guestConf1 = EFCommon::$dbCon->getConfirmedGuestsByConfidence($eventId, CONFOPT1);
		$guestConf2 = EFCommon::$dbCon->getConfirmedGuestsByConfidence($eventId, CONFOPT2);
		$guestConf3 = EFCommon::$dbCon->getConfirmedGuestsByConfidence($eventId, CONFOPT3);
		$guestConf4 = EFCommon::$dbCon->getConfirmedGuestsByConfidence($eventId, CONFOPT4);
		$guestConf5 = EFCommon::$dbCon->getConfirmedGuestsByConfidence($eventId, CONFOPT5);
		$guestNoResp = EFCommon::$dbCon->getConfirmedGuestsByConfidence($eventId, CONFELSE);
		
		EFCommon::$smarty->assign('guestConf1Count', sizeof($guestConf1));
		EFCommon::$smarty->assign('guestConf2Count', sizeof($guestConf2));
		EFCommon::$smarty->assign('guestConf3Count', sizeof($guestConf3));
		EFCommon::$smarty->assign('guestConf4Count', sizeof($guestConf4));
		EFCommon::$smarty->assign('guestConf5Count', sizeof($guestConf5));
		EFCommon::$smarty->assign('guestNoRespCount', sizeof($guestNoResp));
		
		EFCommon::$smarty->assign('guestConf1', $guestConf1);
		EFCommon::$smarty->assign('guestConf2', $guestConf2);
		EFCommon::$smarty->assign('guestConf3', $guestConf3);
		EFCommon::$smarty->assign('guestConf4', $guestConf4);
		EFCommon::$smarty->assign('guestConf5', $guestConf5);
		EFCommon::$smarty->assign('guestNoResp', $guestNoResp);
		
		EFCommon::$smarty->assign('guestimate', EFCommon::$core->computeGuestimate($eventId));
		EFCommon::$smarty->assign('trsvpVal', EFCommon::$core->getTrueRSVP($event));
	}	
	
	/* Save / Create functions */
	
	/* URL Parsing */
	
	private function getUserIdByUri( $requestUri ) {
		$userId = explode('/', $requestUri);		

		// Verify that format of URL is http://{$CURHOST}/user/{$userId}
		if (sizeof($userId) != 3 ) {
			return false;
		}
		
		$userId = $userId[sizeof($userId)-1];
		
		return $userId;
	}
	
	/**
	 * Given the current page URI, get its alias
	 * @return String alias of the event URI
	 */
	protected function getAliasByUri($requestUri) {
		$alias = explode('/', $requestUri);
		
		// Verify that format of URL is http://{$CURHOST}/event/a/{$alias}
		if (sizeof($alias) != 4 ) {
			return false;
		}
		
		$alias = $alias[sizeof($alias) - 1];
		
		return $alias;
	}
	
	/* Validation functions */

	/**
	 * Make sure that the user is valid seeing the current page
	 */
	private function securityValidate($current_page) {
		// /event/manage/* pages are protected
		if (preg_match("/event\/manage*/", $current_page) > 0) {
			if (!isset($_SESSION['user'])) {
				header("Location: ".CURHOST."/login");
			}
			else if (!EFCommon::$dbCon->checkValidHost($_REQUEST['eventId'], $_SESSION['user']->id)) {
				$this->displayError("You're not the host of this event");
				return false;
			}
		}
		
		return true;
	}

	/* validateEventInfo
	 * Makes sure event info is valid
	 *
	 * @param $newEvent | The event object
	 * @return true | The information is valid
	 * @return false | Infomration is bad
	 */
	private function validateEventInfo ( &$newEvent ) {
		// Check for errors
		$error = $newEvent->get_errors();
		
		$is_valid = ( $error === false ) ? true : false;
		
		// If there are errors
		if ( ! $is_valid ) {
			if ( $error !== true )
				EFCommon::$smarty->assign('error', $error);
			return false;
		} 

		// Looks like it's valid ;)
		return true;
	}
	
	// checkUserCreationForm
	public function checkUserCreationForm($userInfo) {
		$flag = 1;
		$email = $userInfo['email'];
		$check_email = $userInfo['check_email'];
		$password = $userInfo['password'];
		$fname = $userInfo['fname'];
		$lname = $userInfo['lname'];
		$phone = $userInfo['phone'];
		$zip = $userInfo['zip'];

		$email_val = $this->valEmail(
							$email,
							$check_email,
							"email",
							"Email entered is invalid."
						);
		
		if ( strlen($password) < 6 ) {
			$flag = 2;
			$error['password'] = "Password should be at least 6 characters";
			EFCommon::$smarty->append("error", $error, true);
		}
		$f_name_val = 	$this->valUsingRegExp(
							$fname,
							"/^[A-Za-z']*$/",
							"fname",
							"First name should only contain letters"
						);
		$l_name_val = 	$this->valUsingRegExp(
							$lname,
							"/^[A-Za-z']*$/",
							"lname",
							"Last name should only contain letters"
						);
						
		if ( isset($ph_val) && $ph_val != '') {
			$ph_val = 	$this->valUsingRegExp(
							$phone,
							"/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/",
							"phone",
							"Phone number is not in valid format"
						);
		}
		
		$zipcode_val = 	$this->valUsingRegExp(
							$zip, 
							"/^\d{5}(-\d{4})?$/", 
							"zip", 
							"Please enter a valid zip code"
						);

		if ( $f_name_val == 2 || $l_name_val == 2 || $email_val == 2 || $ph_val == 2 || $zipcode_val == 2 ) {
			$flag = 2;
		}
		
		// Flag = 2, error = true, else, error = false
		return ( $flag == 2 ) ? true : false;
	}
	
	// BEGIN OLD FUNCTIONS
	//////////////////////
	public function validateSaveEmail($req) {
		$msg="<br />";
		$flag=0;
		$dt=$req['date'];
		$a_date = explode('/', $dt);
		$month = $a_date[0];
		$day = $a_date[1];
		$year = $a_date[2]; 
		if(!@checkdate($month,$day,$year)) {
			$msg.="Please enter a date in mm/dd/yyyy format. <br>";
			$flag=1;
		}
		
		// Make sure date is in the future
		$check = @mktime(0, 0, 0, $month, $day, $year,-1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"), -1);
		if( $check < $today ) {
			$msg.="Date must be in the future<br />";
			$flag=1;
		}

		$res=filter_var($req['content'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[\{A-Za-z 0-9'\}]*$/")));
		if(!($res)) {
			$flag=1;
			$msg.="Content can only contain characters A-Z or numbers 0-9 <br />";
		}

		if($flag==0) {
			$msg="Success";
		}

		return $msg;
	}

	public function valEmail($email, $check_email, $type, $msg) {
		$flag = 1;
		if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$error[$type] = $msg;
			EFCommon::$smarty->append('error', $error, true);
			$flag = 2;
		}
		
		if (strtolower($email) != strtolower($check_email)) {
			$error[$type] = "Email does not match";
			EFCommon::$smarty->append('error', $error, true);
			$flag = 2;
		}
		
		return $flag;
	}

	public function valUsingRegExp($val,$regex,$type,$msg) {
		$flag = 1;
		$res = filter_var( $val, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp" => $regex ) ) );
		if( ! $res ) {
			$error[$type] = $msg;
			EFCommon::$smarty->append('error', $error, true);
			$flag = 2;
		}
		return $flag;
	}

	////////////////
	// End OLD FUNCTIONS
}
