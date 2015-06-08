<?php

use \Service\Json as Json;
use \Model\Menu as Menu;

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
		$routings['users'] = '/user/{action}';
		$routings['user'] = '/user/{action}/{id}';
		$routings['admin'] = '/admin/{action}';
		$routings['easterEgg'] = '/easter';
		
		$json->put("routings", $routings);
		
		$menu = new Menu("Menu główne",3);
		$menu->addItem("Strona główna","/",3);
		$menu->addItem("Użytkownicy","/user/list",2);
		$menu_left[] = $menu;
		$admin_menu = new Menu("Menu Admina",1);
		$admin_menu->addItem("Dodaj post", "/post/add/0",1);
		$admin_menu->addItem("Użytkownicy", "/admin/list",1);
		$admin_menu->addItem("Edytuj menu", "/admin/edit_menu", 1);
		$admin_menu->addItem("Zarządzanie stroną", "/admin/panel", 1);
		$menu_left[] = $admin_menu;
		$json->put("menu_left", $menu_left);
		$json->put("title", "Witaj na stronie!");
		$json->put("background", "/CSS/background.jpg");
		$json->save();
	}
}