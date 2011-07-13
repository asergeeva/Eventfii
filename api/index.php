<?php
session_start();

require('../configs.php');
require('../controller/APIController.class.php');

$panelController = new APIController($smarty);
$panelController->getView($_SERVER['REQUEST_URI']);