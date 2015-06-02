<?php
namespace Service;

use \Model\Menu as Menu;

class Menus {
	
	private $menu_top;
	private $menu_left;
	private $menu_right;
	private $menu_footer;

	public function __construct() {
		$menu_top = array();
		$menu_left = array();
		$menu_right = array();
		$menu_footer = array();
	}
	
	
	public function makeMenu($position,$rank) {
		switch ($position) {
			case "left":
				$menu = new Menu("Menu główne");
				$menu->addItem("Strona główna","/");
				$menu_left[] = $menu->renderMenu();
				if($rank<=1) {
					$admin_menu = new Menu("Menu Admina");
					$admin_menu->addItem("Dodaj post", "/post/add/0");
					$menu_left[] = $admin_menu->renderMenu();
				}
				return $menu_left;
			break;
		}
	}
}