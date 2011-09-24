<?php
session_start();

require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../controller/CronController.class.php');

$common = new EFCommon($smarty);
$cronController = new CronController();
$cronController->getView($_SERVER['REQUEST_URI']);