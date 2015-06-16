<?php
if(!file_exists("installLock.php")) {
	if(isset($_POST['dbserver'])) {
		require 'SPLAutoLoad.php';
		require 'Install.php';
		$autoload = new SPLAutoLoad();
		$autoload->main();
		$install = new Install();
		$install->install();
		return;
	}
	require 'Twig/lib/Twig/Autoloader.php';
	require 'Service/Form.php';
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem('Template');
	$twig = new Twig_Environment($loader);
	$form = new \Service\Form("index.php", "POST");
	$form->addInput($html,$type,$name,$title,$autocomplete="off",$value="");
	$form->addInput("input","text","pagename","Nazwa Strony:",$autocomplete="off",$value="Moja strona");
	$form->addInput("input","text","dbserver","Adres serwera bazy danych:",$autocomplete="off",$value="localhost");
	$form->addInput("input","text","dblogin","Nazwa użytkownika bazy danych:",$autocomplete="off",$value="");
	$form->addInput("input","password","dbpass","Hasło:",$autocomplete="off",$value="");
	$form->addInput("input","text","dbname","Nazwa bazy danych",$autocomplete="off",$value="");
	$form->addInput("input","text","dbprefix","Prefix tabeli",$autocomplete="off",$value="cms");
	$form->addInput("input","text","nick","Nazwa administratora",$autocomplete="off",$value="");
	$form->addInput("input","text","email","Email:",$autocomplete="off",$value="");
	$form->addInput("input","password","password","Hasło",$autocomplete="off",$value="");
	
	echo $twig->render('Index.html.twig',array(
			'form' => $form
	));
	return;
} 
session_start();
require 'SPLAutoLoad.php';
require 'mysqlpass.php';


$autoload = new SPLAutoLoad();
$autoload->main();
use Controller\Page\Index;
$index = new Index();