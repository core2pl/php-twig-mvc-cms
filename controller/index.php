<?php
class Index {
	
	public function __
	require_once 'Twig/Autoloader.php';
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem('views');
	$twig = new Twig_Environment($loader);
	echo $twig->render('page.html', array('text' => 'Hello world!'));
}