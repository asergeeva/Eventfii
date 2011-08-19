<?php
require_once(realpath(dirname(__FILE__)).'/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir(realpath(dirname(__FILE__)).'/templates');
$smarty->setCompileDir(realpath(dirname(__FILE__)).'/templates_c');
$smarty->setCacheDir(realpath(dirname(__FILE__)).'/cache');
$smarty->setConfigDir(realpath(dirname(__FILE__)).'/configs');

define('DB_HOST', '127.0.0.1:3306');
define('DB_USER', 'glaksmono');
define('DB_PASS', '12345');
define('DB_NAME', 'eventfii');
define('DEBUG', true);

define('PATH', '/Eventfii');
define('CURHOST', 'http://localhost'.PATH);
define('IMG_PATH', CURHOST.'/images');
define('IMG_UPLOAD_PATH', 'upload/event/images');
define('CSV_UPLOAD_PATH', 'upload/event/csv');
define('IMG_UPLOAD', CURHOST.'/'.IMG_UPLOAD_PATH);
define('CSV_UPLOAD', CURHOST.'/'.CSV_UPLOAD_PATH);
define('JS_PATH', CURHOST.'/js');
define('CSS_PATH', CURHOST.'/css');
define('EVENT_URL', CURHOST.'/event');
define('NUM_TWEETS', 5);

$smarty->assign('CURHOST', CURHOST);
$smarty->assign('IMG_PATH', IMG_PATH);
$smarty->assign('IMG_UPLOAD', IMG_UPLOAD);
$smarty->assign('CSV_UPLOAD', CSV_UPLOAD);
$smarty->assign('JS_PATH', JS_PATH);
$smarty->assign('CSS_PATH', CSS_PATH);
$smarty->assign('EVENT_URL', EVENT_URL);
$smarty->assign('NUM_TWEETS', NUM_TWEETS);

// Yes
define('CONFOPT1', 90);
define('CONFOPT2', 65);

// May be
define('CONFOPT3', 35);

// No
define('CONFOPT4', 15);
define('CONFOPT5', 4);

// A spam
define('CONFOPT6', 1);

// Not yet responding
define('CONFELSE', 5);

$smarty->assign('CONFOPT1', CONFOPT1);
$smarty->assign('CONFOPT2', CONFOPT2);
$smarty->assign('CONFOPT3', CONFOPT3);
$smarty->assign('CONFOPT4', CONFOPT4);
$smarty->assign('CONFOPT5', CONFOPT5);
$smarty->assign('CONFOPT6', CONFOPT6);
$smarty->assign('CONFELSE', CONFELSE);

// 0-20
define('GUESTRANGE1MIN', 65);
define('GUESTRANGE1MAX', 70);

// 21-50
define('GUESTRANGE2MIN', 45);
define('GUESTRANGE2MAX', 60);

// 51-X
define('GUESTRANGE3MIN', 40);
define('GUESTRANGE3MAX', 55);

define('EMAIL_REMINDER_TYPE', 1);
define('SMS_REMINDER_TYPE', 2);

define('PAYPAL_REDIRECT_URL', 'https://www.sandbox.paypal.com/webscr&cmd=');
define('DEVELOPER_PORTAL', 'https://developer.paypal.com');
define('DEVICE_ID', 'PayPal_Platform_PHP_SDK');
define('APPLICATION_ID', 'APP-80W284485P519543T');

define('WTITLE', 'trueRSVP');
define('WSLOGAN', 'What\'s your number?');

$smarty->assign('WTITLE', WTITLE);
$smarty->assign('WSLOGAN', WSLOGAN);
