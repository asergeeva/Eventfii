<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');

require_once(realpath(dirname(__FILE__)).'/controller/FBController.class.php');

if (isset($_GET['code'])){
	header("Location: ".FB_BASE_URL."?request_ids=".$_GET['request_ids']);
	exit;
}
session_start();

$common = new EFCommon($smarty);
$logoutUrl = EFCommon::$facebook->getLogoutUrl();
$loginUrl = EFCommon::$facebook->getLoginUrl(array(
	'scope' => 'email,publish_stream'
));

$user = EFCommon::$facebook->getUser();
if ($user) {
  try {
  	$fbController = new FBController();
  	FBController::renderPage();
  } catch (FacebookApiException $e) {
  	print_r($e);
    $user = null;
  }
} else {
	echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
	exit;
}