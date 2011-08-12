<?php

require('configs.php');
require('controller/PanelController.class.php');

require_once(realpath(dirname(__FILE__)).'/db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/models/Event.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFCore.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFTwitter.class.php');
require_once(realpath(dirname(__FILE__)).'/models/User.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFSMS.class.php');
require_once(realpath(dirname(__FILE__)).'/models/FileUploader.class.php');
require_once(realpath(dirname(__FILE__)).'/libs/OpenInviter/openinviter.php');

session_start();

$panelController = new PanelController($smarty);
$panelController->getView($_SERVER['REQUEST_URI']);
