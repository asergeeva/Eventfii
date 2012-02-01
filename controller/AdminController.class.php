<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/AdminDB.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/Event.class.php');


class AdminController {
	private $dbCon;
	
	public function __construct() {
		$this->dbCon = new AdminDB();
	}
	
	public function __destruct() {
		
	}
	private function displayStock()
	{
		$stockList = $this->dbCon->admin_getStockList();
		$stockPhotosCount = array();
		foreach($stockList as $stock)
		{
			$stockPhotosCount[$stock['id']] = $this->dbCon->admin_getStockPhotosCount($stock['id']);
		}
		EFCommon::$smarty->assign('stock', $stockList);
		EFCommon::$smarty->assign('stockCount', $stockPhotosCount);
	}
	private function showStockList()
	{
		$stockList = $this->dbCon->admin_getStockList();
		EFCommon::$smarty->assign('stock', $stockList);
	}
	private function getStockById($sid)
	{
		$stockName = $this->dbCon->admin_getStockName($sid);
		EFCommon::$smarty->assign('stockName', $stockName);
	}
	private function displayStockPhotos($stockId)
	{
		$stockPhotos = $this->dbCon->admin_getStockPhotos($stockId);
		EFCommon::$smarty->assign('stockPhotos', $stockPhotos);
	}
	private function displayStockName($id)
	{
		$stockName = $this->dbCon->admin_getStockName($id);
		EFCommon::$smarty->assign('stockName', $stockName);
	}
	private function displayEvents() {
		EFCommon::$smarty->assign('num_events', $this->dbCon->admin_getNumEvents());	
		EFCommon::$smarty->assign('num_users', $this->dbCon->admin_getNumUsers());	
		EFCommon::$smarty->assign('num_invites', $this->dbCon->admin_getTotalNumInvites());
		
		$eventList = $this->dbCon->admin_getEventList();
		$newEvents = array();
		foreach ($eventList as $eventInfo) {
			$event = new Event($eventInfo['id']);
			
			
			$eventInfo['num_invites'] = $this->dbCon->admin_getNumInvites($event->eid);
			$eventInfo['fb_invite'] = $this->dbCon->admin_getNumFbInvites($event->eid);
			$eventInfo['num_checked'] = $this->dbCon->admin_getNumChecked($event->eid);
			$eventInfo['truersvp'] = EFCommon::$core->getTrueRSVP($event);
			
			$newEvents[] = $eventInfo;
		}
		EFCommon::$smarty->assign('events', $newEvents);
	}
	private function delStockPhoto($delId)
	{
		$this->dbCon->admin_delStockPhoto($delId);
	}
	private function saveStock()
	{
		$this->dbCon->admin_saveStock($_POST['stock_name']);	
	}
	private function EditStock()
	{
		$this->dbCon->admin_editStock($_POST['stock_name'], $_GET['stockId']);
	}
	private function DeleteStock()
	{
		$this->dbCon->admin_delStock($_GET['stockId']);
	}
	private function uploadFile()
	{
		if (isset($_FILES['up_file']) && $_FILES['up_file']['error'] != 4)
		{
			$file		=  $_FILES['up_file'];
			$tmp_name	=  $file['tmp_name'];
			$filename   =  $file['name'];
			$s_id		= $_GET['stockId'];
			$uploadPath = '../upload/stock/';
			$thumbPath = '../upload/stock/thumb/';
			$upfile = $s_id.'_'.$filename;
			move_uploaded_file($tmp_name,$uploadPath.$upfile);
			chmod($uploadPath.$upfile, 0777);
			include("../libs/image_resize/resize_class.php");
			$image_resize = new CI_Image_lib();
			//Crop Main Image
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
			
			//Crop thumnail image
			
			$config['source_image'] = $uploadPath.$source_image;
			$config['new_image'] = $thumbPath.$medium_image;
			$config['quality'] = '80';
			$width = '185';
			$height = '185';
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
				return CURHOST."/admin/?option=stock&error=Unable to upload image.";
			}else
			{
				$this->dbCon->insertStockPhoto($upfile, $s_id);	
				return CURHOST."/admin/?option=stock&error=Image successfully uploaded.";
			}
			//upload Here	
		}else
		{
			return CURHOST."/admin/?option=addstock&stockId=".$_GET['stockId']."&error=Please select image.";
			EFCommon::$smarty->assign('error', "");	
		}
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		$requestUri = explode('/', $requestUri);
		
		// Make sure that HTTPS is always on
		/*if (!isset($_SERVER['HTTPS'])) {
			header('Location: '.CURHOST.'/'.$requestUri[1]);
		}*/
		
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
				if(!isset($_GET['option']))
				{
					$this->displayEvents();		
				}elseif(isset($_GET['option']) && $_GET['option'] == 'stock')
				{
					$this->displayStock();
					EFCommon::$smarty->assign('error', "");
				}elseif(isset($_GET['option']) && isset($_GET['stockId']) && $_GET['option'] == 'viewstock' && !isset($_GET['delId']))
				{
					$this->displayStockPhotos($_GET['stockId']);	
				}elseif(isset($_GET['option']) && isset($_GET['stockId']) && $_GET['option'] == 'addstock')
				{
					$this->displayStockName($_GET['stockId']);
					if(isset($_POST['submit']))
					{
						$url = $this->uploadFile();
						header("Location:".$url);	
					}
				}elseif(isset($_GET['option']) && isset($_GET['stockId']) && $_GET['option'] == 'viewstock' && isset($_GET['delId']))
				{
					$this->delStockPhoto($_GET['delId']);
					header("Location:".CURHOST."/admin/?option=viewstock&stockId=".$_GET['stockId']."&error=Image deleted.");
				}elseif(isset($_GET['option']) && $_GET['option'] == 'manageStock' && !isset($_GET['type']))
				{
					$this->showStockList();
					EFCommon::$smarty->assign('error', "");
				}elseif(isset($_GET['option']) && $_GET['option'] == 'manageStock' && isset($_GET['type']) && $_GET['type'] == 'add')
				{
					if(isset($_POST['submit']))
					{
						$this->saveStock();
						header("Location:".CURHOST."/admin/?option=manageStock&error=Record saved successfully.");	
					}
				}elseif(isset($_GET['option']) && $_GET['option'] == 'manageStock' && isset($_GET['type']) && $_GET['type'] == 'edit' && isset($_GET['stockId']))
				{
					$this->getStockById($_GET['stockId']);
					if(isset($_POST['submit']))
					{
						$this->EditStock();
						header("Location:".CURHOST."/admin/?option=manageStock&error=Record updated successfully.");	
					}
				}elseif(isset($_GET['option']) && $_GET['option'] == 'manageStock' && isset($_GET['type']) && $_GET['type'] == 'del' && isset($_GET['stockId']))
				{
					$this->DeleteStock();
					header("Location:".CURHOST."/admin/?option=manageStock&error=Record deleted successfully.");
				}
				EFCommon::$smarty->display('admin/index.tpl');
				break;
			default:
				EFCommon::$smarty->assign('requestUri', $requestUri);
				EFCommon::$smarty->display('error.tpl');
				break;
		}
	}
}