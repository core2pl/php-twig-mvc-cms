<?php
namespace Model;

use Model\Widget as Widget;

class Menu extends Widget {

	private $name,$items;
	
	public function __construct($rank,$name) {
		parent::__construct($rank);
		$this->name = $name;
		$this->items = array();
		$this->setType("menu");
	}
	
	public function renderWidget($rank) {
		if ($rank > $this->getRank())
			return;
		$menu = (object) null;
		foreach ($this->items as $item) {
			if($rank <= $item->rank) {
				$menu->items[] = $item;
			}
		}
		$menu->title = $this->name;
		$menu->type = "menu";
		return $menu;
	}
	
	public function addItem($name, $value, $rank) {
		$item = (object) null;
		$item->name = $name;
		$item->value = $value;
		$item->rank = $rank;
		$this->items[] = $item;
	}
	
	public function removeItem($id) {
		if($id < sizeof($this->items)) {
			for($i = $id; $i < sizeof($this->items)-1; $i++) {
				$this->items[$i] = $this->items[$i+1];
			}
			unset($this->items[sizeof($this->items)-1]);
		}
	}
	
	public function modifyItem($id, $name, $value) {
		if(!isset($this->items[$id])) {
			return false;
		} else {
			$this->items[$id]->$name = $value;
			return true;
		}
	}
	
	public function getItem($id,$name) {
		return $this->items[$id]->$name;
	}
	
	public function moveItem($id,$newid) {
		if($newid<sizeof($this->items))
		if($newid > $id) {
			$temp = $this->items[$id];
			for($i = $id; $i < $newid; $i++) {
				$this->items[$i] = $this->items[$i+1];
			}
			$this->items[$newid] = $temp;
		} else {
			$temp = $this->items[$id];
			for($i = $id; $i > $newid; $i--) {
				$this->items[$i] = $this->items[$i-1];
			}
			$this->items[$newid] = $temp;
		}
	}
	
	public function addItems(array $items) {
		foreach ($items as $item) {
			$this->items[] = $item;
		}
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
}
