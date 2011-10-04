<?php
require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');

session_start();

$common = new EFCommon($smarty);
$user = EFCommon::$facebook->getUser();
if ($user) {
  $logoutUrl = EFCommon::$facebook->getLogoutUrl();
} else {
  $loginUrl = EFCommon::$facebook->getLoginUrl();
  header('Location: '.$loginUrl);
}
print($user);