<?php
namespace Controller;

require 'Twig/lib/Twig/Autoloader.php';

class Base {
	
	public $twig;
	
	function twig() {
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem('Template');
		$this->twig = new \Twig_Environment($loader);
	}
}