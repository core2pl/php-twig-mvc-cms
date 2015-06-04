<?php
require 'mysqlpass.php';
require 'Service/PDO.php';
if(/*isset($_POST['online']) && isset($_SESSION['id'])*/true) {
	$pdo = new \Service\PDO();
	$pdo->setLastLogin($_SESSION['id']);
}
if(isset($_POST['list'])) {
	
}