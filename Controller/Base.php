<?php
namespace Controller;

require 'Twig/lib/Twig/Autoloader.php';

use \Service\PDO;
use \Service\Json;

class Base {
	
	public $twig;
	public $pdo;
	public $json;
	public $widgets;
	
	public function twig() {
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem('Template');
		$this->twig = new \Twig_Environment($loader);
		$this->pdo = new \Service\PDO();
		$this->json = new \Service\Json();
		$this->widgets = new \Service\Widgets();
	}
}