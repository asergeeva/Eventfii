<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/PanelController.class.php');
 
class CreateController extends PanelController {
	private $dbConn;
	public function __construct() {
		$this->dbConn = new AdminDB();
	}
	
	public function __destruct() {
	
	}
	
	/**
	 * Controller for the following prefixes: 
	 *		1. /event/create
	 * @return false when it does not match the prefix
	 */
	public function get_file_extension($file_name)
	{
			  return substr(strrchr($file_name,'.'),1);
	}
	private function displayStock()
	{
		$stockList = $this->dbConn->admin_getStockList();
		$stockPhotosCount = array();
		EFCommon::$smarty->assign('stock', $stockList);
	}
	function GetImageFromUrl($link)
	{	 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0); 
		curl_setopt($ch,CURLOPT_URL,$link); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$result=curl_exec($ch); 
		curl_close($ch); 
		return $result; 
	}
	/*Function to get Image from url*/
	public function getView($current_page) {
		if (preg_match("/event\/create.*/", $current_page) > 0) {
			switch ($current_page) {
				case "/event/create":
					// If there are some of the fields that needs to be prefilled
					$event_field = array();
					if (isset($_POST['title']) && strtolower($_POST['title']) != "name of event") {
						$event_field['title'] = stripslashes($_POST['title']);
					}
					if (isset($_POST['goal']) && strtolower($_POST['goal']) != "max") {
						$event_field['goal'] = stripslashes($_POST['goal']);
					}
					EFCommon::$smarty->assign('event_field', $event_field);
				
					EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[0]);
					EFCommon::$smarty->assign('step', 1);
					EFCommon::$smarty->display('create.tpl');
					break;
				case "/event/create/details":
					$newEvent = new Event(NULL, true);
					
					$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
					
					if (!$is_valid) {
						EFCommon::$smarty->assign('error', $newEvent->error);
						$this->saveEventFields( $newEvent );
						
						EFCommon::$smarty->assign('step', 1);
						EFCommon::$smarty->display('create.tpl');
					} else {				
						EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[1]);
						EFCommon::$smarty->assign('step', 2);
						EFCommon::$smarty->display('create.tpl');
					}
					break;
				case '/event/create/invite/showstockimages':
					$id = $_POST['id'];
					$stockPhotos = $this->dbConn->admin_getStockPhotos($id);
					$html = '';
					$count = 0;
					foreach($stockPhotos as $stockPhoto)
					{
						$count++;
						$html .= '<img id="'.$stockPhoto['id'].'" src="'.CURHOST.'/upload/stock/thumb/'.$stockPhoto['thumb'].'" onclick="changeImage('.$stockPhoto['id'].');" />';
						if($count%2==0)
						{
							$html .= '<div class="clear"></div>';	
						}
					}
					$html .= '';
					echo $html;
					break;
				case '/event/create/invite':
					$newEvent = isset($_SESSION['newEvent']) ? $_SESSION['newEvent'] : new Event(NULL);
					$this->eventid = $newEvent->eid;
					$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
					if (!$is_valid) {
						EFCommon::$smarty->assign('error', $newEvent->error);
						
						$this->saveEventFields( $newEvent );
						$this->displayStock();
						EFCommon::$smarty->assign('event', $newEvent);
						EFCommon::$smarty->assign('eventid', json_encode(array('eid'=>$newEvent->eid)));
						EFCommon::$smarty->assign('step', 3);
						EFCommon::$smarty->display('custom_invite.tpl');
					} else {
						EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[2]);
						if (!isset($_SESSION['newEvent'])) {
							$this->makeNewEvent( $newEvent );
						}
						
						if ( isset($_POST['submit']) ) {
							$guest_emails = $_SESSION['newEvent']->submitGuests();
							if ( sizeof($guest_emails) == 0 ) {
								EFCommon::$smarty->assign('error', "No guests added.");
							} else {
								EFCommon::$smarty->assign('notification', "Yay!");
							}
						}
						$this->displayStock();
						EFCommon::$smarty->assign('step', 3);
						EFCommon::$smarty->assign('event', $_SESSION['newEvent']);
						EFCommon::$smarty->assign('eventid', json_encode(array('eid'=>$_SESSION['newEvent']->eid)));
						EFCommon::$smarty->display('custom_invite.tpl');
					}
					break;
				case '/event/create/guests':
					$newEvent = isset($_SESSION['newEvent']) ? $_SESSION['newEvent'] : new Event(NULL);
					
					$is_valid = ( $newEvent->numErrors == 0 ) ? true : false;
					if (!$is_valid) {
						EFCommon::$smarty->assign('error', $newEvent->error);
						
						$this->saveEventFields( $newEvent );
						
						EFCommon::$smarty->assign('step', 2);
						EFCommon::$smarty->display('create.tpl');
					} else {
						EFCommon::$metricsTracker->track(Experiment::$EXPERIMENT_CREATE_EVENT[2]);
						if (!isset($_SESSION['newEvent'])) {
							$this->makeNewEvent( $newEvent );
						}
						
						if ( isset($_POST['submit']) ) {
							$guest_emails = $_SESSION['newEvent']->submitGuests();
							if ( sizeof($guest_emails) == 0 ) {
								EFCommon::$smarty->assign('error', "No guests added.");
							} else {
								EFCommon::$smarty->assign('notification', "Yay!");
							}
						}
						
						$this->assignInvited($_SESSION['newEvent']->eid);
						$this->assignContacts();
						
						EFCommon::$smarty->assign('finishSubmit', CURHOST.'/event/a/'.
																	$_SESSION['newEvent']->alias.
																	'?created=true');
						EFCommon::$smarty->assign('step', 4);
						EFCommon::$smarty->assign('addButton', true);
						EFCommon::$smarty->assign('event', $_SESSION['newEvent']);
						if (isset($_GET['option'])) {
						EFCommon::$smarty->assign('submitTo', CURHOST . "/event/create/guests?eventId=" . 
																$_SESSION['newEvent']->eid . 
																" &option=".$_GET['option']);
						}
						EFCommon::$smarty->display('create_addguest.tpl');
					}
					break;
					case '/event/create/invite/upload':
						if (!empty($_FILES))
						{
							$file		=  $_FILES['Filedata'];
							$tmp_name	=  $file['tmp_name'];
							$filename   =  $file['name'];
							$eid		=  $_REQUEST['eid'];
							//$ext      = substr($filename,-3);
							$ext		= $this->get_file_extension($filename);
							$uploadPath = './upload/events/';
							$file_name  = $eid.'_origional';
							$upfile = $file_name.".$ext";
							move_uploaded_file($tmp_name,$uploadPath.$upfile);	
							chmod($uploadPath.$upfile, 0777);
							/*Image Resize CI Library*/			
							include("./libs/image_resize/resize_class.php");
							$image_resize = new CI_Image_lib();
							$source_image = $upfile;
							$medium_image = $upfile;
							$config['source_image'] = $uploadPath.$source_image;
							$config['new_image'] = $uploadPath.$medium_image;
							$config['quality'] = '80';
							$width = '638';
							$height = '638';
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
							echo $upfile;
							exit; 
							//upload Here	
						}
						break;
					case '/event/create/invite/upload/save':
						if(isset($_POST['eid']))
							$eid = $_POST['eid'];
						else
							$eid = $_SESSION['newEvent']->eid;
						$imageURL = base64_decode($_POST['imageURL']);
						$sourcecode = $this->GetImageFromUrl($imageURL);
						$uploadPath = './upload/events/';
						$ext = $this->get_file_extension($imageURL);
						chmod($uploadPath.$eid.'_origional.'.$ext,0777);
						$savefile = fopen($uploadPath.$eid.'_origional.'.$ext, 'w');
						$event_field = array();
						$event_field['image'] = $uploadPath.$eid.'_origional.'.$ext;
						if(!isset($_POST['eid']))
							$_SESSION['newEvent']->image = $eid.'_origional.'.$ext;
						$this->addEventImage($eid, $eid.'_origional.'.$ext);
						fwrite($savefile, $sourcecode);
						fclose($savefile);
						break;
			}
			
			/* IMPORTANT: WE NEED TO EXIT AFTER USING SUB-CONTROLLERS */
			exit;
		}
		return false;
	}
}