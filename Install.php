<?php

use \Service\Json as Json;

class Install {

	public function __construct() {
	
	}
	
	public function install() {
		$json = new Json();
		$json->open("config.json");
		
		$routings = array();
		$routings['showPost'] = '/^\/post\/[0-9]+$/D';
		$routings['showPosts'] = '/^\/show\/[a-z]+$/D';
		
		$json->put("routings", $routings);
		$json->save();
	}
}