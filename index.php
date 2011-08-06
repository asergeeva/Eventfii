<?php
session_start();

require('configs.php');
require('controller/PanelController.class.php');

$panelController = new PanelController($smarty);
$panelController->getView($_SERVER['REQUEST_URI']);
