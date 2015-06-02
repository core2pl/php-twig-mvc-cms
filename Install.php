<?php

use \Service\Json as Json;

class Install {

	public function __construct() {
	
	}
	
	public function install() {
		$json = new Json();
		$json->open("config.json");
		
		$routings = array();
		$routings['showPost'] = '/post/{id}';
		$routings['showPosts'] = '/show/{order}';
		$routings['modifyPost'] = '/post/{action}/{id}';
		$routings['modifyComment'] = '/comment/{action}/{id}';
		$routings['login'] = '/login';
		$routings['logout'] = '/logout';
		$routings['register'] = '/register';
		$routings['user'] = '/user/{action}/{id}';
		
		$json->put("routings", $routings);
		$json->save();
	}
}