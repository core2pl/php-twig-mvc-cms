<?php
namespace Controller\Page;

use Controller\Index as IndexController;
use Service\Router;
use Service\Json;

class Index {
	
	function __construct() {
		$ctrl = new IndexController();

		$router = new Router();
		$json = new Json();
		$json->open("config.json");
		$routings = $json->get("routings");
		$uri = $_SERVER["REQUEST_URI"];
		
		foreach ($routings as $rout => $value) {
			if($router->match($value,$uri)) {
				$ctrl->$rout($router->vars);
			}
		}
	}
}