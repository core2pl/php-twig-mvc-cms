<?php
session_start();
if(isset($_POST['online']) && isset($_SESSION['id'])) {
	$pdo = new \Service\PDO();
	$pdo->setLastLogin($_SESSION['id']);
}