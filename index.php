<?php
session_start();

require_once(realpath(dirname(__FILE__)).'/configs.php');
require_once(realpath(dirname(__FILE__)).'/models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/controller/PanelController.class.php');

$common = new EFCommon($smarty);
$panelController = new PanelController();
$panelController->getView($_SERVER['REQUEST_URI']);
