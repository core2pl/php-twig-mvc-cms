<?php
namespace Service;

use \Model\Menu as Menu;

class Menus {
	
	private $menu_top;
	private $menu_left;
	private $menu_right;
	private $menu_footer;

	public function __construct() {
		
	}
	
	
	public function makeMenus() {
		$menu = new Menu("Menu główne");
		$menu->addItem("strona główna","?");
		$menu_left = array();
		$menu_left[] = $menu->renderMenu();
		return $menu_left;
	}
}