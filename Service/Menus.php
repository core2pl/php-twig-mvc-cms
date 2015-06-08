<?php
namespace Service;

use \Model\Menu as Menu;

class Menus {
	
	private $menu_top;
	private $menu_left;
	private $menu_right;
	private $menu_footer;

	public function __construct() {
		$json = new \Service\Json();
		$json->open("config.json");
		$this->menu_top = $json->get("menu_top");
		$this->menu_left = $json->get("menu_left");
		$this->menu_right = $json->get("menu_right");
		$this->menu_footer = $json->get("menu_footer");
	}
	
	
	public function makeMenu($position,$rank) {
		switch ($position) {
			case "top":
				if(empty($this->menu_top)) return array();
				$menu_top = array();
				foreach ($this->menu_top as $menu) {
					if($menu->getRank()>=$rank)
					$menu_top[] = $menu->renderMenu($rank);
				}
				return $menu_top;
			break;
			case "left":
				if(empty($this->menu_left)) return array();
				$menu_left = array();
				foreach ($this->menu_left as $menu) {
					if($menu->getRank()>=$rank)
					$menu_left[] = $menu->renderMenu($rank);
				}
				return $menu_left;
			break;
			case "right":
				if(empty($this->menu_right)) return array();
				$menu_right = array();
				foreach ($this->menu_right as $menu) {
					if($menu->getRank()>=$rank)
					$menu_right[] = $menu->renderMenu($rank);
				}
				return $menu_right;
			break;
			case "footer":
				if(empty($this->menu_footer)) return array();
				$menu_footer = array();
				foreach ($this->menu_footer as $menu) {
					if($menu->getRank()>=$rank)
					$menu_footer[] = $menu->renderMenu($rank);
				}
				return $menu_footer;
			break;
		}
	}
}