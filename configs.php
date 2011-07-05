<?php
require('libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('templates');
$smarty->setCompileDir('templates_c');
$smarty->setCacheDir('cache');
$smarty->setConfigDir('configs');

define('PATH', '/eventfii');
define('CURHOST', 'http://localhost'.PATH);

define('IMG_PATH', CURHOST.'/images');
define('EP_IMG_PATH', CURHOST.'/images/ep');
define('EC_IMG_PATH', CURHOST.'/images/ec');
define('FP_IMG_PATH', CURHOST.'/images/fp');
define('UP_IMG_PATH', CURHOST.'/images/up');

define('IMG_UPLOAD_PATH', 'upload/event/images');
define('CSV_UPLOAD_PATH', 'upload/event/csv');

define('IMG_UPLOAD', CURHOST.'/'.IMG_UPLOAD_PATH);
define('CSV_UPLOAD', CURHOST.'/'.CSV_UPLOAD_PATH);

define('JS_PATH', CURHOST.'/js');
define('CSS_PATH', CURHOST.'/css');

$smarty->assign('CURHOST', CURHOST);

$smarty->assign('IMG_PATH', IMG_PATH);
$smarty->assign('EP_IMG_PATH', EP_IMG_PATH);
$smarty->assign('EC_IMG_PATH', EC_IMG_PATH);
$smarty->assign('FP_IMG_PATH', FP_IMG_PATH);
$smarty->assign('UP_IMG_PATH', UP_IMG_PATH);

$smarty->assign('IMG_UPLOAD', IMG_UPLOAD);
$smarty->assign('CSV_UPLOAD', CSV_UPLOAD);

$smarty->assign('JS_PATH', JS_PATH);
$smarty->assign('CSS_PATH', CSS_PATH);

define('CONFOPT1', 90);
define('CONFOPT2', 65);
define('CONFOPT3', 35);
define('CONFOPT4', 15);
define('CONFOPT5', 4);
define('CONFOPT6', 1);
define('CONFELSE', 5);

// 0-20
define('GUESTRANGE1MIN', 65);
define('GUESTRANGE1MAX', 70);

// 21-50
define('GUESTRANGE2MIN', 45);
define('GUESTRANGE2MAX', 60);

// 51-X
define('GUESTRANGE3MIN', 40);
define('GUESTRANGE3MAX', 55);

define('EMAIL_REMINDER_TYPE', 0);
define('EMAIL_FOLLOWUP_TYPE', 1);

define('PAYPAL_REDIRECT_URL', 'https://www.sandbox.paypal.com/webscr&cmd=');
define('DEVELOPER_PORTAL', 'https://developer.paypal.com');
define('DEVICE_ID', 'PayPal_Platform_PHP_SDK');
define('APPLICATION_ID', 'APP-80W284485P519543T');

define('WTITLE', 'trueRSVP');
define('WSLOGAN', 'What\'s your number?');

$smarty->assign('WTITLE', WTITLE);
$smarty->assign('WSLOGAN', WSLOGAN);