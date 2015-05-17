<?php
namespace Model;

use Model\Base;

class Menu extends Base {

	private $items;
	
	public function __construct($name) {
		$this->name = $name;
		$this->items = array();
	}
	
	public function renderMenu() {
		$menu = (object) null;
		$menu->items = $this->items;
		$menu->title = $this->name;
		return $menu;
	}
	
	public function addItem( $name, $value) {
		$item = (object) null;
		$item->name = $name;
		$item->value = $value;
		$this->items[] = $item;
	}
	
	public function addItems(array $items) {
		foreach ($items as $item) {
			$this->items[] = $item;
		}
	}
}