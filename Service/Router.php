<?php
namespace Service;

class Router {

	public $vars;
	
	public function __construct() {
		$vars = array();
	}
	
	public function match($template, $uri) {
		$routing = array();
		$url = array();
		preg_match_all('/[a-zA-Z0-9\.\-_{}]+/', $template, $routing);
		preg_match_all('/[a-zA-Z0-9\.\-_]+/', $uri, $url);
		if(sizeof($routing[0])==sizeof($url[0])) {
			foreach ($routing[0] as $key => $rout) {
				if(preg_match('/\{[a-zA-Z0-9\.\-_]+\}/', $rout, $value)) {
					if($value[0] != $url[0][$key]) {
						preg_match('/[a-zA-Z0-9\.\-_]+/', $rout, $name);
						$this->vars[$name[0]] = $url[0][$key];
					}
				} else if(preg_match('/[a-zA-Z0-9\.\-_]+/', $rout, $value)) {
					if($value[0] != $url[0][$key]) {
						return false;
					}
				}
			}
			return true;
		} else {
			return false;
		}
	}
}