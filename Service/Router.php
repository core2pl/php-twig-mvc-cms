<?php
namespace Service;

class Router {

	public $vars;
	
	public function __construct() {
	
	}
	
	public function match($rout, $uri) {
		if(preg_match($rout, $uri))	{
			$this->vars = explode("/", $uri)[2];
			return true;
		}
		return false;
	}
}