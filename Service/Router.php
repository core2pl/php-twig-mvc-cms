<?php
namespace Service;

class Router {

	public $vars;
	
	public function __construct() {
	
	}
	
	public function match($template, $uri) {
		$routing = array();
		$url = array();
		preg_match('/^[a-zA-Z0-9\.\-_]$/D', $template, $routing);
		preg_match('/^[a-zA-Z0-9\.\-_]$/D', $uri, $url);
		if(sizeof($routing)==sizeof($url)) {
			foreach ($routing as $key => $rout) {
				if(preg_match('/^\{[a-zA-Z0-9\.\-_]\}$/D', $rout, $var)) {
					preg_match('/^[a-zA-Z0-9\.\-_]$/D', $var, $name);
					$this->vars[$name] = $url[$key]; 
				}
			}
			return true;
		} else {
			return false;
		}
	}
}