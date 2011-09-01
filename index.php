<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/configs.php');
require_once(realpath(dirname(__FILE__)).'/models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/models/AbstractMessage.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFEmailMessage.class.php');
require_once(realpath(dirname(__FILE__)).'/controller/PanelController.class.php');
require_once(realpath(dirname(__FILE__)).'/db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/models/Event.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFCore.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFTwitter.class.php');
require_once(realpath(dirname(__FILE__)).'/models/User.class.php');
require_once(realpath(dirname(__FILE__)).'/models/EFSMS.class.php');
require_once(realpath(dirname(__FILE__)).'/models/FileUploader.class.php');
require_once(realpath(dirname(__FILE__)).'/libs/OpenInviter/openinviter.php');
require_once(realpath(dirname(__FILE__)).'/libs/QR/qrlib.php');
require_once(realpath(dirname(__FILE__)).'/libs/Facebook/facebook.php');

// header("Location: ". LAUNCH_PAGE);

session_start();

$common = new EFCommon($smarty);
$panelController = new PanelController();

$panelController->getView($_SERVER['REQUEST_URI']);
