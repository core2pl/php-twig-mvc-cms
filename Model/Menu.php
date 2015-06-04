<?php
namespace Model;

use Model\Base;

class Menu extends Base {

	private $items,$rank;
	
	public function __construct($name,$rank) {
		$this->name = $name;
		$this->items = array();
		$this->rank = $rank;
	}
	
	public function renderMenu($rank) {
		if ($rank > $this->rank) 
			return;
		$menu = (object) null;
		foreach ($this->items as $item) {
			if($rank <= $item->rank) {
				$menu->items[] = $item;
			}
		}
		$menu->title = $this->name;
		return $menu;
	}
	
	public function addItem( $name, $value, $rank) {
		$item = (object) null;
		$item->name = $name;
		$item->value = $value;
		$item->rank = $rank;
		$this->items[] = $item;
	}
	
	public function addItems(array $items) {
		foreach ($items as $item) {
			$this->items[] = $item;
		}
	}
	
	public function getRank() {
		return $this->rank;
	}
}