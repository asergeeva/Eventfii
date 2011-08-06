<?php
session_start();

require_once(realpath('../configs.php'));
require_once(realpath('../controller/APIController.class.php'));

$panelController = new APIController($smarty);
$panelController->getView($_SERVER['REQUEST_URI']);