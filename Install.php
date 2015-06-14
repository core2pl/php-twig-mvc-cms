<?php

use \Service\Json as Json;
use \Model\Menu as Menu;
use \Model\WidgetClock as Clock;


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
		
		$menu = new Menu(3,"Menu główne");
		$menu->addItem("Strona główna","/",3);
		$menu->addItem("Użytkownicy","/user/list",2);
		$menu_left[] = $menu;
		$about = new Menu(3,"O nas");
		$about->addItem("Autorzy", "/info/author",3);
		$admin_menu = new Menu(1,"Menu Admina");
		$admin_menu->addItem("Dodaj post", "/post/add/0",1);
		$admin_menu->addItem("Użytkownicy", "/admin/list",1);
		$admin_menu->addItem("Edytuj menu", "/admin/edit_menu", 1);
		$admin_menu->addItem("Zarządzanie stroną", "/admin/panel", 1);
		$clock = new Clock(3);
		$menu_left[] = $admin_menu;
		$menu_right[] = $clock;
		$menu_right[] = $about;
		$json->put("widgets_left", $menu_left);
		$json->put("widgets_right", $menu_right);
		$json->put("title", "Witaj na stronie!");
		$json->put("background", "/CSS/background.jpg");
		$json->put("favicon", "");
		$json->put("logo", "");
		$json->save();
	}
}