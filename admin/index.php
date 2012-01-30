<?php
session_start();
ini_set( "display_errors", 1);
require_once(realpath(dirname(__FILE__)).'/../configs.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../controller/AdminController.class.php');

$common = new EFCommon($smarty);
$adminController = new AdminController();
$adminController->getView($_SERVER['REQUEST_URI']);