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

define('JS_PATH', CURHOST.'/js');
define('CSS_PATH', CURHOST.'/css');

$smarty->assign('CURHOST', CURHOST);

$smarty->assign('IMG_PATH', IMG_PATH);
$smarty->assign('EP_IMG_PATH', EP_IMG_PATH);
$smarty->assign('EC_IMG_PATH', EC_IMG_PATH);
$smarty->assign('FP_IMG_PATH', FP_IMG_PATH);
$smarty->assign('UP_IMG_PATH', UP_IMG_PATH);

$smarty->assign('JS_PATH', JS_PATH);
$smarty->assign('CSS_PATH', CSS_PATH);