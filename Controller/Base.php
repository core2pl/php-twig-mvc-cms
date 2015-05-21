<?php
namespace Controller;

require 'Twig/lib/Twig/Autoloader.php';

use \Service\PDO;

class Base {
	
	private $twig;
	private $pdo;
	private $json;
	
	function __construct() {
		$this->pdo = new \Service\PDO();
		$this->json = new \Service\Json();
	}
	
	function twig() {
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem('Template');
		$this->twig = new \Twig_Environment($loader);
	}
}