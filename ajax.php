<?php
session_start();
require 'SPLAutoLoad.php';
require 'mysqlpass.php';
require 'Twig/lib/Twig/Autoloader.php';
$autoload = new SPLAutoLoad();
$autoload->main();
use \Service\PDO;
use \Model\User;
use \Service\Table;

if(isset($_SESSION['id']) && $_POST['refresh']) {
	$pdo = new \Service\PDO();
	$pdo->setLastLogin($_SESSION['id']);
}
if(isset($_GET['admin_list'])) {
	\Twig_Autoloader::register();
	$loader = new \Twig_Loader_Filesystem('Template');
	$twig = new \Twig_Environment($loader);
	$pdo = new \Service\PDO();
	$users = $pdo->listUsers();
	$table = new \Service\Table();
	$table->addCell("Użytkownik", "black", null);
	$table->addCell("Ranga", "black", null);
	$table->addCell("Status", "black", null);
	$table->nextRow();
	foreach ($users as $user) {
		$table->addCell($user->getNick(),"black","/user/show/".$user->getId());
		switch ($user->getRank()) {
			case 1:
				$table->addCell("Admin", "red", null);
				break;
			case 2:
				$table->addCell("Użytkownik", "green", null);
				break;
			case 4:
				$table->addCell("Zbanowany", "black", null);
				break;
		}
		if($user->getStatus()=="Online")
			$table->addCell($user->getStatus(), "green", null);
		else
			$table->addCell($user->getStatus(), "red", null);
		$table->nextRow();
	}
	echo $twig->render("Table.html.twig", array(
			"table" => $table
	));
}

if(isset($_GET['list'])) {
	\Twig_Autoloader::register();
	$loader = new \Twig_Loader_Filesystem('Template');
	$twig = new \Twig_Environment($loader);
	$pdo = new \Service\PDO();
	$users = $pdo->listUsers();
	$table = new \Service\Table();
	$table->addCell("Użytkownik", "black", null);
	$table->addCell("Status", "black", null);
	$table->nextRow();
	foreach ($users as $user) {
		$table->addCell($user->getNick(),"black","/user/show/".$user->getId());
		if($user->getStatus()=="Online")
			$table->addCell($user->getStatus(), "green", null);
		else
			$table->addCell($user->getStatus(), "red", null);
		$table->nextRow();
	}
	echo $twig->render("Table.html.twig", array(
			"table" => $table
	));
}