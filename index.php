
<?php

// Get controller dan method dari URL
$c = $_GET['c'] ?? 'Auth'; // Default controller
$m = $_GET['m'] ?? 'register';    // Default method

// Load base controller, mewarisi fungsi induk
require_once("Controller/Controller.class.php");
require_once("Controller/" . $c . "Controller.class.php");

$controller = new $c();
$controller->$m();